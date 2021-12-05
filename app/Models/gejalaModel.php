<?php

namespace App\Models;

use CodeIgniter\Model;

class gejalaModel extends Model
{
    protected $table = 'data_gejala';
    protected $allowedFields = [
        'id_gejala', 'kategori', 'gejala',
    ];

    public function search($keyword)
    {

        $builder = $this->table('data_gejala');
        $builder->like('kategori', $keyword);
        $builder->orLike('gejala', $keyword);

        return $builder;
    }

    public function validateGejala($kategori, $gejala)
    {
        $builder = $this->table('data_gejala');
        $builder->where('kategori', $kategori);
        $builder->where('gejala', $gejala);

        return $builder;
    }

    public function getView()
    {
        $builder = $this->table('data_gejala');
        $builder->select('id_gejala, kategori, gejala');
        $builder->orderBy('kategori');
        $query = $this->builder->get();

        return $query;
    }

    public function countGejala()
    {
        $query = $this->db->query("SELECT * FROM `data_gejala`");
        $sql = $query->getNumRows();
        return $sql;
    }
}
