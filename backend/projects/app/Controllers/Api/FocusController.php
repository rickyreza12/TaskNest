<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use Exception;

class FocusController extends BaseController
{
    public function index()
    {
        try {
            $taskModel = new TaskModel();

            $userId = $this->request->user['uid'] ?? null;
            if (!$userId) {
                return apiResponse(false, 'Unauthorized')->setStatusCode(401);
            }

            $search  = $this->request->getGet('search');
            $sort    = $this->request->getGet('sort') ?? 'focus_start_at';
            $order   = $this->request->getGet('order') ?? 'desc';
            $page    = (int) ($this->request->getGet('page') ?? 1);
            $perPage = (int) ($this->request->getGet('perPage') ?? 10);

            $builder = $taskModel->where('assigned_user_id', $userId)->where('is_focusing', 1);

            if ($search) {
                $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
            }

            $tasks = $builder->orderBy($sort, $order)
                             ->paginate($perPage, 'default', $page);

            $pager = $taskModel->pager;

            return apiResponse(true, 'Focused tasks fetched successfully', [
                'tasks' => $tasks,
                'pagination' => [
                    'currentPage' => $pager->getCurrentPage(),
                    'totalPages'  => $pager->getPageCount(),
                    'perPage'     => $perPage,
                    'total'       => $pager->getTotal(),
                ]
            ]);
        } catch (Exception $e) {
            log_message('error', '[FOCUS_INDEX_ERROR] ' . $e->getMessage());
            return apiResponse(false, 'Failed to fetch focus tasks', ['error' => $e->getMessage()]);
        }
    }

    public function start($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $taskModel = new TaskModel();
            $task = $taskModel->find($id);

            if (! $task) {
                return apiResponse(false, 'Task not found')->setStatusCode(404);
            }

            $taskModel->update($id, [
                'is_focusing'    => 1,
                'focus_start_at' => date('Y-m-d H:i:s'),
                'focus_end_at'   => null,
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to start focus mode');
            }

            $db->transCommit();
            return apiResponse(true, 'Focus session started');

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', '[FOCUS_START_ERROR] ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to start focus', ['error' => $e->getMessage()]);
        }
    }

    public function end($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $taskModel = new TaskModel();
            $task = $taskModel->find($id);

            if (! $task) {
                return apiResponse(false, 'Task not found')->setStatusCode(404);
            }

            $taskModel->update($id, [
                'is_focusing'   => 0,
                'focus_end_at'  => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return apiResponse(false, 'Failed to end focus mode');
            }

            $db->transCommit();
            return apiResponse(true, 'Focus session ended');

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', '[FOCUS_END_ERROR] ' . $e->getMessage());
            return apiResponse(false, 'Exception: Failed to end focus', ['error' => $e->getMessage()]);
        }
    }
}
