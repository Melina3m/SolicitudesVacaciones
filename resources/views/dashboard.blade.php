<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard General') }}
            </h2>
            <a href="{{ route('vacations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                + Solicitar Vacaciones
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Indicadores -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                <!-- Total Empleados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                            {{ Auth::user()->role === 'admin' ? 'Total Empleados' : 'Empleados a Cargo' }}
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $metrics['total_empleados'] }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sol. Pendientes</div>
                        <div class="mt-2 text-3xl font-bold text-yellow-600">
                            {{ $metrics['pendientes'] }}
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sol. Aprobadas</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">
                            {{ $metrics['aprobadas'] }}
                        </div>
                    </div>
                </div>

                <!-- Rechazadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sol. Rechazadas</div>
                        <div class="mt-2 text-3xl font-bold text-red-600">
                            {{ $metrics['rechazadas'] }}
                        </div>
                    </div>
                </div>
                
                <!-- Total Solicitudes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ Auth::user()->role === 'empleado' ? 'col-span-1 md:col-span-2 lg:col-span-4' : '' }}">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Histórico</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $metrics['total_solicitudes'] }}
                        </div>
                    </div>
                </div>

            </div>

            <!-- Últimas Solicitudes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Últimas Solicitudes Registradas</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if(Auth::user()->role !== 'empleado')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($ultimas as $req)
                                    <tr>
                                        @if(Auth::user()->role !== 'empleado')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $req->user->name }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $req->start_date }} al {{ $req->end_date }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ $req->days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $req->status === 'aprobada' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $req->status === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $req->status === 'rechazada' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $req->status === 'cancelada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $req->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role !== 'empleado' ? '5' : '4' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay solicitudes recientes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('vacations.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Ver todas las solicitudes &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
