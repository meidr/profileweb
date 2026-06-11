<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json(Department::orderBy('name', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name'
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201);
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id
        ]);

        $oldName = $department->name;
        $newName = $validated['name'];

        $department->update($validated);

        // Sync logic: update employee records
        if ($oldName !== $newName) {
            Employee::where('department', $oldName)->update(['department' => $newName]);
        }

        return response()->json($department);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        // The implementation plan says we leave existing employees as-is if deleted
        return response()->json(null, 204);
    }
}
