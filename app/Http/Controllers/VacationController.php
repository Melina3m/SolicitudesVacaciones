<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $requests = VacationRequest::with('user')->latest()->get();

        // El supervisor puede ver sus solicitudes y las de los empleados a su cargo
        } elseif ($user->role === 'supervisor') {

            $requests = VacationRequest::with('user')
                ->where('user_id', $user->id)
                ->orWhereHas('user', function ($query) use ($user) {
                    $query->where('supervisor_id', $user->id);
                })
                ->latest()
                ->get();

        } else {

            // El empleado solo puede consultar sus propias solicitudes
            $requests = VacationRequest::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('vacations.index', compact('requests'));
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