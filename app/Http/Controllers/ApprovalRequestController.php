<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;

class ApprovalRequestController extends Controller
{
    public function index()
    {
        $requests = ApprovalRequest::with('employee')->orderBy('created_at', 'desc')->get();
        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'unique_code' => 'required|string|size:5',
            'type' => 'required|string',
            'subtype' => 'required|string',
            'description' => 'nullable|string',
            'current_data' => 'nullable|array',
            'requested_data' => 'nullable|array',
        ]);

        $employee = \App\Models\Employee::findOrFail($request->employee_id);
        if ($employee->unique_code !== $request->unique_code) {
            return response()->json(['message' => 'Invalid Security Code. Please make sure you entered the correct 5-digit code.'], 403);
        }

        $approvalRequest = ApprovalRequest::create([
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'subtype' => $request->subtype,
            'description' => $request->description,
            'current_data' => $request->current_data,
            'requested_data' => $request->requested_data,
            'status' => 'pending'
        ]);

        return response()->json($approvalRequest, 201);
    }

    public function update(Request $request, string $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'required_if:status,rejected',
        ]);

        $approvalRequest->status = $request->status;
        if ($request->has('admin_notes')) {
            $approvalRequest->admin_notes = $request->admin_notes;
        }

        if ($request->status === 'approved') {
            // Apply requested data changes to the employee
            $employee = $approvalRequest->employee;
            if ($approvalRequest->type === 'data_change') {
                $employee->update($approvalRequest->requested_data);
            } elseif ($approvalRequest->type === 'visibility') {
                if (isset($approvalRequest->requested_data['visibility'])) {
                    $employee->visibility = $approvalRequest->requested_data['visibility'];
                } else {
                    $employee->visibility = 'hidden'; // fallback
                }
                $employee->save();
            }
        }

        $approvalRequest->save();

        return response()->json($approvalRequest);
    }
}
