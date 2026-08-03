<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = \App\Models\Document::with('user', 'versions')->latest()->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        $document = \App\Models\Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'status' => 'draft',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        $document->versions()->create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'version' => '1.0',
            'change_summary' => 'Initial upload',
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento subido correctamente.');
    }

    public function show(\App\Models\Document $document)
    {
        $document->load('versions.uploader', 'user');
        return view('documents.show', compact('document'));
    }

    public function download(\App\Models\DocumentVersion $version)
    {
        return response()->download(storage_path('app/private/' . $version->file_path), $version->original_name);
    }

    public function storeVersion(\Illuminate\Http\Request $request, \App\Models\Document $document)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'change_summary' => 'required|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        $lastVersion = $document->versions()->latest()->first();
        $nextVersionNumber = $lastVersion ? ((float)$lastVersion->version + 1.0) : 1.0;

        $document->versions()->create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'version' => number_format($nextVersionNumber, 1),
            'change_summary' => $request->change_summary,
        ]);

        return back()->with('success', 'Nueva versión subida correctamente.');
    }

    public function updateStatus(\Illuminate\Http\Request $request, \App\Models\Document $document)
    {
        $request->validate([
            'status' => 'required|in:draft,pending,approved,rejected',
        ]);

        if (in_array($request->status, ['approved', 'rejected']) && !auth()->user()->isReviewer()) {
            abort(403, 'No tienes permisos para aprobar o rechazar documentos.');
        }

        $document->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado a ' . ucfirst($request->status) . '.');
    }
}
