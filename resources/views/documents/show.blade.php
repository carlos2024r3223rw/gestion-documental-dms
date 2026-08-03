<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('documents.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Detalles: {{ $document->title }}
                </h2>
            </div>
            
            <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full shadow-sm
                {{ $document->status === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : ($document->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : ($document->status === 'pending' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200')) }}">
                Estado: {{ $document->status === 'draft' ? 'Borrador' : ucfirst($document->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Columna Izquierda (Info y Flujos) -->
                <div class="space-y-8">
                    <!-- Información General -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-lg font-extrabold text-gray-900 border-b pb-3 mb-4">Metadatos del Documento</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase">Descripción</p>
                                    <p class="text-sm text-gray-800 mt-1">{{ $document->description ?: 'Sin descripción.' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase">Propietario</p>
                                    <p class="text-sm text-gray-800 mt-1 font-medium">{{ $document->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase">Fecha de Creación</p>
                                    <p class="text-sm text-gray-800 mt-1">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flujo de Aprobación (Workflows) -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-lg font-extrabold text-gray-900 border-b pb-3 mb-4">Flujo de Trabajo (Workflow)</h3>
                            
                            <form action="{{ route('documents.status.update', $document) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Cambiar Estado a:</label>
                                    <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="draft" {{ $document->status == 'draft' ? 'selected' : '' }}>Borrador (Draft)</option>
                                        <option value="pending" {{ $document->status == 'pending' ? 'selected' : '' }}>Pendiente de Revisión</option>
                                        @if(auth()->user()->isReviewer() || auth()->user()->isAdmin())
                                            <option value="approved" {{ $document->status == 'approved' ? 'selected' : '' }}>Aprobado</option>
                                            <option value="rejected" {{ $document->status == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                        @endif
                                    </select>
                                </div>
                                
                                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                    Actualizar Estado
                                </button>
                                @if(!auth()->user()->isReviewer() && !auth()->user()->isAdmin())
                                    <p class="text-xs text-gray-500 mt-2 text-center">Solo administradores o revisores pueden Aprobar/Rechazar.</p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha (Versiones) -->
                <div class="md:col-span-2 space-y-8">
                    
                    <!-- Subir Nueva Versión -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6 bg-indigo-50/50 border-b border-gray-100">
                            <h3 class="text-lg font-extrabold text-indigo-900">Subir Nueva Versión</h3>
                            <p class="text-sm text-indigo-700">El documento original no se borra. Se creará una versión v{{ number_format(($document->versions->last()->version ?? 1.0) + 1.0, 1) }}.</p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('documents.versions.store', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="change_summary" class="block text-sm font-bold text-gray-700 mb-2">Resumen de Cambios <span class="text-red-500">*</span></label>
                                        <input type="text" name="change_summary" required placeholder="Ej. Corrección de cláusula 3"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div>
                                        <label for="file" class="block text-sm font-bold text-gray-700 mb-2">Archivo Nuevo <span class="text-red-500">*</span></label>
                                        <input type="file" name="file" required class="w-full border border-gray-300 p-1.5 rounded-lg text-sm">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                        Subir Versión
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Versiones -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-lg font-extrabold text-gray-900 border-b pb-3 mb-4">Historial de Versiones</h3>
                            
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach ($document->versions->sortByDesc('version') as $index => $version)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ring-8 ring-white">
                                                            <span class="text-xs font-bold text-indigo-700">v{{ $version->version }}</span>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                Subido por <span class="font-medium text-gray-900">{{ $version->uploader->name }}</span>
                                                            </p>
                                                            <p class="text-sm text-gray-800 font-medium mt-1">"{{ $version->change_summary }}"</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap flex flex-col items-end gap-2">
                                                            <time datetime="{{ $version->created_at }}">{{ $version->created_at->format('d/m/Y H:i') }}</time>
                                                            
                                                            <div class="flex gap-2">
                                                                <button onclick="openPreview('{{ route('documents.preview', $version) }}', '{{ $version->original_name }}')" type="button" class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-bold rounded shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                                                    Ver (Preview)
                                                                </button>
                                                                <a href="{{ route('documents.download', $version) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                                                    Descargar
                                                                </a>
                                                            </div>
                                                            @if($version->file_hash)
                                                                <p class="text-[10px] text-gray-400 font-mono mt-1 w-48 truncate" title="{{ $version->file_hash }}">SHA-256: {{ substr($version->file_hash, 0, 16) }}...</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Auditoría (Logs) -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between border-b pb-3 mb-4">
                                <h3 class="text-lg font-extrabold text-gray-900">Registro de Auditoría (Logs)</h3>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">Top-Tier</span>
                            </div>
                            
                            <div class="flow-root">
                                <ul class="-mb-8 max-h-96 overflow-y-auto pr-2">
                                    @foreach ($document->activityLogs as $log)
                                        <li>
                                            <div class="relative pb-5">
                                                @if (!$loop->last)
                                                    <span class="absolute top-4 left-3 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <span class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white mt-0.5">
                                                            <svg class="h-3 w-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-0">
                                                        <div class="text-xs text-gray-500">
                                                            <span class="font-medium text-gray-900">{{ $log->user ? $log->user->name : 'Sistema' }}</span>
                                                            <span class="text-gray-400"> ({{ $log->ip_address }}) </span>
                                                            <span class="whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                                        </div>
                                                        <p class="text-sm text-gray-800 mt-0.5">{{ $log->description }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Previsualización -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closePreview()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-gray-200">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="previewTitle">Previsualización de Documento</h3>
                    <button type="button" onclick="closePreview()" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="bg-white p-0 h-[70vh]">
                    <embed id="previewEmbed" src="" type="application/pdf" width="100%" height="100%">
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPreview(url, title) {
            document.getElementById('previewTitle').innerText = title;
            document.getElementById('previewEmbed').src = url;
            document.getElementById('previewModal').classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewEmbed').src = '';
        }
    </script>
</x-app-layout>
