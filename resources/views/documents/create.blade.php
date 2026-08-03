<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('documents.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Subir Nuevo Documento
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-10">
                    
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Título del Documento <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out px-4 py-3 text-gray-900" 
                                    placeholder="Ej. Contrato de Arrendamiento 2026">
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Descripción / Metadatos</label>
                                <textarea id="description" name="description" rows="4" 
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out px-4 py-3 text-gray-900" 
                                    placeholder="Escribe un resumen o palabras clave para indexación...">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-bold text-gray-700 mb-2">Archivo Físico <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-400">(Max 10MB)</span></label>
                                <div id="drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-colors duration-200">
                                    <div class="space-y-2 text-center" id="upload-prompt">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none p-1">
                                                <span>Haz clic para seleccionar un archivo</span>
                                                <input id="file" name="file" type="file" class="sr-only" required onchange="updateFileName(this)">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOCX, XLSX, PNG, JPG</p>
                                    </div>
                                    
                                    <!-- File selected state (hidden by default) -->
                                    <div class="space-y-2 text-center hidden" id="file-selected">
                                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm font-bold text-gray-900" id="file-name-display">nombre-del-archivo.pdf</div>
                                        <label for="file" class="cursor-pointer text-xs text-indigo-600 hover:text-indigo-800 font-medium block mt-1">Cambiar archivo</label>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            </div>
                        </div>

                        <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-4">
                            <a href="{{ route('documents.index') }}" class="px-6 py-3 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition hover:-translate-y-1 duration-300">
                                Subir Documento
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const promptDiv = document.getElementById('upload-prompt');
            const selectedDiv = document.getElementById('file-selected');
            const nameDisplay = document.getElementById('file-name-display');
            const dropZone = document.getElementById('drop-zone');

            if (input.files && input.files[0]) {
                nameDisplay.textContent = input.files[0].name;
                promptDiv.classList.add('hidden');
                selectedDiv.classList.remove('hidden');
                dropZone.classList.add('bg-green-50', 'border-green-300');
                dropZone.classList.remove('hover:bg-indigo-50', 'hover:border-indigo-500');
            }
        }
    </script>
</x-app-layout>
