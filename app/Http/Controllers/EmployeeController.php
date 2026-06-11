<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::orderBy('id', 'desc')->get());
    }

    public function stats()
    {
        $total = Employee::count();
        $active = Employee::where('status', 'active')->count();
        $inactive = Employee::where('status', 'inactive')->count();
        $public = Employee::where('visibility', 'public')->count();
        $hidden = Employee::where('visibility', 'hidden')->count();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'public' => $public,
            'hidden' => $hidden
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:employees',
            'nip' => 'required|string|unique:employees',
            'position' => 'required|string',
            'department' => 'required|string',
            'status' => 'required|in:active,inactive',
            'visibility' => 'required|in:public,hidden',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'hire_date' => 'nullable|date',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $employee = Employee::create($data);
        return response()->json($employee, 201);
    }

    public function show(Employee $employee)
    {
        return response()->json($employee);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:employees,email,'.$employee->id,
            'nip' => 'sometimes|required|string|unique:employees,nip,'.$employee->id,
            'position' => 'sometimes|required|string',
            'department' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:active,inactive',
            'visibility' => 'sometimes|required|in:public,hidden',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'hire_date' => 'nullable|date',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $employee->update($data);
        return response()->json($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(null, 204);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:employees,id'
        ]);

        Employee::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Employees deleted successfully.']);
    }
}
