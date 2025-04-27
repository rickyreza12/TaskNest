<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\RabbitMQService;
use Exception;

class NotificationController extends BaseController
{
    public function trigger()
    {
        $rules = [
            'title' => 'required',
            'body'  => 'required',
            'targetUserId' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation error', $this->validator->getErrors());
        }

        try {
            $rabbit = new RabbitMQService();

            $data = [
                'title' => $this->request->getPost('title'),
                'body'  => $this->request->getPost('body'),
                'targetUserId' => $this->request->getPost('targetUserId'),
            ];

            $rabbit->publish('tasknest_notifications', $data);
            $rabbit->close();

            return apiResponse(true, 'Notification queued successfully', $data);

        } catch (Exception $e) {
            log_message('error', 'Notification Trigger Error: ' . $e->getMessage());
            return apiResponse(false, 'Failed to queue notification', ['error' => $e->getMessage()]);
        }
    }
}
