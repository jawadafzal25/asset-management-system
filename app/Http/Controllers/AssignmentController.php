<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutAssetRequest;
use App\Http\Requests\CheckinAssetRequest;
use App\Http\Resources\AssignmentResource;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function checkout(CheckoutAssetRequest $request)
    {
        // 1. Request se validated array nikala
        $validatedData = $request->validated();
        
        // 2. Sir ki requirement ke mutabiq data_get() use karke securely data pass kiya
        $assignment = $this->assignmentService->checkout([
            'asset_id'    => data_get($validatedData, 'asset_id'),
            'employee_id' => data_get($validatedData, 'employee_id'),
            'quantity'    => data_get($validatedData, 'quantity', 1),
            'description' => data_get($validatedData, 'description'),
        ]);
        
        // Using your Global Response Macro for Success
        return response()->success(
            new AssignmentResource($assignment), 
            'Asset checked out successfully.', 
            201
        );
    }

    public function checkin(CheckinAssetRequest $request)
    {
        $validatedData = $request->validated();

        // data_get() ka use
        $assignment = $this->assignmentService->checkin([
            'asset_id' => data_get($validatedData, 'asset_id'),
        ]);
        
        return response()->success(
            new AssignmentResource($assignment), 
            'Asset checked in successfully.', 
            200
        );
    }

    // $assetId = null kar diya taake specific aur all dono routes kaam karein
    public function history($assetId = null) 
    {
        $history = $this->assignmentService->getHistory($assetId);
        
        return response()->success(
            AssignmentResource::collection($history), 
            'Asset history fetched successfully.', 
            200
        );
    }
}