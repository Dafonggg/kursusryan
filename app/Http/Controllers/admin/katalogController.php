<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Katalog;

class katalogController extends Controller
{
    /**
     * Display the katalog produk page.
     */
    public function katalogProduk()
    {
        $katalogs = Katalog::orderBy('created_at', 'desc')->paginate(6);
        return view('admin.katalog-produk', compact('katalogs'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     * Note: Saat ini form tambah produk menggunakan modal di halaman katalogProduk().
     * Method ini dapat digunakan jika ingin menggunakan halaman terpisah untuk form tambah produk.
     */
    public function create()
    {
        // Jika ingin menggunakan halaman terpisah, uncomment baris berikut:
        // return view('admin.tambah-katalog');
        
        // Saat ini tidak digunakan karena menggunakan modal
        return redirect()->route('dashboard.katalog-produk');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_katalog' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/katalog'), $imageName);
            $validatedData['gambar'] = 'images/katalog/' . $imageName;
        } else {
            $validatedData['gambar'] = 'default.jpg';
        }

        Katalog::create($validatedData);
        return redirect()->route('dashboard.katalog-produk')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $katalog = Katalog::where('id_katalog', $id)->first();
        
        if (!$katalog) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }
            return redirect()->route('dashboard.katalog-produk')->with('error', 'Produk tidak ditemukan');
        }
        
        if (request()->expectsJson()) {
            return response()->json($katalog);
        }
        
        return view('admin.detail-katalog', compact('katalog'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * Note: Saat ini form edit produk menggunakan modal di halaman katalogProduk().
     * Method ini dapat digunakan jika ingin menggunakan halaman terpisah untuk form edit produk.
     */
    public function edit(string $id)
    {
        // Jika ingin menggunakan halaman terpisah, uncomment baris berikut:
        // $katalog = Katalog::where('id_katalog', $id)->first();
        // if (!$katalog) {
        //     return redirect()->route('dashboard.katalog-produk')->with('error', 'Produk tidak ditemukan');
        // }
        // return view('admin.edit-katalog', compact('katalog'));
        
        // Saat ini tidak digunakan karena menggunakan modal
        return redirect()->route('dashboard.katalog-produk');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_katalog' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        $katalog = Katalog::where('id_katalog', $id)->first();
        
        if (!$katalog) {
            return redirect()->route('dashboard.katalog-produk')->with('error', 'Produk tidak ditemukan');
        }

        // Handle file upload if new image is provided
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($katalog->gambar && $katalog->gambar !== 'default.jpg' && file_exists(public_path($katalog->gambar))) {
                unlink(public_path($katalog->gambar));
            }
            
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/katalog'), $imageName);
            $validatedData['gambar'] = 'images/katalog/' . $imageName;
        } else {
            // Keep existing image if no new image uploaded
            unset($validatedData['gambar']);
        }

        $katalog->update($validatedData);
        return redirect()->route('dashboard.katalog-produk')->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $katalog = Katalog::where('id_katalog', $id)->first();
        
        if (!$katalog) {
            return redirect()->route('dashboard.katalog-produk')->with('error', 'Produk tidak ditemukan');
        }

        // Delete image file if exists
        if ($katalog->gambar && $katalog->gambar !== 'default.jpg' && file_exists(public_path($katalog->gambar))) {
            unlink(public_path($katalog->gambar));
        }

        $katalog->delete();
        return redirect()->route('dashboard.katalog-produk')->with('success', 'Produk berhasil dihapus');
    }
}
