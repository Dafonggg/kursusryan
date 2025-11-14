<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Enums\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    /**
     * Display a listing of materials for a course
     */
    public function index(Course $course)
    {
        $materials = CourseMaterial::where('course_id', $course->id)
            ->orderBy('order')
            ->get();
        
        return view('admin.materials.index', compact('course', 'materials'));
    }

    /**
     * Show the form for creating a new material
     */
    public function create(Course $course)
    {
        return view('admin.materials.create', compact('course'));
    }

    /**
     * Store a newly created material
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'type' => 'required|in:video,document',
            'title' => 'required|string|max:255',
            'scope' => 'required|in:online,offline,both',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // Max 10MB
            'url' => 'nullable|url|max:500',
        ], [
            'type.required' => 'Tipe materi wajib diisi',
            'title.required' => 'Judul materi wajib diisi',
            'scope.required' => 'Scope materi wajib diisi',
            'path.file' => 'File yang diunggah tidak valid',
            'url.url' => 'URL tidak valid',
        ]);

        // Validasi: harus ada path atau url
        if (!$request->hasFile('path') && !$request->filled('url')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi salah satu']);
        }

        // Handle file upload
        if ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('materials', 'public');
            $validated['url'] = null;
        } else {
            $validated['path'] = null;
        }

        $validated['course_id'] = $course->id;
        $validated['type'] = MaterialType::from($validated['type']);

        CourseMaterial::create($validated);

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified material
     */
    public function edit(Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        return view('admin.materials.edit', compact('course', 'material'));
    }

    /**
     * Update the specified material
     */
    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => 'required|in:video,document',
            'title' => 'required|string|max:255',
            'scope' => 'required|in:online,offline,both',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
            'url' => 'nullable|url|max:500',
        ]);

        // Validasi: harus ada path atau url jika tidak ada yang sudah ada
        if (!$request->hasFile('path') && !$request->filled('url') && !$material->path && !$material->url) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi salah satu']);
        }

        // Handle file upload
        if ($request->hasFile('path')) {
            // Delete old file if exists
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = $request->file('path')->store('materials', 'public');
            $validated['url'] = null;
        } elseif ($request->filled('url')) {
            // If new URL is provided and old file exists, delete it
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = null;
        }

        $validated['type'] = MaterialType::from($validated['type']);

        $material->update($validated);

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material
     */
    public function destroy(Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        // Delete file if exists
        if ($material->path && Storage::disk('public')->exists($material->path)) {
            Storage::disk('public')->delete($material->path);
        }

        $material->delete();

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil dihapus!');
    }
}

