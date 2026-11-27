<?php

namespace App\Models;

use CodeIgniter\Model;

class listModel extends Model
{
    protected $table = 'users';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'email', 'username', 'fullname', 'user_image', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'deleted_at',
    ];


    public function search($keyword)
    {

        $builder = $this->table('users');
        $builder->like('username', $keyword);
        $builder->orLike('fullname', $keyword);
        $builder->orLike('email', $keyword);

        return $builder;
    }

    public function countAllHasil(): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `hasil`");
        return (int) $query->getRow()->total;
    }

    public function countHasil($id): int
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `hasil` WHERE id_user = ?', [(int) $id]);
        return (int) $query->getRow()->total;
    }

    public function countHasilPerPenyakit(?int $idUser = null): array
    {
        $sql = 'SELECT penyakit, COUNT(*) AS total FROM hasil';
        $binds = [];
        if ($idUser !== null) {
            $sql .= ' WHERE id_user = ?';
            $binds[] = (int) $idUser;
        }
        $sql .= ' GROUP BY penyakit ORDER BY total DESC';

        return $this->db->query($sql, $binds)->getResultArray();
    }

    public function checkUser($id)
    {
        $query = $this->db->query(
            'SELECT users.id as userid, username, email, fullname, auth_groups.name AS name FROM users JOIN auth_groups_users ON auth_groups_users.user_id = users.id JOIN auth_groups ON auth_groups.id = auth_groups_users.group_id where auth_groups_users.user_id = ?',
            [(int) $id]
        );
        $result = $query->getRow();
        return $result;
    }
}
