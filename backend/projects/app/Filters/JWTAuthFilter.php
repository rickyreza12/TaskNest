<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('api');

        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return apiResponse(false, 'Missing Authorization header')->setStatusCode(401);
        }

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return apiResponse(false, 'Invalid Authorization format')->setStatusCode(401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
            $request->user = (array)$decoded;
        } catch (\Exception $e) {
            return apiResponse(false, 'Invalid Token: ' . $e->getMessage(), null)->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
