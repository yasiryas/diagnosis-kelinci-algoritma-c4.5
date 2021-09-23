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
}
