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
        // Service layer data process karegi. Agar koi error aya toh Global Handler khud catch karega.
        $assignment = $this->assignmentService->checkout($request->validated());
        
        // Using your Global Response Macro for Success
        return response()->success(
            new AssignmentResource($assignment), 
            'Asset checked out successfully.', 
            201
        );
    }

    public function checkin(CheckinAssetRequest $request)
    {
        $assignment = $this->assignmentService->checkin($request->validated());
        
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