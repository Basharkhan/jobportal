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

    public function store(ApplicationRequest $request) {        
        try {            
            $application = $this->applicationService->applyToJob(
                $request->validated(),
                $request->file('resume')
            );
            
            return response()->json([
                'status' => 'success',
                'data' => $application
            ], Response::HTTP_CREATED);
        } catch ( ValidationException $e ) {
            throw $e;
        } catch(AccessDeniedHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_FORBIDDEN);
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
            $applications = $this->applicationService->getApplicationsByJobSeeker($userId, $perPage);

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

    public function getApplicationByIdForAdmin(int $id) {
        try {
            $application = $this->applicationService->getApplicationByIdForAdmin($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $application
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error( 'Failed to retrieve application: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }    

    public function getApplicationsByJobForAdmin(int $jobId, Request $request) {
        try {
            $perPage = $request->query('per_page', 10);
            $applications = $this->applicationService->getApplicationsByJobForAdmin($jobId, $perPage);

            return response()->json([
                'status' => 'success',
                'data' => $applications
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Applications not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error( 'Failed to retrieve applications: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getApplicationsByJobForEmployer(int $jobId, Request $request) {
        try {
            $perPage = $request->query('per_page', 10);
            $applications = $this->applicationService->getApplicationsByJobForEmployer($jobId, $perPage);

            return response()->json([
                'status' => 'success',
                'data' => $applications
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error( 'Failed to retrieve applications: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateApplicationStatus(int $id, Request $request) {
        try {
            $status = $request->input('status');
            if (!in_array($status, ['pending', 'accepted', 'rejected'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid status'
                ], Response::HTTP_BAD_REQUEST);
            }

            $updatedApplication = $this->applicationService->updateApplicationStatus($id, $status);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Application status updated successfully',
                'data' => $updatedApplication
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error( 'Failed to update application status: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteApplication(int $id) {
        try {
            $deleted = $this->applicationService->deleteApplication($id);
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Application deleted successfully'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete application'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            Log::error( 'Failed to delete application: ' . $e->getMessage() );
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getApplicationsForAdmin(Request $request) {        
        try {
            $perPage = $request->query('per_page', 10);           
            $applications = $this->applicationService->getApplications($perPage);

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
}
