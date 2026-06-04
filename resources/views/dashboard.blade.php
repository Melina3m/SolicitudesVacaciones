<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.24em] text-blue-700">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Overview
                </div>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                    {{ __('Dashboard') }}
                </h2>
                <p class="mt-2 max-w-xl text-sm text-slate-600 leading-6">Consulta el estado actual de las solicitudes de vacaciones y realiza seguimiento a su gestión.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('vacations.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-2xl font-bold text-sm text-white shadow-lg shadow-indigo-200/60 hover:shadow-indigo-300 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Solicitar Vacaciones
                </a>
                <a href="{{ route('vacations.index') }}" class="inline-flex items-center px-5 py-3 rounded-2xl border border-slate-200 bg-white/80 font-bold text-sm text-slate-700 shadow-sm hover:bg-white hover:shadow-md transition-all">
                    Ver solicitudes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="rounded-[2rem] border border-white/60 bg-slate-900 text-white shadow-2xl shadow-slate-200/60 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 opacity-95"></div>
                <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -left-10 bottom-0 h-48 w-48 rounded-full bg-cyan-300/10 blur-3xl"></div>
                <div class="relative grid gap-6 p-6 md:p-8 lg:grid-cols-[1.4fr_1fr] lg:items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.24em] text-blue-100">
                            Control operativo
                        </div>
                        <h3 class="mt-4 text-2xl font-black tracking-tight sm:text-3xl">RESUMEN GENERAL</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-50/90">Estado actual de las solicitudes registradas en el sistema.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm border border-white/10">
                            <div class="text-blue-100 text-xs uppercase tracking-[0.24em] font-bold">Pendientes</div>
                            <div class="mt-2 text-3xl font-black">{{ $metrics['pendientes'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm border border-white/10">
                            <div class="text-blue-100 text-xs uppercase tracking-[0.24em] font-bold">Aprobadas</div>
                            <div class="mt-2 text-3xl font-black">{{ $metrics['aprobadas'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm border border-white/10">
                            <div class="text-blue-100 text-xs uppercase tracking-[0.24em] font-bold">Rechazadas</div>
                            <div class="mt-2 text-3xl font-black">{{ $metrics['rechazadas'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm border border-white/10">
                            <div class="text-blue-100 text-xs uppercase tracking-[0.24em] font-bold">Total</div>
                            <div class="mt-2 text-3xl font-black">{{ $metrics['total_solicitudes'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
           

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h3 class="font-bold text-lg text-slate-800">Últimas Solicitudes Registradas</h3>
                    <a href="{{ route('vacations.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50/60 hover:bg-blue-50 px-3 py-2 rounded-lg transition-colors">
                        Ver todas las solicitudes
                        <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="p-0 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if(Auth::user()->role !== 'empleado')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Días</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentRequests as $req)
                                    <tr>
                                        @if(Auth::user()->role !== 'empleado')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm uppercase">
                                                        {{ substr($req->user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $req->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $req->user->position ?? 'Sin cargo' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                            {{ $req->days }} <span class="text-xs font-medium text-gray-500">días</span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($req->status === 'aprobada')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/50">
                                                    Aprobada
                                                </span>
                                            @elseif($req->status === 'pendiente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-50 text-amber-800 border border-amber-200/50">
                                                    Pendiente
                                                </span>
                                            @elseif($req->status === 'rechazada')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-rose-50 text-rose-800 border border-rose-200/50">
                                                    Rechazada
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-50 text-slate-700 border border-slate-200/50">
                                                    Cancelada
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $req->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role !== 'empleado' ? '5' : '4' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay solicitudes registradas recientemente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>