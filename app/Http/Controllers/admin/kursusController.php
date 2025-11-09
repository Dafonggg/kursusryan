<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kursus;
class kursusController extends Controller
{
    /**
     * Display the kursus page.
     */
    public function kursus()
    {
        $kursus = Kursus::orderBy('created_at', 'desc')->paginate(6);
        return view('admin.kursus', compact('kursus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tambah-kursus');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kursus' => 'required',    
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'durasi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Generate kode kursus otomatis (KRS001, KRS002, dst)
        $lastKursus = Kursus::whereNotNull('kode_kursus')
            ->where('kode_kursus', 'like', 'KRS%')
            ->orderByRaw('CAST(SUBSTRING(kode_kursus, 4) AS UNSIGNED) DESC')
            ->first();
        
        $nextNumber = 1;
        
        if ($lastKursus && $lastKursus->kode_kursus) {
            // Extract number from last kode_kursus (e.g., KRS001 -> 1)
            $lastNumber = (int) substr($lastKursus->kode_kursus, 3);
            $nextNumber = $lastNumber + 1;
        }
        
        $validatedData['kode_kursus'] = 'KRS' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kursus'), $imageName);
            $validatedData['gambar'] = 'images/kursus/' . $imageName;
        }

        Kursus::create($validatedData);
        return redirect()->route('dashboard.kursus')->with('success', 'Kursus berhasil ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.detail-kursus', compact('kursus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kursus = Kursus::where('id_kursus', $id)->first();
        return view('admin.edit-kursus', compact('kursus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_kursus' => 'required',    
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'durasi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kursus = Kursus::where('id_kursus', $id)->first();
        
        if (!$kursus) {
            return redirect()->route('dashboard.kursus')->with('error', 'Kursus tidak ditemukan');
        }

        // Handle file upload if new image is provided
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($kursus->gambar && file_exists(public_path($kursus->gambar))) {
                unlink(public_path($kursus->gambar));
            }
            
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kursus'), $imageName);
            $validatedData['gambar'] = 'images/kursus/' . $imageName;
        } else {
            // Keep existing image if no new image uploaded
            unset($validatedData['gambar']);
        }

        $kursus->update($validatedData);
        return redirect()->route('dashboard.kursus')->with('success', 'Kursus berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kursus = Kursus::where('id_kursus', $id)->first();
        
        if (!$kursus) {
            return redirect()->route('dashboard.kursus')->with('error', 'Kursus tidak ditemukan');
        }

        // Delete image file if exists
        if ($kursus->gambar && file_exists(public_path($kursus->gambar))) {
            unlink(public_path($kursus->gambar));
        }

        $kursus->delete();
        return redirect()->route('dashboard.kursus')->with('success', 'Kursus berhasil dihapus');
    }
}
