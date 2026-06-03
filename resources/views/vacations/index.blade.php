<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Vacaciones') }}
            </h2>
            <a href="{{ route('vacations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                + Nueva Solicitud
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($requests as $req)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $req->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $req->user->position ?? 'No asignado' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $req->start_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $req->end_date }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($req->status === 'pendiente')
                                                @if(Auth::id() === $req->user_id)
                                                    <form action="{{ route('vacations.updateStatus', $req) }}" method="POST" class="inline">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelada">
                                                        <button type="submit" class="text-gray-600 hover:text-gray-900 font-bold underline">Cancelar</button>
                                                    </form>
                                                @endif

                                                @if(Auth::user()->role === 'admin' || Auth::id() === $req->user->supervisor_id)
                                                    <div class="flex space-x-3 inline-block ml-2">
                                                        <form action="{{ route('vacations.updateStatus', $req) }}" method="POST" class="inline">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="aprobada">
                                                            <button type="submit" class="text-green-600 hover:text-green-900 font-bold underline">Aprobar</button>
                                                        </form>
                                                        <form action="{{ route('vacations.updateStatus', $req) }}" method="POST" class="inline">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="rechazada">
                                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold underline">Rechazar</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400 italic">Acción por: {{ $req->action_by ?? 'Sistema' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay solicitudes registradas en este momento.</td>
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