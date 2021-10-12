<?php

namespace App\Models;

use CodeIgniter\Model;

class sampleModel extends Model
{
    protected $table = 'data_sample';
    protected $allowedFields = [
        'id_sample', 'id_gejala', 'id_penyakit',
    ];

    public function search($keyword)
    {
        $builder = $this->table('mining_sample');
        $builder->like('penyakit', $keyword);
        $builder->orLike('gejala', $keyword);
        $builder->orLike('kategori', $keyword);

        return $builder;
    }

    public function getView()
    {
        $builder = $this->table('data_sample');
        $builder->select('data_sample.id_sample, data_gejala.kategori, data_gejala.gejala, data_penyakit.penyakit');
        $builder->join('data_gejala', 'data_sample.id_gejala = data_gejala.id_gejala');
        $builder->join('data_penyakit', 'data_sample.id_penyakit = data_penyakit.id_penyakit');
        $query = $this->builder->get();
        return $query;

        // $query = $this->db->query('SELECT data_gejala.kategori as kategori,data_gejala.gejala as gejala, data_penyakit.penyakit FROM data_sample join data_gejala ON data_sample.id_gejala = data_gejala.id_gejala JOIN data_penyakit on data_sample.id_penyakit = data_penyakit.id_penyakit');
        // $tampil = $query->getRow();
        // return $tampil;
    }

    // public function validateGejala($kategori, $gejala)
    // {
    //     $builder = $this->table('data_sample');
    //     $builder->where('penyakit', $kategori);
    //     $builder->where('gejala', $gejala);

    //     return $builder;
    // }



    //Kosongkan mining_sample
    public function clearMiningSample()
    {
        $sql = "TRUNCATE TABLE mining_sample";
        $this->db->query($sql);
    }

    //Start Mining -> copy data_sample to mining_sample
    public function startMiningSample()
    {
        $sql = "INSERT INTO mining_sample SELECT * FROM data_sample";
        $this->db->query($sql);
    }

    //Mining delete sample with id
    public function deleteMiningSample($id_gejala)
    {
        $sql = "DELETE FROM mining_sample WHERE id_gejala= $id_gejala";
        $this->db->query($sql);
    }

    //Menghitung jumlah atribut / kategori
    public function getJumlahAtribut()
    {
        $query = $this->db->query('SELECT COUNT(DISTINCT data_gejala.kategori) AS atribut FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala');
        $atribut = $query->getRow();
        return $atribut;
    }

    public function getAtribut()
    {
        $query = $this->db->query('SELECT DISTINCT data_gejala.kategori AS atribut FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala');
        $atribut = $query->getRow();
        return $atribut;
    }


    // public function getKategori()
    // {
    //     $query = $this->db->query('SELECT COUNT(DISTINCT data_gejala.kategori) AS atribut FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala');
    //     $atribut = $query->getRow();
    //     return $atribut;
    // }


    //menghitung jumlah kasus dengan kategori dan gejala yang berbeda
    //data bernilai null tidak ditampilkan

    public function getKasus()
    {
        $query = $this->db->query('SELECT DISTINCT data_gejala.id_gejala,data_gejala.kategori, data_gejala.gejala, COUNT(*) as total FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala GROUP BY kategori,gejala');
        $kasus = $query->getResult();
        return $kasus;
    }

    //menangkap nama penyakit dengan jumlah kasus penyakit yang berbeda
    public function getJumlahPenyakit()
    {
        $query = $this->db->query('SELECT DISTINCT data_penyakit.penyakit, COUNT(*) as total FROM mining_sample JOIN data_penyakit ON mining_sample.id_penyakit=data_penyakit.id_penyakit GROUP BY penyakit');
        $jumlahPenyakit = $query->getResult();
        return $jumlahPenyakit;
    }

    //menangkap kategori, gejala, penyakit dan jumlah kasus yang berbeda
    public function getJumlahKasusPenyakit()
    {
        $query = $this->db->query('SELECT DISTINCT data_gejala.id_gejala, data_penyakit.id_penyakit, data_gejala.kategori,data_gejala.gejala,data_penyakit.penyakit, COUNT(penyakit) as total FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala JOIN data_penyakit ON mining_sample.id_penyakit = data_penyakit.id_penyakit GROUP BY kategori,gejala,penyakit');
        $jumlahKasusPenyakit = $query->getResult();
        return $jumlahKasusPenyakit;
    }

