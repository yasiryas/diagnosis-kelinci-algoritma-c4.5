<?php

namespace App\Models;

use CodeIgniter\Model;

class penyakitModel extends Model
{
    protected $table = 'data_penyakit';
    protected $allowedFields = [
        'id_penyakit', 'penyakit', 'obat', 'solusi',
    ];

    public function search($keyword)
    {

        $builder = $this->table('data_penyakit');
        $builder->like('penyakit', $keyword);
        $builder->orLike('obat', $keyword);
        $builder->orLike('solusi', $keyword);

        return $builder;
    }

    public function validatePenyakit($penyakit)
    {
        $builder = $this->table('data_penyakit');
        $builder->where('penyakit', $penyakit);

        return $builder;
    }

    public function hasil($id)
    {
        // $builder = $this->table('data_penyakit');
        // $builder->select('id_penyakit', 'penyakit', 'obat', 'solusi');
        // $builder->where('id_penyakit', 4);
        // $query = $this->builder->get();

        $sql = $this->db->query("SELECT * FROM `data_penyakit` WHERE id_penyakit = $id");
        // $this->db->query($sql);
        $query = $sql->getRow();

        return $query;
    }
}
