<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-tight">
                    {{ __('Gestión de Vacaciones') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-1">Historial, filtros y aprobación de solicitudes</p>
            </div>
            
            <a href="{{ route('vacations.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:shadow-indigo-200 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Nueva Solicitud
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Notificaciones Flash -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtros (Solo para Admin y Supervisor) -->
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-sm text-slate-700 uppercase tracking-wider">Panel de Filtros</h3>
                </div>
                <div class="p-6 bg-white">
                    <form method="GET" action="{{ route('vacations.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-500 uppercase">Estado</label>
                            <select name="status" id="status" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aprobada" {{ request('status') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="rechazada" {{ request('status') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        <div>
                            <label for="user_id" class="block text-xs font-bold text-slate-500 uppercase">Empleado</label>
                            <select name="user_id" id="user_id" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                                <option value="">Todos</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-slate-500 uppercase">Desde</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-slate-500 uppercase">Hasta</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                        </div>
                        <div class="lg:col-span-4 flex justify-end space-x-2 mt-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('vacations.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors">
                                Limpiar
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                                Filtrar Resultados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Tabla de Datos Principal -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                                <th class="p-4 pl-6">Empleado</th>
                                <th class="p-4">Cargo</th>
                                <th class="p-4">Inicio</th>
                                <th class="p-4">Fin</th>
                                <th class="p-4 text-center">Días</th>
                                <th class="p-4">Estado</th>
                                <th class="p-4 pr-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            @forelse($requests as $req)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="p-4 pl-6 font-bold text-slate-800">{{ $req->user->name }}</td>
                                    <td class="p-4 text-slate-400 font-medium">
                                        {{ $req->user->position ?? 'No asignado' }}
                                    </td>
                                    <td class="p-4 font-medium text-slate-700">{{ $req->start_date }}</td>
                                    <td class="p-4 font-medium text-slate-700">{{ $req->end_date }}</td>
                                    <td class="p-4 text-center font-extrabold text-slate-800">{{ $req->days }}</td>
                                    <td class="p-4">
                                        @if($req->status === 'pendiente')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                                Pendiente
                                            </span>
                                        @elseif($req->status === 'aprobada')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aprobada
                                            </span>
                                        @elseif($req->status === 'rechazada')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Rechazada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Cancelada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-6 text-right whitespace-nowrap">
                                        @if($req->status === 'pendiente')
                                            <!-- Acción de cancelación para el propio empleado -->
                                            @if(Auth::id() === $req->user_id)
                                                <form action="{{ route('vacations.updateStatus', $req) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelada">
                                                    <button type="submit" class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors border border-slate-200/40">Cancelar</button>
                                                </form>
                                            @endif

                                            <!-- Acciones de Aprobación/Rechazo para Administradores -->
                                            @if(Auth::user()->role === 'admin' || Auth::id() === $req->user->supervisor_id)
                                                <div class="inline-flex space-x-1.5 ml-2" x-data="{ openAprobar: false, openRechazar: false }">
                                                    
                                                    <!-- Botón Aprobar -->
                                                    <button type="button" @click="openAprobar = true" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 px-3 py-1.5 rounded-lg transition-colors border border-emerald-200/30">Aprobar</button>
                                                    
                                                    <!-- Modal Aprobar -->
                                                    <div x-show="openAprobar" style="display: none;" class="fixed z-50 inset-0 overflow-y-auto text-left" role="dialog" aria-modal="true">
                                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="openAprobar" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="openAprobar = false"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                                            <div x-show="openAprobar" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                                                                <form action="{{ route('vacations.updateStatus', $req) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <input type="hidden" name="status" value="aprobada">
                                                                    <div class="bg-white px-6 pt-6 pb-4">
                                                                        <h3 class="text-lg font-bold text-slate-800">Aprobar Solicitud</h3>
                                                                        <p class="text-xs text-slate-400 mt-0.5">La solicitud de {{ $req->user->name }} cambiará a estado aprobado.</p>
                                                                        <div class="mt-4">
                                                                            <label for="observations" class="block text-xs font-bold text-slate-500 uppercase">Observaciones (Opcional)</label>
                                                                            <textarea name="observations" rows="3" class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-slate-50/50 px-6 py-4 flex flex-row-reverse gap-2">
                                                                        <button type="submit" class="inline-flex justify-center rounded-xl px-4 py-2 bg-emerald-600 text-sm font-bold text-white hover:bg-emerald-700 shadow-sm transition-colors">Confirmar Aprobación</button>
                                                                        <button type="button" @click="openAprobar = false" class="inline-flex justify-center rounded-xl px-4 py-2 bg-white border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Regresar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Botón Rechazar -->
                                                    <button type="button" @click="openRechazar = true" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 px-3 py-1.5 rounded-lg transition-colors border border-rose-200/30">Rechazar</button>

                                                    <!-- Modal Rechazar -->
                                                    <div x-show="openRechazar" style="display: none;" class="fixed z-50 inset-0 overflow-y-auto text-left" role="dialog" aria-modal="true">
                                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="openRechazar" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="openRechazar = false"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                                            <div x-show="openRechazar" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                                                                <form action="{{ route('vacations.updateStatus', $req) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <input type="hidden" name="status" value="rechazada">
                                                                    <div class="bg-white px-6 pt-6 pb-4">
                                                                        <h3 class="text-lg font-bold text-slate-800">Rechazar Solicitud</h3>
                                                                        <p class="text-xs text-slate-400 mt-0.5">Explica detalladamente la razón del rechazo al empleado.</p>
                                                                        <div class="mt-4">
                                                                            <label for="observations" class="block text-xs font-bold text-slate-500 uppercase">Observaciones (Recomendado)</label>
                                                                            <textarea name="observations" rows="3" required class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-slate-50/50 px-6 py-4 flex flex-row-reverse gap-2">
                                                                        <button type="submit" class="inline-flex justify-center rounded-xl px-4 py-2 bg-rose-600 text-sm font-bold text-white hover:bg-rose-700 shadow-sm transition-colors">Confirmar Rechazo</button>
                                                                        <button type="button" @click="openRechazar = false" class="inline-flex justify-center rounded-xl px-4 py-2 bg-white border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Regresar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        @else
                                            <!-- Estados Finalizados -->
                                            <div class="text-left inline-block">
                                                <div class="text-[11px] text-slate-400 font-medium italic">Acción por: {{ $req->action_by ?? 'Sistema' }}</div>
                                                @if($req->observations)
                                                    <div class="text-[11px] text-slate-500 max-w-[180px] truncate" title="{{ $req->observations }}">
                                                        <b class="text-slate-600">Obs:</b> {{ Str::limit($req->observations, 20) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-12 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            <span>No se encontraron solicitudes con los filtros aplicados.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>