    //menghitung jumlah kasus gejala yang berbeda
    public function getJumlahGejala()
    {
        $query = $this->db->query('SELECT DISTINCT data_gejala.id_gejala,data_gejala.kategori,data_gejala.gejala, COUNT(*) as total FROM mining_sample join data_gejala ON mining_sample.id_gejala = data_gejala.id_gejala JOIN data_penyakit ON mining_sample.id_penyakit = data_penyakit.id_penyakit GROUP BY kategori,gejala');
        $jumlahGejala = $query->getResult();
        return $jumlahGejala;
    }

    //kosongkan data mining_entropy
    public function clearMiningEntropy()
    {
        $sql = "TRUNCATE TABLE mining_entropy";
        $this->db->query($sql);
    }

    //simpan data mining_entropy
    public function saveMiningEntropy($idGejala, $idPenyakit, $total)
    {
        $sql = "INSERT INTO mining_entropy (id, id_gejala, id_penyakit, total) VALUES (NULL, $idGejala, $idPenyakit,$total)";

        $this->db->query($sql);
    }

    //kosongkan data mining_kasus
    public function clearMiningKasus()
    {
        $sql = "TRUNCATE TABLE mining_kasus";
        $this->db->query($sql);
    }

    //simpan data mining_kasus
    public function saveMiningKasus($atributKasus, $totalKasus)
    {
        $sql = "INSERT INTO mining_kasus (id, id_gejala, total) VALUES (NULL, $atributKasus, $totalKasus)";

        $this->db->query($sql);
    }

    //Get data mining_kasus
    public function getMiningKasus()
    {
        $sql = "SELECT mining_kasus.id_gejala, data_gejala.kategori, mining_kasus.total FROM `mining_kasus` JOIN data_gejala ON data_gejala.id_gejala = mining_kasus.id_gejala";
        $this->db->query($sql);
    }

    //simpan data entropy
    public function saveEntropy($idGejala, $entropy)
    {
        $sql = "INSERT INTO entropy (id, id_gejala, entropy) VALUES (NULL, $idGejala, $entropy)";
        $this->db->query($sql);
    }

    //get data entropy
    public function getEntropy($idGejala)
    {
        // $sql = "SELECT * FROM `entropy` WHERE id_gejala = $idGejala ORDER BY entropy DESC LIMIT 1";
        $sql = "SELECT entropy.id, entropy.id_gejala, data_gejala.kategori, entropy.entropy FROM `entropy` join data_gejala on entropy.id_gejala = data_gejala.id_gejala WHERE entropy.id_gejala = $idGejala ORDER BY entropy DESC LIMIT 1";
        $query = $this->db->query($sql);
        $entropy = $query->getResult();
        return $entropy;
    }

    //get data gejala
    public function getGejala()
    {
        $sql = "SELECT * FROM `data_gejala`";
        $this->db->query($sql);
    }

    //kosongkan data entropy
    public function clearEntropy()
    {
        $sql = "TRUNCATE TABLE entropy";
        $this->db->query($sql);
    }

    //simpan data mining gain
    public function saveMiningGain($kategori, $gain)
    {
        $sql = "INSERT INTO `mining_gain` (`id`, `kategori`, `gain`) VALUES (NULL, '$kategori', '$gain');";
        $this->db->query($sql);
    }

    //kosongkan data mining gain
    public function clearMiningGain()
    {
        $sql = "TRUNCATE TABLE mining_gain";
        $this->db->query($sql);
    }

    //menghitung jumlah mining gain
    public function getMiningGain()
    {
        $query = $this->db->query('SELECT kategori, sum(gain) as gain FROM `mining_gain` group by kategori');
        $miningGain = $query->getResult();
        return $miningGain;
    }

    //simpan data  gain
    public function saveGain($kategori, $gain)
    {
        $sql = "INSERT INTO `gain` (`id`, `kategori`, `gain`) VALUES (NULL, '$kategori', '$gain');";
        $this->db->query($sql);
    }

    //kosongkan data  gain
    public function clearGain()
    {
        $sql = "TRUNCATE TABLE gain";
        $this->db->query($sql);
    }

    //get jumlah  gain
    public function getGain()
    {
        $query = $this->db->query('SELECT * FROM `gain`');
        $gain = $query->getResult();
        return $gain;
    }

