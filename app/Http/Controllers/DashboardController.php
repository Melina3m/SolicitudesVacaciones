<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $metrics = [
            'total_empleados' => 0,
            'pendientes' => 0,
            'aprobadas' => 0,
            'rechazadas' => 0,
            'total_solicitudes' => 0,
        ];

        if ($user->role === 'admin') {
            $metrics['total_empleados'] = User::count();
            $metrics['pendientes'] = VacationRequest::where('status', 'pendiente')->count();
            $metrics['aprobadas'] = VacationRequest::where('status', 'aprobada')->count();
            $metrics['rechazadas'] = VacationRequest::where('status', 'rechazada')->count();
            $metrics['total_solicitudes'] = VacationRequest::count();

            $ultimas = VacationRequest::with('user')->latest()->take(5)->get();

        } elseif ($user->role === 'supervisor') {
            $empleadosIds = User::where('supervisor_id', $user->id)->pluck('id')->toArray();
            $empleadosIds[] = $user->id; // Incluir al propio supervisor

            $metrics['total_empleados'] = count($empleadosIds) - 1; // Sin contarse a si mismo en la metrica
            $metrics['pendientes'] = VacationRequest::whereIn('user_id', $empleadosIds)->where('status', 'pendiente')->count();
            $metrics['aprobadas'] = VacationRequest::whereIn('user_id', $empleadosIds)->where('status', 'aprobada')->count();
            $metrics['rechazadas'] = VacationRequest::whereIn('user_id', $empleadosIds)->where('status', 'rechazada')->count();
            $metrics['total_solicitudes'] = VacationRequest::whereIn('user_id', $empleadosIds)->count();

            $ultimas = VacationRequest::with('user')->whereIn('user_id', $empleadosIds)->latest()->take(5)->get();

        } else {
            // Empleado
            $metrics['total_empleados'] = 0; // No aplica
            $metrics['pendientes'] = VacationRequest::where('user_id', $user->id)->where('status', 'pendiente')->count();
            $metrics['aprobadas'] = VacationRequest::where('user_id', $user->id)->where('status', 'aprobada')->count();
            $metrics['rechazadas'] = VacationRequest::where('user_id', $user->id)->where('status', 'rechazada')->count();
            $metrics['total_solicitudes'] = VacationRequest::where('user_id', $user->id)->count();

            $ultimas = VacationRequest::where('user_id', $user->id)->latest()->take(5)->get();
        }

        return view('dashboard', compact('metrics', 'ultimas'));
    }
}
