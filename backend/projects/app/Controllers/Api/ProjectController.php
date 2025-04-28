<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use Exception;

class ProjectController extends BaseController
{
    public function index()
    {
        try {
            $projectModel = new ProjectModel();

            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $search = $this->request->getPost('search');
            $sort = $this->request->getPost('sort') ?? 'created_at';
            $order = $this->request->getPost('order') ?? 'desc';
            $page = (int) $this->request->getPost('page') ?? 1;
            $perPage = (int) $this->request->getPost('perPage') ?? 10;

            if ($perPage <= 0) {
                $perPage = 10;
            }
            if ($page <= 0) {
                $page = 1;
            }

            $builder = $projectModel
                ->select('projects.*, users.name as owner_name')
                ->join('users', 'users.id = projects.owner_id')
                ->where('owner_id', $userId);

            if ($search) {
                $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
            }

            $projects = $builder->orderBy($sort, $order)
                                ->paginate($perPage, 'default', $page);

            $pager = $projectModel->pager;

            return apiResponse(true, 'Projects fetched successfully', [
                'projects' => $projects,
                'pagination' => [
                    'currentPage' => $pager->getCurrentPage(),
                    'totalPages' => $pager->getPageCount(),
                    'perPage' => $perPage,
                    'total' => $pager->getTotal(),
                ]
            ]);
        } catch (Exception $e) {
            log_message('error', '[ProjectController:index] ' . $e->getMessage());
            return apiResponse(false, 'Failed to fetch projects', ['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation error', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $projectModel = new ProjectModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'owner_id' => $userId,
            ];

            $projectModel->insert($data);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to create project');
            }

            $db->transCommit();
            return apiResponse(true, 'Project created successfully', $data);

        } catch (Exception $e) {
            log_message('error', '[ProjectController:index] ' . $e->getMessage());
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to create project', ['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation error', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $projectModel = new ProjectModel();
            $project = $projectModel->where('owner_id', $userId)->find($id);

            if (! $project) {
                return apiResponse(false, 'Project not found');
            }

            $projectModel->update($id, [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to update project');
            }

            $db->transCommit();
            return apiResponse(true, 'Project updated successfully');

        } catch (Exception $e) {
            log_message('error', '[ProjectController:index] ' . $e->getMessage());
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to update project', ['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $projectModel = new ProjectModel();
            $project = $projectModel->where('owner_id', $userId)->find($id);

            if (! $project) {
                return apiResponse(false, 'Project not found');
            }

            $projectModel->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to delete project');
            }

            $db->transCommit();
            return apiResponse(true, 'Project deleted successfully');

        } catch (Exception $e) {
            log_message('error', '[ProjectController:index] ' . $e->getMessage());
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to delete project', ['error' => $e->getMessage()]);
        }
    }

    public function invite($id)
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            return apiResponse(false, 'Validation error', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $projectModel = new \App\Models\ProjectModel();
            $memberModel = new \App\Models\ProjectMemberModel();
            $userModel = new \App\Models\UserModel();

            // Check if project exists and user is owner
            $project = $projectModel->where('owner_id', $userId)->find($id);

            if (! $project) {
                return apiResponse(false, 'Project not found or you are not the owner')->setStatusCode(404);
            }

            // Check if invited user exists
            $invitedUser = $userModel->where('email', $this->request->getPost('email'))->first();

            if (! $invitedUser) {
                return apiResponse(false, 'User with this email not found')->setStatusCode(404);
            }

            // Check if user is already a member
            $existingMember = $memberModel
                ->where('project_id', $id)
                ->where('user_id', $invitedUser['id'])
                ->first();

            if ($existingMember) {
                return apiResponse(false, 'User is already a member of this project');
            }

            // Insert into project_members table
            $memberModel->insert([
                'project_id' => $id,
                'user_id'    => $invitedUser['id'],
                'role'       => 'member', // optional: you can add roles like member, admin, etc
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to invite user');
            }

            $db->transCommit();
            return apiResponse(true, 'User invited successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[INVITE PROJECT ERROR] ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to invite user', ['error' => $e->getMessage()]);
        }
    }

}