    //simpan data  splitinfo
    public function saveMiningSplitInfo($kategori, $si)
    {
        $sql = "INSERT INTO `mining_splitinfo` (`id`, `kategori`, `splitinfo`) VALUES (NULL, '$kategori', '$si')";
        $this->db->query($sql);
    }

    //kosongkan data  splitinfo
    public function clearMiningSplitInfo()
    {
        $sql = "TRUNCATE TABLE mining_splitinfo";
        $this->db->query($sql);
    }

    //get splitinfo
    public function getMiningSplitInfo()
    {
        $query = $this->db->query('SELECT kategori, sum(splitinfo) as total FROM `mining_splitinfo` GROUP BY kategori');
        $splitinfo = $query->getResult();
        return $splitinfo;
    }

    //simpan data  splitinfo
    public function saveSplitInfo($kategori, $si)
    {
        $sql = "INSERT INTO `splitinfo` (`id`, `kategori`, `splitinfo`) VALUES (NULL, '$kategori', '$si')";
        $this->db->query($sql);
    }

    //get splitinfo
    public function getSplitInfo()
    {
        $query = $this->db->query('SELECT kategori, sum(splitinfo) FROM `mining_splitinfo` GROUP BY kategori');
        $splitinfo = $query->getResult();
        return $splitinfo;
    }

    //simpan data  gain
    public function saveGainRatio($kategori, $gainRatio)
    {
        $sql = "INSERT INTO `gain_ratio` (`id`, `kategori`, `gain_ratio`) VALUES (NULL, '$kategori', '$gainRatio')";
        $this->db->query($sql);
    }

    //kosongkan data  splitinfo
    public function clearGainRatio()
    {
        $sql = "TRUNCATE TABLE gain_ratio";
        $this->db->query($sql);
    }

    //Cari Gain Tertinggi
    public function getTopGain()
    {
        $query = $this->db->query('SELECT kategori, max(gain) as gain FROM `gain`');
        $gain = $query->getResult();
        return $gain;
    }

    //Menampilkan gain tertinggi
    public function getViewTopGain($kategori)
    {
        // $query = $this->db->query("SELECT data_gejala.id_gejala, data_penyakit.id_penyakit, data_gejala.kategori,data_gejala.gejala,data_penyakit.penyakit, COUNT(penyakit) as total FROM data_sample join data_gejala ON data_sample.id_gejala = data_gejala.id_gejala JOIN data_penyakit ON data_sample.id_penyakit = data_penyakit.id_penyakit WHERE kategori = '$kategori' GROUP BY kategori,gejala,penyakit ORDER BY `data_gejala`.`gejala` DESC");
        $query = $this->db->query("SELECT data_gejala.id_gejala, data_penyakit.id_penyakit, data_gejala.kategori,data_gejala.gejala,data_penyakit.penyakit, COUNT(penyakit) as total FROM data_sample join data_gejala ON data_sample.id_gejala = data_gejala.id_gejala JOIN data_penyakit ON data_sample.id_penyakit = data_penyakit.id_penyakit WHERE kategori = '$kategori' GROUP BY kategori,gejala ORDER BY `data_gejala`.`gejala` DESC");
        $gain = $query->getResult();
        return $gain;
    }

    //insert pemangkasan
    public function savePemangkasan($kategori, $gejala)
    {
        $sql = "INSERT INTO `pemangkasan` (`id`, `kategori`, `gejala`) VALUES (NULL, '$kategori', '$gejala')";
        $this->db->query($sql);
    }

    //clear pemangkasan
    public function clearPemangkasan()
    {
        $sql = "TRUNCATE TABLE `pemangkasan`";
        $this->db->query($sql);
    }

    //Menampilkan Pemangkasan
    public function getPemangkasan()
    {
        $query = $this->db->query("SELECT *, COUNT(*) AS total FROM `pemangkasan` GROUP BY gejala");
        $sql = $query->getResult();
        return $sql;
    }

    //insert decision tree
    public function saveDecisionTree($parent, $akar, $keputusan)
    {
        $sql = "INSERT INTO `decision_tree` (`id`, `parent`, `akar`, `keputusan`) VALUES (NULL, '$parent', '$akar','$keputusan')";
        $this->db->query($sql);
    }

    public function clearDecisionTree()
    {
        $sql = "TRUNCATE TABLE `decision_tree`";
        $this->db->query($sql);
    }
}
