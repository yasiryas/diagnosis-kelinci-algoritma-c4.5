<?php

namespace App\Controllers;

use App\Models\gejalaModel;
use App\Models\penyakitModel;
use App\Models\sampleModel;


class mining extends BaseController
{

    protected $db, $builder;
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->gejalaModel = new gejalaModel();
        $this->penyakitModel = new penyakitModel();
        $this->sampleModel = new sampleModel();
    }

    //Percobaan satu dengan penuh kesalahan 
    //Tempat berbuat salah
    public function hitung()
    {
        $atribut = $this->sampleModel->getJumlahAtribut();
        $jumlahkasus = ($this->sampleModel->getView()->getNumRows()) / ($atribut->atribut);
        $kasus = $this->sampleModel->getKasus();
        $jumlahpenyakit = $this->sampleModel->getJumlahPenyakit();
        $jumlahkasuspenyakit = $this->sampleModel->getJumlahKasusPenyakit();
        $jumlahgejala = $this->sampleModel->getJumlahGejala();


        $data['title'] = 'Data Mining';
        $data['atribut'] = $atribut;
        $data['jumlahkasus'] = $jumlahkasus;
        $data['kasus'] = $kasus;
        $data['jumlahpenyakit'] = $jumlahpenyakit;
        $data['jumlahkasuspenyakit'] = $jumlahkasuspenyakit;
        $data['jumlahgejala'] = $jumlahgejala;



        return view('admin/hasilmining', $data);
    }

    public function mining()
    {
        //penyiapan variabel untuk bahan mining
        $atribut = $this->sampleModel->getJumlahAtribut();
        $jumlahkasus = ($this->sampleModel->getView()->getNumRows()) / ($atribut->atribut);
        $kasus = $this->sampleModel->getKasus();
        $jumlahpenyakit = $this->sampleModel->getJumlahPenyakit();
        $jumlahkasuspenyakit = $this->sampleModel->getJumlahKasusPenyakit();
        $jumlahgejala = $this->sampleModel->getJumlahGejala();
        $jumlahGejalaKasus = $this->sampleModel->getMiningKasus();



        //======================Data Mining -> Algorithm C4.5==================================

        //Menghitung entropy total
        $entropytotal = 0;
        foreach ($jumlahpenyakit as $jp) :
            $jumlahAtribut = ($jp->total) / ($atribut->atribut);
            $proportion = $jumlahAtribut / $jumlahkasus;
            $entropy = (-$proportion * log($proportion, 2));
            $entropytotal += $entropy;
        endforeach;

        //Menghitung Entropy Tiap Value 

        //1. Menyimpan data jumlah total gejala dengan penyakit
        $this->sampleModel->clearMiningEntropy();
        foreach ($jumlahkasuspenyakit as $jkp) :
            $idGejala = $jkp->id_gejala;
            $idPenyakit = $jkp->id_penyakit;
            $total = $jkp->total;
            $this->sampleModel->saveMiningEntropy($idGejala, $idPenyakit, $total);
        endforeach;

        //2. menyimpan data jumlah kasus yang terjadi
        $this->sampleModel->clearMiningKasus();
        foreach ($kasus as $k) :
            $atributKasus = $k->id_gejala;
            $totalKasus = $k->total;
            $this->sampleModel->saveMiningKasus($atributKasus, $totalKasus);
        endforeach;

        //3. Formula hitung entropy tiap atribut

        //=============Hitung entropy=============

        $this->sampleModel->clearEntropy();
        $entropyHitung = $entropytotal;
        $entropyAtr = 0;
        $gainAtr = 0;


        foreach ($jumlahgejala as $jg) :
            foreach ($jumlahkasuspenyakit as $jkp) :
                if ($jkp->id_gejala == $jg->id_gejala) {
                    $entropyA = (- ($jkp->total / $jg->total) * log(($jkp->total / $jg->total), 2));
                    $entropyAtr += $entropyA;
                } else {
                    $entropyAtr = 0;
                }

                if ($entropyAtr != 0) {
                    $idGejala = $jkp->id_gejala;
                    $this->sampleModel->saveEntropy($idGejala, $entropyAtr);
                } else {
                    $idGejala = $jkp->id_gejala;
                    $this->sampleModel->saveEntropy($idGejala, $entropyAtr);
                }
            endforeach;
        endforeach;

        //=============End  Hitung entropy==========

        //=============Hitung Gain ============
        $entropyGainTotal = $entropytotal;
        $jumlahKasus = $jumlahkasus;
        $gain2 = 0;
        $this->sampleModel->clearMiningGain();
        foreach ($kasus as $k) :
            $id_gejala = $k->id_gejala;
            $kategori = $k->kategori;
            $entropyAtribut = $this->sampleModel->getEntropy($id_gejala);
            foreach ($entropyAtribut as $entropy) :
                $kategori2 = $entropy->kategori;
                $totalgejalakasus = $k->total;
                if ($kategori == $kategori2) {
                    $ea = $entropy->entropy;
                    $gain1 = ($totalgejalakasus / $jumlahKasus) * $ea;
                    $gain2 = $gain1;
                    $this->sampleModel->saveMiningGain($kategori2, $gain2);
                } else {
                    $gain1 = 0;
                    $gain2 = 0;
                    $entropyGainTotal = $entropytotal;
                }
            endforeach;
        endforeach;


        $this->sampleModel->clearGain();
        $miningGain = $this->sampleModel->getMiningGain();
        foreach ($miningGain as $m) :
            $entropyTotal = $entropytotal;
            $kategori = $m->kategori;
            $gainMining = $m->gain;
            $gain = $entropyTotal - $gainMining;
            $this->sampleModel->saveGain($kategori, $gain);
        endforeach;


        //=============End Hitung Gain =========


        //=============Hitung Split Info =========
        $splitinfo = 0;
        $si = 0;
        $this->sampleModel->clearMiningSplitInfo();
        foreach ($kasus as $k) :
            $id_gejala = $k->id_gejala;
            $kategori = $k->kategori;
            $totalgejalakasus = $k->total;

            $splitinfo = (- ($totalgejalakasus / $jumlahKasus)) *  log(($totalgejalakasus / $jumlahKasus), 2);
            $si += $splitinfo;
            $this->sampleModel->saveMiningSplitInfo($kategori, $si);

            $si = 0;
            $splitinfo = 0;

        endforeach;


        // $miningSplitInfo = $this->sampleModel->getMiningSplitInfo();
        // foreach ($miningSplitInfo as $splitinfo) :
        //     $kategori = $splitinfo->kategori;
        //     $saveSplitInfo = $splitinfo->splitinfo;
        //     $this->sampleModel->saveSplitInfo($kategori, $saveSplitInfo);
        // endforeach;
        //=============End Hitung Split Info ===========


        //============= Hitung Gain Ratio =============

        $splitinfoForGainRatio = $this->sampleModel->getMiningSplitInfo();
        $gainForGainRatio = $this->sampleModel->getGain();
        $this->sampleModel->clearGainRatio();
        foreach ($splitinfoForGainRatio as $splitinfo) :
            $hitungSplitInfo = $splitinfo->total;
            $kategori = $splitinfo->kategori;
            foreach ($gainForGainRatio as $gain) :
                $kategori1 = $gain->kategori;
                $hitungGain = $gain->gain;
                if ($kategori == $kategori1) {
                    $gainRatio = $hitungGain / $hitungSplitInfo;
                    $this->sampleModel->saveGainRatio($kategori, $gainRatio);
                }
            endforeach;
        endforeach;

        //============= End Hitung Gain Ratio ==========

        //============= Membuat Cabang Pohon Keputusan ==========
        $this->sampleModel->clearPemangkasan();
        // 1. Memilih Nilai Gain Tertinggi => Result = Atribut/Kategori   
        $topGain = $this->sampleModel->getTopGain();

        // 2. Memisah dengan Atribut -> Gejala dan Penyakit
        foreach ($topGain as $tg) :
            $kategoriGain = $tg->kategori;
        endforeach;
        $viewGain = $this->sampleModel->getViewTopGain($kategoriGain);
        foreach ($viewGain as $vg) :
            $id_gejala = $vg->id_gejala;
            $kategori = $vg->kategori;
            $gejala = $vg->gejala;
            $entropyAtribut = $this->sampleModel->getEntropy($id_gejala);
            foreach ($entropyAtribut as $entropy) :
                $value = $entropy->entropy;
                if ($value == 0) {
                    $this->sampleModel->savePemangkasan($kategori, $gejala);
                }
            // var_dump($entropyAtribut);
            endforeach;
        endforeach;
        // // var_dump($kategoriGain);
        // $pangkas = $this->sampleModel->getPemangkasan();
        // foreach ($pangkas as $p) :
        //     $parent = $vg->id_gejala;
        //     $akar = $vg->kategori;
        //     $keputusan = $vg->penyakit;
        //     $gejalaP = $p->gejala;
        //     $totalP = $p->total;
        //     // var_dump($gejala);
        //     // if ($gejalaP == $gejala and $totalP == 1) {
        //     //     $this->sampleModel->saveDecisionTree(
        //     //         $parent,
        //     //         $akar,
        //     //         $keputusan
        //     //     );
        //     // }
        // endforeach;



        // 3. Membuat pohon keputusan pada tabel pohon keputusan
        // 4. Menghapus data Node dari data sample
        // 5. Membuat pengulangan pada Mining
        //============= End Membuat Cabang Pohon Keputusan =======



        //penyiapan data yang akan dikirimkan ke page mining
        $data = [
            'title' => 'Data Mining',
            'atribut' => $atribut,
            'jumlahkasus' => $jumlahkasus,
            'kasus' => $kasus,
            'jumlahpenyakit' => $jumlahpenyakit,
            'jumlahkasuspenyakit' => $jumlahkasuspenyakit,
            'jumlahgejala' => $jumlahgejala,
            'entropytotal' => $entropytotal,

        ];

        //mengembalikan nilai he laporan
        return view('admin/lapormining', $data);
    }

    public function hitungGain()
    {
    }
}
