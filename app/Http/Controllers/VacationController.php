<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = VacationRequest::with('user');

        if ($user->role === 'admin') {
            // Todos pueden ser vistos por el admin
        } elseif ($user->role === 'supervisor') {
            // El supervisor puede ver sus solicitudes y las de los empleados a su cargo
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('user', function ($subQ) use ($user) {
                      $subQ->where('supervisor_id', $user->id);
                  });
            });
        } else {
            // El empleado solo puede consultar sus propias solicitudes
            $query->where('user_id', $user->id);
        }

        // Filtros (Solo Administrador y Supervisor pueden filtrar por empleado)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id') && in_array($user->role, ['admin', 'supervisor'])) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $requests = $query->latest()->get();

        // Obtener usuarios para el filtro de empleados (solo para admin y supervisor)
        $users = [];
        if ($user->role === 'admin') {
            $users = \App\Models\User::all();
        } elseif ($user->role === 'supervisor') {
            $users = \App\Models\User::where('supervisor_id', $user->id)->orWhere('id', $user->id)->get();
        }

        return view('vacations.index', compact('requests', 'users'));
    }

    public function create()
    {
        return view('vacations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'required|string|max:500',
            'optional_date' => 'nullable|date|after_or_equal:today', //agrego campo de solicitud para p prueba
        ]);

        $start = new \DateTime($request->start_date);
        $end = new \DateTime($request->end_date);

        // Se suma 1 para incluir el día inicial en el total de días solicitados
        $days = $start->diff($end)->days + 1;

        VacationRequest::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'pendiente',
        ]);

        return redirect()
            ->route('vacations.index')
            ->with('success', 'Solicitud creada correctamente.');
    }

    public function updateStatus(Request $request, VacationRequest $vacationRequest)
    {
        $request->validate([
            'status' => 'required|in:aprobada,rechazada,cancelada',
            'observations' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if ($request->status === 'cancelada') {

            // Un empleado solo puede cancelar sus propias solicitudes pendientes
            if (
                $vacationRequest->user_id !== $user->id ||
                $vacationRequest->status !== 'pendiente'
            ) {
                return back()->with('error', 'No puedes cancelar esta solicitud.');
            }

        } else {

            // Solo el administrador o el supervisor directo pueden aprobar o rechazar
            $isSupervisor = ($vacationRequest->user->supervisor_id === $user->id);

            if ($user->role !== 'admin' && !$isSupervisor) {
                return back()->with('error', 'No tienes permisos para gestionar esta solicitud.');
            }
        }

        $vacationRequest->update([
            'status' => $request->status,
            'observations' => $request->observations,
            'action_by' => $user->name,
        ]);

        return back()->with('success', 'Solicitud actualizada con éxito.');
    }
}