<?php

namespace App\Http\Controllers\Api;


use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController {
    public function __construct( protected UserService $userService ) {

    }

    public function getAllAdmins(Request $request) {        
        try {
            $perPage = $request->input('per_page', 10);
            $admins = $this->userService->getAllAdmins($perPage);

            return response()->json([
                'success' => true,
                'data' => $admins
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error fetching admins: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch admins'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllEmployers(Request $request) {
        try {
            $perPage = $request->input('per_page', 10);
            $employers = $this->userService->getAllEmployers($perPage);

            return response()->json([
                'success' => true,
                'data' => $employers
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error fetching employers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employers'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllJobSeekers(Request $request) {
        try {
            $perPage = $request->input('per_page', 10);
            $users = $this->userService->getAllJobSeekers($perPage);

            return response()->json([
                'success' => true,
                'data' => $users
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }   

    public function getAllUsers(Request $request) {
        try {
            $perPage = $request->input('per_page', 10);
            $users = $this->userService->getAllUsers($perPage);

            return response()->json([
                'success' => true,
                'data' => $users
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error fetching all users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch all users'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }    

    public function getUserById(int $id) {
        try {
            $user = $this->userService->getUserById($id);
                        
            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch(ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEmployerById(int $id) {
        try {
            $user = $this->userService->getEmployerById($id);
            
            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch(ValidationException $e) {
            throw $e; 
        } catch (Exception $e) {
            Log::error('Error fetching employer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employer'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getJobSeekerById(int $id) {
        try {
            $user = $this->userService->getJobSeekerById($id);
            
            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch(ValidationException $e) {
            throw $e; 
        } catch (Exception $e) {
            Log::error('Error fetching job seeker: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch job seeker'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserByEmail(Request $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email'
            ]);
            $email = $validatedData['email'];

            $user = $this->userService->getUserByEmail($email);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(ValidationException $e) {
            throw $e; 
        } catch (Exception $e) {
            Log::error('Error fetching user by email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user by email'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser(int $id) {
        try {
            $this->userService->deleteUser($id);           

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], Response::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeJobSeekerStatus(int $id, Request $request) {
        try {
            $validatedData = $request->validate([
                'status' => 'required|integer|in:0,1'
            ]);
            $status = $validatedData['status'];

            $user = $this->userService->changeJobSeekerStatus($id, $status);                

            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(ValidationException $e) {
            throw $e; 
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('Error changing job seeker status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to change job seeker status'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeEmployerStatus(int $id, Request $request) {
        try {
            $validatedData = $request->validate([
                'status' => 'required|integer|in:0,1'
            ]);
            $status = $validatedData['status'];

            $user = $this->userService->changeEmployerStatus($id, $status);                

            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        } catch(ValidationException $e) {
            throw $e; 
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('Error changing employer status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to change employer status'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
