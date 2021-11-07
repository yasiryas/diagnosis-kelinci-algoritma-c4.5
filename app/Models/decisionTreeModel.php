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
        $builder->orLike('akar', $keyword);
        $builder->orLike('keputusan', $keyword);
        $builder->orLike('penyakit', $keyword);

        return $builder;
    }

    public function validateGejala($parent, $keputusan, $gejala)
    {
        $builder = $this->table('decision_tree');
        $builder->where('parent', $parent);
        $builder->where('keputusan', $keputusan);
        $builder->where('akar', $gejala);

        return $builder;
    }

    public function getView()
    {
        $builder = $this->table('decision_tree');
        $builder->select('decision_tree.parent, decision_tree.akar, decision_tree.keputusan, data_penyakit.penyakit');
        $builder->join('data_penyakit', 'data_penyakit.id_penyakit = decision_tree.keputusan');
        $query = $this->builder->get();
        return $query;
    }
}
