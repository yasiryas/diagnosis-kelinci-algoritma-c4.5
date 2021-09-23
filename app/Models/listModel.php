<?php

namespace App\Models;

use CodeIgniter\Model;

class listModel extends Model
{
    protected $table = 'users';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'email', 'username', 'fullname', 'user_image', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_meszge', 'active', 'force_pass_reset', 'permissions', 'deleted_at',
    ];


    public function search($keyword)
    {

        $builder = $this->table('user');
        $builder->like('username', $keyword);
        $builder->orLike('name', $keyword);
        $builder->orLike('email', $keyword);

        return $builder;
    }
}
