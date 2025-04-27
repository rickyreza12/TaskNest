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

            $search = $this->request->getGet('search');
            $sort = $this->request->getGet('sort') ?? 'created_at';
            $order = $this->request->getGet('order') ?? 'desc';
            $page = (int) $this->request->getGet('page') ?? 1;
            $perPage = (int) $this->request->getGet('perPage') ?? 10;

            $builder = $projectModel->where('owner_id', $userId);

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
            $db->transRollback();
            return apiResponse(false, 'Exception: Failed to delete project', ['error' => $e->getMessage()]);
        }
    }
}
