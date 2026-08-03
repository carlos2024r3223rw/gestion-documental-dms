<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Corporativo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Documentos -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transform transition hover:-translate-y-1 hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Documentos</h3>
                            <span class="text-3xl font-extrabold text-gray-900">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aprobados -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transform transition hover:-translate-y-1 hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Aprobados</h3>
                            <span class="text-3xl font-extrabold text-green-600">{{ $stats['approved'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transform transition hover:-translate-y-1 hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pendientes</h3>
                            <span class="text-3xl font-extrabold text-blue-600">{{ $stats['pending'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rechazados -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transform transition hover:-translate-y-1 hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Rechazados</h3>
                            <span class="text-3xl font-extrabold text-red-600">{{ $stats['rejected'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Documentos Recientes -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-extrabold text-gray-900">Actividad Reciente</h3>
                    <a href="{{ route('documents.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900">Ver Todos &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50">Documento</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($recentDocuments as $doc)
                                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold text-gray-900">
                                                <a href="{{ route('documents.show', $doc) }}" class="hover:text-indigo-600">{{ $doc->title }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm
                                            {{ $doc->status === 'approved' ? 'bg-green-100 text-green-700' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-700' : ($doc->status === 'pending' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $doc->status === 'draft' ? 'Borrador' : ucfirst($doc->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $doc->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 font-medium">No hay actividad reciente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
