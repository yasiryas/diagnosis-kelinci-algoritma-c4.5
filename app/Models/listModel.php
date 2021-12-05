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

    public function countAllHasil()
    {
        $query = $this->db->query("SELECT * FROM `hasil`");
        $sql = $query->getNumRows();
        return $sql;
    }

    public function countHasil($id)
    {
        $query = $this->db->query("SELECT * FROM `hasil` WHERE id_user = $id");
        $sql = $query->getNumRows();
        return $sql;
    }

    public function checkUser($id)
    {
        $query = $this->db->query("SELECT users.id as userid, username, email, name FROM users JOIN auth_groups_users ON auth_groups_users.user_id = users.id JOIN auth_groups ON auth_groups.id = auth_groups_users.group_id where auth_groups_users.user_id = $id");
        $result = $query->getRow();
        return $result;
    }
}
