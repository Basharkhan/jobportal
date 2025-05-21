<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\JobRequest;
use App\Services\JobPostingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobPostingController
{
    public function __construct(protected JobPostingService $jobPostingService)
    {
        
    }

    public function store(JobRequest $jobRequest) {
        try {
            $job = $this->jobPostingService->createJob( $jobRequest->validated() );
            return response()->json( [
                'success' => true,
                'message' => 'Job created successfully',
                'data' => $job,
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch ( Exception $e ) {
            Log::error( 'Job creation error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Job creation failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function index(Request $request) {
        try {
            $employerId = auth()->user()->id;
            $perPage = $request->query('per_page', 10);
            $jobs = $this->jobPostingService->getJobsByEmployerId( $employerId, $perPage );
            return response()->json( [
                'success' => true,
                'data' => $jobs,
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Get jobs error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve jobs'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function show( int $jobId ) {
        try {
            $job = $this->jobPostingService->findJobById( $jobId );
            return response()->json( [
                'success' => true,
                'data' => $job,
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Find job error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve job'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function update( int $jobId, JobRequest $jobRequest ) {
        try {
            $job = $this->jobPostingService->updateJob( $jobId, $jobRequest->validated() );
            return response()->json( [
                'success' => true,
                'message' => 'Job updated successfully',
                'data' => $job,
            ], Response::HTTP_OK );
        } catch ( ValidationException $e ) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            return response()->json( [
                'success' => false,
                'message' => 'Job not found'
            ], Response::HTTP_NOT_FOUND );
        } catch ( Exception $e ) {
            Log::error( 'Job update error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Job update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function destroy( int $jobId ) {
        try {
            $this->jobPostingService->deleteJob( $jobId );
            return response()->json( [
                'success' => true,
                'message' => 'Job deleted successfully',
            ], Response::HTTP_OK );
        } catch (NotFoundHttpException $e) {
            return response()->json( [
                'success' => false,
                'message' => 'Job not found'
            ], Response::HTTP_NOT_FOUND );
        } catch ( Exception $e ) {
            Log::error( 'Job deletion error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Job deletion failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function changeJobStatus( int $jobId, Request $request ) {
        try {
            $status = $request->input( 'status' );
            $this->jobPostingService->changeJobStatus( $jobId, $status );
            return response()->json( [
                'success' => true,
                'message' => 'Job status updated successfully',
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Job status change error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Job status update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function search(Request $request) {        
        try {
            $employerId = auth()->user()->id;
            $filters = $request->only('title', 'status', 'category_id', 'location_id', 'job_type');
            $perPage = $request->query('per_page', 10);
            $jobs = $this->jobPostingService->searchEmployerJobs( (int)$employerId, $filters, (int) $perPage );
            return response()->json( [
                'success' => true,
                'data' => $jobs,
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            Log::error( 'Search jobs error: ' . $e->getMessage() );
            return response()->json( [
                'success' => false,
                'message' => 'Failed to search jobs'
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }
}
