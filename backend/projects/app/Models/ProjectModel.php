<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table            = 'projects';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'description', 'owner_id'];

    protected $useTimestamps    = true; // created_at, updated_at
    protected $useSoftDeletes   = true; // deleted_at

    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
