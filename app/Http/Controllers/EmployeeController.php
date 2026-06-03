<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Buscar por nombre o correo
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtrar por cargo
        if ($request->filled('position')) {
            $query->where('position', 'like', "%{$request->position}%");
        }

        // Filtrar por supervisor
        if ($request->filled('supervisor_id')) {
            $query->where('supervisor_id', $request->supervisor_id);
        }

        $employees = $query->with('supervisor')->latest()->paginate(10);
        
        // Obtener supervisores para el filtro
        $supervisors = User::whereIn('role', ['admin', 'supervisor'])->get();

        return view('employees.index', compact('employees', 'supervisors'));
    }

    public function create()
    {
        $supervisors = User::whereIn('role', ['admin', 'supervisor'])->get();
        return view('employees.create', compact('supervisors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'supervisor', 'empleado'])],
            'position' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'supervisor_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'position' => $request->position,
            'entry_date' => $request->entry_date,
            'supervisor_id' => $request->supervisor_id,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.index')->with('success', 'Empleado creado exitosamente.');
    }

    public function edit(User $employee)
    {
        $supervisors = User::whereIn('role', ['admin', 'supervisor'])->where('id', '!=', $employee->id)->get();
        return view('employees.edit', compact('employee', 'supervisors'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'role' => ['required', Rule::in(['admin', 'supervisor', 'empleado'])],
            'position' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'supervisor_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        $data = $request->except(['password']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(User $employee)
    {
        // Check if employee has pending vacation requests
        if ($employee->vacationRequests()->where('status', 'pendiente')->exists()) {
            return redirect()->route('employees.index')->with('error', 'No se puede eliminar al empleado porque tiene solicitudes de vacaciones pendientes.');
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
