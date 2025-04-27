<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Exception;

class AuthController extends BaseController
{
    public function register()
    {
        $rules = [
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'name'     => 'required|min_length[3]'
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart(); 

        try {
            $userModel = new UserModel();
            $data = [
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'name'     => $this->request->getPost('name'),
            ];

            if (! $userModel->save($data)) {
                throw new Exception('Failed to save user');
            }

            $db->transComplete(); 

            if ($db->transStatus() === false) {
                throw new Exception('Transaction failed');
            }

            return apiResponse(true, 'User registered successfully', [
                'email' => $data['email'],
                'name'  => $data['name'],
            ]);
        } catch (Exception $e) {
            $db->transRollback(); 
            return apiResponse(false, 'Registration error: ' . $e->getMessage());
        }
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation errors', $this->validator->getErrors());
        }

        try {
            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();

            if (! $user) {
                return apiResponse(false, 'Email not found');
            }

            if (! password_verify($this->request->getPost('password'), $user['password'])) {
                return apiResponse(false, 'Wrong password');
            }

            $key = getenv('JWT_SECRET');
            $payload = [
                'iat'  => time(),
                'exp'  => time() + (3600 * 24), // valid for 1 day
                'uid'  => $user['id'],
                'name' => $user['name'],
                'email'=> $user['email'],
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            return apiResponse(true, 'Login successful', [
                'token' => $token
            ]);
        } catch (Exception $e) {
            return apiResponse(false, 'Login error: ' . $e->getMessage());
        }
    }
}
