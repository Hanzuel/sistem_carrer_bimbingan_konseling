<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CareerMaterialController extends Controller
{
    public function index()
    {
        $materials = LearningMaterial::latest()->get();

        return view('bk.materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:500'],
            'content_type' => ['required', Rule::in(['text', 'file', 'link'])],
            'body' => ['nullable', 'string', 'required_if:content_type,text'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120', 'required_if:content_type,file'],
            'external_url' => ['nullable', 'url', 'max:255', 'required_if:content_type,link'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'body.required_if' => 'Isi materi teks wajib diisi untuk tipe konten Teks.',
            'file.required_if' => 'File PDF wajib diunggah untuk tipe konten File.',
            'file.mimes' => 'Format file harus berupa PDF.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
            'external_url.required_if' => 'Tautan URL wajib diisi untuk tipe konten Tautan.',
            'external_url.url' => 'Format tautan harus berupa URL yang valid (diawali http:// atau https://).',
        ]);

        $filePath = null;
        if ($validated['content_type'] === 'file' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('career-materials', 'public');
        }

        $bodyText = $validated['content_type'] === 'text' ? $validated['body'] : null;

        LearningMaterial::create([
            'user_id' => $request->user()?->id,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'category' => $validated['category'],
            'summary' => $validated['summary'],
            'format_type' => $validated['content_type'],
            'content_type' => $validated['content_type'],
            'body' => $bodyText,
            'content' => $bodyText,
            'file_path' => $filePath,
            'external_url' => $validated['content_type'] === 'link' ? $validated['external_url'] : null,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return back()->with('status', 'Modul panduan berhasil ditambahkan.');
    }

    public function update(Request $request, LearningMaterial $material)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:500'],
            'content_type' => ['required', Rule::in(['text', 'file', 'link'])],
            'body' => ['nullable', 'string', 'required_if:content_type,text'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'external_url' => ['nullable', 'url', 'max:255', 'required_if:content_type,link'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $filePath = $material->file_path;

        if ($validated['content_type'] === 'file') {
            if ($request->hasFile('file')) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file')->store('career-materials', 'public');
            } elseif (!$filePath) {
                return back()->withErrors(['file' => 'Silakan unggah file PDF terlebih dahulu.'])->withInput();
            }
        } else {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
        }

        $bodyText = $validated['content_type'] === 'text' ? $validated['body'] : null;

        $material->update([
            'title' => $validated['title'],
            'slug' => $material->title !== $validated['title']
                ? $this->generateUniqueSlug($validated['title'], $material->id)
                : $material->slug,
            'category' => $validated['category'],
            'summary' => $validated['summary'],
            'format_type' => $validated['content_type'],
            'content_type' => $validated['content_type'],
            'body' => $bodyText,
            'content' => $bodyText,
            'file_path' => $filePath,
            'external_url' => $validated['content_type'] === 'link' ? $validated['external_url'] : null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('status', 'Modul panduan berhasil diperbarui.');
    }

    public function destroy(LearningMaterial $material)
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('status', 'Modul panduan berhasil dihapus.');
    }

    public function siswaIndex()
    {
        $materials = LearningMaterial::where('is_published', true)
            ->latest()
            ->get();

        return view('siswa.pusat-materi', compact('materials'));
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'modul-karier';
        $slug = $baseSlug;
        $counter = 1;

        while (
            LearningMaterial::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}