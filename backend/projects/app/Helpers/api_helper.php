<?php

if (!function_exists('apiResponse')) {
    function apiResponse($status = true, $message = '', $data = null)
    {
        return \Config\Services::response()
            ->setJSON([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ]);
    }
}
