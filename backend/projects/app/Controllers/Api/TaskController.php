<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use Exception;

class TaskController extends BaseController
{
    public function index()
    {
        try {
            $taskModel = new TaskModel();

            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $search = $this->request->getGet('search');
            $sort = $this->request->getGet('sort') ?? 'created_at';
            $order = $this->request->getGet('order') ?? 'desc';
            $page = (int) $this->request->getGet('page') ?: 1;
            $perPage = (int) $this->request->getGet('perPage') ?: 10;
            $projectId = $this->request->getGet('projectId');
            $status = $this->request->getGet('status');
            $assignedUserId = $this->request->getGet('assignedUserId');

            $builder = $taskModel->where('created_by', $userId);

            if ($projectId) {
                $builder->where('project_id', $projectId);
            }
            if ($status) {
                $builder->where('status', $status);
            }
            if ($assignedUserId) {
                $builder->where('assigned_user_id', $assignedUserId);
            }
            if ($search) {
                $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
            }

            $tasks = $builder->orderBy($sort, $order)
                             ->paginate($perPage, 'default', $page);

            $pager = $taskModel->pager;

            return apiResponse(true, 'Tasks fetched successfully', [
                'tasks' => $tasks,
                'pagination' => [
                    'currentPage' => $pager->getCurrentPage(),
                    'totalPages' => $pager->getPageCount(),
                    'perPage' => $perPage,
                    'total' => $pager->getTotal(),
                ]
            ]);
        } catch (Exception $e) {
            log_message('error', 'Task Index Error: ' . $e->getMessage());
            return apiResponse(false, 'Failed to fetch tasks', ['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'project_id' => 'required|integer',
            'assigned_user_id' => 'permit_empty|integer',
            'status' => 'permit_empty|in_list[pending,ongoing,completed]',
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

            $taskModel = new TaskModel();
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'project_id' => $this->request->getPost('project_id'),
                'assigned_user_id' => $this->request->getPost('assigned_user_id'),
                'status' => $this->request->getPost('status') ?? 'pending',
                'created_by' => $userId,
            ];

            $taskModel->insert($data);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to create task');
            }

            $db->transCommit();
            return apiResponse(true, 'Task created successfully', $data);

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Task Create Error: ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to create task', ['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]',
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

            $taskModel = new TaskModel();
            $task = $taskModel->where('created_by', $userId)->find($id);

            if (! $task) {
                return apiResponse(false, 'Task not found');
            }

            $taskModel->update($id, [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status') ?? $task['status'],
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to update task');
            }

            $db->transCommit();
            return apiResponse(true, 'Task updated successfully');

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Task Update Error: ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to update task', ['error' => $e->getMessage()]);
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

            $taskModel = new TaskModel();
            $task = $taskModel->where('created_by', $userId)->find($id);

            if (! $task) {
                return apiResponse(false, 'Task not found');
            }

            $taskModel->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to delete task');
            }

            $db->transCommit();
            return apiResponse(true, 'Task deleted successfully');

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Task Delete Error: ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to delete task', ['error' => $e->getMessage()]);
        }
    }
}
