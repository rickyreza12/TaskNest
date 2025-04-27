<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProjectMemberModel;
use Exception;

class ProjectMemberController extends BaseController
{
    public function index($projectId)
    {
        try {
            $memberModel = new ProjectMemberModel();
            $members = $memberModel->where('project_id', $projectId)->findAll();
            return apiResponse(true, 'Members fetched successfully', $members);
        } catch (Exception $e) {
            return apiResponse(false, 'Failed to fetch members', ['error' => $e->getMessage()]);
        }
    }

    public function add($projectId)
    {
        $rules = [
            'user_id' => 'required|integer'
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation error', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $memberModel = new ProjectMemberModel();
            $memberModel->insert([
                'project_id' => $projectId,
                'user_id'    => $this->request->getPost('user_id'),
                'role'       => $this->request->getPost('role') ?? 'member'
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to add member');
            }

            $db->transCommit();
            return apiResponse(true, 'Member added successfully');

        } catch (Exception $e) {
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to add member', ['error' => $e->getMessage()]);
        }
    }

    public function remove($projectId, $userId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $memberModel = new ProjectMemberModel();
            $memberModel->where('project_id', $projectId)
                        ->where('user_id', $userId)
                        ->delete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to remove member');
            }

            $db->transCommit();
            return apiResponse(true, 'Member removed successfully');

        } catch (Exception $e) {
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to remove member', ['error' => $e->getMessage()]);
        }
    }
}
