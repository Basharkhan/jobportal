<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApplicationRequest;
use App\Services\ApplicationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplicationController {
    public function __construct(protected ApplicationService $applicationService){
        
    }

    public function store(ApplicationRequest $applicationRequest) {
        try {
            $validatedData = $applicationRequest->validated();

            if($applicationRequest->hasFile(('resume'))) {
                $file = $applicationRequest->file('resume');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('resumes'), $fileName);
                $validatedData['resume'] = $fileName;
            }

            $validatedData['user_id'] = auth()->user()->id;

            $application = $this->applicationService->applyToJob($validatedData);
            
            return response()->json([
                'status' => 'success',
                'data' => $application
            ], Response::HTTP_CREATED);
        } catch ( ValidationException $e ) {
            throw $e;
        } catch (Exception $e) {
            Log::error( 'Employer login error: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(Request $request) {        
        try {
            $perPage = $request->query('per_page', 10);
            $userId = auth()->user()->id;
            $applications = $this->applicationService->getApplicationsByUser($userId, $perPage);

            return response()->json([
                'status' => 'success',
                'data' => $applications
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error( 'Employer login error: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findApplication(int $id) {
        try {
            $application = $this->applicationService->findApplication($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $application
            ], Response::HTTP_OK);
        } catch(AccessDeniedHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to access this application'
            ], Response::HTTP_FORBIDDEN);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Application not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error( 'Failed to retrieve application: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
