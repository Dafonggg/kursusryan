<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\materin;
use App\Models\Kursus;

class materinController extends Controller
{
    /**
     * Display the materi kursus page.
     */
    public function materiKursus()
    {
        $materi = materin::with('kursus')->orderBy('created_at', 'desc')->paginate(10);
        $kursus = Kursus::orderBy('nama_kursus', 'asc')->get();
        return view('admin.materi-kursus', compact('materi', 'kursus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kursus = Kursus::orderBy('nama_kursus', 'asc')->get();
        return view('admin.tambah-materi', compact('kursus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_kursus' => 'required|exists:kursus,id_kursus',
            'jenis_file' => 'required|in:pdf,doc,ppt,video,link',
            'file_materin' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'link_video' => 'nullable|url',
        ]);

        // Validate based on jenis_file
        if (in_array($validatedData['jenis_file'], ['pdf', 'doc', 'ppt'])) {
            if (!$request->hasFile('file_materin')) {
                return redirect()->back()->withErrors(['file_materin' => 'File harus diupload untuk jenis file ini.'])->withInput();
            }
        } elseif (in_array($validatedData['jenis_file'], ['video', 'link'])) {
            if (!$request->filled('link_video')) {
                return redirect()->back()->withErrors(['link_video' => 'Link video harus diisi untuk jenis file ini.'])->withInput();
            }
        }

        // Handle file upload
        if ($request->hasFile('file_materin')) {
            $file = $request->file('file_materin');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/materi'), $fileName);
            $validatedData['file_materin'] = 'storage/materi/' . $fileName;
        }

        materin::create($validatedData);
        return redirect()->route('dashboard.materi-kursus')->with('success', 'Materi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $materi = materin::with('kursus')->where('id_materin', $id)->firstOrFail();
        return view('admin.detail-materi', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $materi = materin::where('id_materin', $id)->firstOrFail();
        $kursus = Kursus::orderBy('nama_kursus', 'asc')->get();
        return view('admin.edit-materi', compact('materi', 'kursus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_kursus' => 'required|exists:kursus,id_kursus',
            'jenis_file' => 'required|in:pdf,doc,ppt,video,link',
            'file_materin' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'link_video' => 'nullable|url',
        ]);

        $materi = materin::where('id_materin', $id)->first();
        
        if (!$materi) {
            return redirect()->route('dashboard.materi-kursus')->with('error', 'Materi tidak ditemukan');
        }

        // Validate based on jenis_file
        if (in_array($validatedData['jenis_file'], ['pdf', 'doc', 'ppt'])) {
            // If changing to file type but no new file uploaded and no existing file
            if (!$request->hasFile('file_materin') && !$materi->file_materin) {
                return redirect()->back()->withErrors(['file_materin' => 'File harus diupload untuk jenis file ini.'])->withInput();
            }
            // Clear link_video if switching to file type
            $validatedData['link_video'] = null;
        } elseif (in_array($validatedData['jenis_file'], ['video', 'link'])) {
            if (!$request->filled('link_video') && !$materi->link_video) {
                return redirect()->back()->withErrors(['link_video' => 'Link video harus diisi untuk jenis file ini.'])->withInput();
            }
            // Clear file_materin if switching to video/link type
            if ($request->hasFile('file_materin')) {
                // Delete old file if exists
                if ($materi->file_materin && file_exists(public_path($materi->file_materin))) {
                    unlink(public_path($materi->file_materin));
                }
            } else {
                // Clear file_materin when switching to video/link
                if ($materi->file_materin && file_exists(public_path($materi->file_materin))) {
                    unlink(public_path($materi->file_materin));
                }
                $validatedData['file_materin'] = null;
            }
        }

        // Handle file upload if new file is provided
        if ($request->hasFile('file_materin')) {
            // Delete old file if exists
            if ($materi->file_materin && file_exists(public_path($materi->file_materin))) {
                unlink(public_path($materi->file_materin));
            }
            
            $file = $request->file('file_materin');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/materi'), $fileName);
            $validatedData['file_materin'] = 'storage/materi/' . $fileName;
        } else {
            // Keep existing file if no new file uploaded and jenis_file is file type
            if (in_array($validatedData['jenis_file'], ['pdf', 'doc', 'ppt'])) {
                unset($validatedData['file_materin']);
            }
        }

        $materi->update($validatedData);
        return redirect()->route('dashboard.materi-kursus')->with('success', 'Materi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $materi = materin::where('id_materin', $id)->first();
        
        if (!$materi) {
            return redirect()->route('dashboard.materi-kursus')->with('error', 'Materi tidak ditemukan');
        }

        // Delete file if exists
        if ($materi->file_materin && file_exists(public_path($materi->file_materin))) {
            unlink(public_path($materi->file_materin));
        }

        $materi->delete();
        return redirect()->route('dashboard.materi-kursus')->with('success', 'Materi berhasil dihapus');
    }
}
