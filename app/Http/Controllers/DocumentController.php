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
        $fileHash = hash_file('sha256', storage_path('app/private/' . $path));

        $document->versions()->create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'version' => '1.0',
            'change_summary' => 'Initial upload',
            'file_hash' => $fileHash,
        ]);

        \App\Models\ActivityLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'Documento y versión 1.0 subida con éxito.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento subido correctamente.');
    }

    public function show(\Illuminate\Http\Request $request, \App\Models\Document $document)
    {
        $document->load(['versions.uploader', 'user', 'activityLogs' => function ($query) {
            $query->latest();
        }]);

        \App\Models\ActivityLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'description' => 'El usuario inspeccionó los detalles del documento.',
            'ip_address' => $request->ip(),
        ]);

        return view('documents.show', compact('document'));
    }

    public function download(\Illuminate\Http\Request $request, \App\Models\DocumentVersion $version)
    {
        \App\Models\ActivityLog::create([
            'document_id' => $version->document_id,
            'user_id' => auth()->id(),
            'action' => 'downloaded',
            'description' => 'Descargó la versión v' . $version->version . ' (' . $version->original_name . ')',
            'ip_address' => $request->ip(),
        ]);

        return response()->download(storage_path('app/private/' . $version->file_path), $version->original_name);
    }

    public function preview(\Illuminate\Http\Request $request, \App\Models\DocumentVersion $version)
    {
        \App\Models\ActivityLog::create([
            'document_id' => $version->document_id,
            'user_id' => auth()->id(),
            'action' => 'previewed',
            'description' => 'Previsualizó el archivo v' . $version->version,
            'ip_address' => $request->ip(),
        ]);

        $filePath = storage_path('app/private/' . $version->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file($filePath);
    }

    public function storeVersion(\Illuminate\Http\Request $request, \App\Models\Document $document)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'change_summary' => 'required|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');
        $fileHash = hash_file('sha256', storage_path('app/private/' . $path));

        $lastVersion = $document->versions()->latest()->first();
        $nextVersionNumber = $lastVersion ? ((float)$lastVersion->version + 1.0) : 1.0;
        $nextVersionString = number_format($nextVersionNumber, 1);

        $document->versions()->create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'version' => $nextVersionString,
            'change_summary' => $request->change_summary,
            'file_hash' => $fileHash,
        ]);

        \App\Models\ActivityLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'version_uploaded',
            'description' => 'Subió una nueva versión: v' . $nextVersionString,
            'ip_address' => $request->ip(),
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

        \App\Models\ActivityLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'description' => 'Cambió el estado del documento a: ' . ucfirst($request->status),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Estado actualizado a ' . ucfirst($request->status) . '.');
    }
}
