<?php

namespace App\Models;

use CodeIgniter\Model;

class decisionTreeModel extends Model
{
    protected $table = 'decision_tree';
    protected $allowedFields = [
        'parent', 'akar', 'keputusan',
    ];

    public function search($keyword)
    {

        $builder = $this->table('decision_tree');
        $builder->like('parent', $keyword);
        $builder->orLike('keputusan', $keyword);

        return $builder;
    }

    public function validateGejala($kategori, $gejala)
    {
        $builder = $this->table('decision_tree');
        $builder->where('parent', $kategori);
        $builder->where('keputusan', $gejala);

        return $builder;
    }
}
