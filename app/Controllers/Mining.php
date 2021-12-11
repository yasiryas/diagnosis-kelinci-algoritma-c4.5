<?php

namespace App\Controllers;

use App\Models\gejalaModel;
use App\Models\penyakitModel;
use App\Models\sampleModel;
use App\Models\miningModel;


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
        $this->miningModel = new miningModel();
    }

    //Percobaan satu dengan penuh kesalahan 
    //Tempat berbuat salah
    public function hitung()
    {

        // $jumlahkasus = 0;

        $atribut = $this->miningModel->getJumlahAtribut();
        $jumlahkasus = ($this->miningModel->getData()->getNumRows()) / ($atribut->atribut);
        $kasus = $this->miningModel->getKasus();
        $jumlahpenyakit = $this->miningModel->getJumlahPenyakit();
        $jumlahkasuspenyakit = $this->miningModel->getJumlahKasusPenyakit();
        $jumlahgejala = $this->miningModel->getJumlahGejala();


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
        //Kosongkan Pohon Keputusan dan Sample
        $this->miningModel->clearDecisionTree();
        $this->miningModel->clearMiningSample();

        //Start mining transfer data data_sample to mining_sample
        $this->miningModel->startMiningSample();

        //penyiapan variabel untuk bahan mining
        $jumlahkasus = 0;
        $startCabang = "";
        $startDetail = "";
        $atribut = $this->miningModel->getJumlahAtribut();


        //======================Data Mining -> Algorithm C4.5==================================


        //Start Perulangan (while)
        $i = $atribut->atribut;
        while ($i > 0) {

            //isi variabel
            $jumlahkasus = ($this->miningModel->getData()->getNumRows()) / ($atribut->atribut);
            $kasus = $this->miningModel->getKasus();
            $jumlahpenyakit = $this->miningModel->getJumlahPenyakit();
            $jumlahkasuspenyakit = $this->miningModel->getJumlahKasusPenyakit();
            $jumlahgejala = $this->miningModel->getJumlahGejala();
            $jumlahGejalaKasus = $this->miningModel->getMiningKasus();



            //Menghitung entropy total
            $entropytotal = 0;
            foreach ($jumlahpenyakit as $jp) :
                $jumlahAtribut = ($jp->total) / ($atribut->atribut);
                // var_dump($jumlahAtribut);
                $proportion = $jumlahAtribut / $jumlahkasus;
                $entropy = (-$proportion * log($proportion, 2));
                $entropytotal += $entropy;
            endforeach;

            //Menghitung Entropy Tiap Value 

            //1. Menyimpan data jumlah total gejala dengan penyakit
            $this->miningModel->clearMiningEntropy();
            foreach ($jumlahkasuspenyakit as $jkp) :
                $idGejala = $jkp->id_gejala;
                $idPenyakit = $jkp->id_penyakit;
                $total = $jkp->total;
                $this->miningModel->saveMiningEntropy($idGejala, $idPenyakit, $total);
            endforeach;

            //2. menyimpan data jumlah kasus yang terjadi
            $this->miningModel->clearMiningKasus();
            foreach ($kasus as $k) :
                $atributKasus = $k->id_gejala;
                $totalKasus = $k->total;
                $this->miningModel->saveMiningKasus($atributKasus, $totalKasus);
            endforeach;

            //3. Formula hitung entropy tiap atribut

            //=============Hitung entropy=============

            $this->miningModel->clearEntropy();
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
                        $this->miningModel->saveEntropy($idGejala, $entropyAtr);
                    } else {
                        $idGejala = $jkp->id_gejala;
                        $this->miningModel->saveEntropy($idGejala, $entropyAtr);
                    }
                endforeach;
            endforeach;

            //=============End  Hitung entropy==========

            //=============Hitung Gain ============
            $entropyGainTotal = $entropytotal;
            $jumlahKasus = $jumlahkasus;
            $gain2 = 0;
            $this->miningModel->clearMiningGain();
            foreach ($kasus as $k) :
                $id_gejala = $k->id_gejala;
                $kategori = $k->kategori;
                $entropyAtribut = $this->miningModel->getEntropy($id_gejala);
                foreach ($entropyAtribut as $entropy) :
                    $kategori2 = $entropy->kategori;
                    $totalgejalakasus = $k->total;
                    if ($kategori == $kategori2) {
                        $ea = $entropy->entropy;
                        $gain1 = ($totalgejalakasus / $jumlahKasus) * $ea;
                        $gain2 = $gain1;
                        $this->miningModel->saveMiningGain($kategori2, $gain2);
                    } else {
                        $gain1 = 0;
                        $gain2 = 0;
                        $entropyGainTotal = $entropytotal;
                    }
                endforeach;
            endforeach;


            $this->miningModel->clearGain();
            $miningGain = $this->miningModel->getMiningGain();
            foreach ($miningGain as $m) :
                $entropyTotal = $entropytotal;
                $kategori = $m->kategori;
                $gainMining = $m->gain;
                $gain = $entropyTotal - $gainMining;
                $this->miningModel->saveGain($kategori, $gain);
            endforeach;


            //=============End Hitung Gain =========


            //=============Hitung Split Info =========
            $splitinfo = 0;
            $si = 0;
            $this->miningModel->clearMiningSplitInfo();
            foreach ($kasus as $k) :
                $id_gejala = $k->id_gejala;
                $kategori = $k->kategori;
                $totalgejalakasus = $k->total;

                $splitinfo = (- ($totalgejalakasus / $jumlahKasus)) *  log(($totalgejalakasus / $jumlahKasus), 2);
                $si += $splitinfo;
                $this->miningModel->saveMiningSplitInfo($kategori, $si);

                $si = 0;
                $splitinfo = 0;

            endforeach;


            // $miningSplitInfo = $this->miningModel->getMiningSplitInfo();
            // foreach ($miningSplitInfo as $splitinfo) :
            //     $kategori = $splitinfo->kategori;
            //     $saveSplitInfo = $splitinfo->splitinfo;
            //     $this->miningModel->saveSplitInfo($kategori, $saveSplitInfo);
            // endforeach;
            //=============End Hitung Split Info ===========


            //============= Hitung Gain Ratio =============

            $splitinfoForGainRatio = $this->miningModel->getMiningSplitInfo();
            $gainForGainRatio = $this->miningModel->getGain();
            $this->miningModel->clearGainRatio();
            foreach ($splitinfoForGainRatio as $splitinfo) :
                $hitungSplitInfo = $splitinfo->total;
                $kategori = $splitinfo->kategori;
                foreach ($gainForGainRatio as $gain) :
                    $kategori1 = $gain->kategori;
                    $hitungGain = $gain->gain;
                    if ($kategori == $kategori1) {
                        $gainRatio = $hitungGain / $hitungSplitInfo;
                        $this->miningModel->saveGainRatio($kategori, $gainRatio);
                    }
                endforeach;
            endforeach;

            //============= End Hitung Gain Ratio ==========

            //============= Membuat Cabang Pohon Keputusan ==========
            $this->miningModel->clearPemangkasan();
            // 1. Memilih Nilai Gain Tertinggi => Result = Atribut/Kategori   
            $topGain = $this->miningModel->getTopGain();

            // 2. Memisah dengan Atribut -> Gejala dan Penyakit
            foreach ($topGain as $tg) :
                $kategoriGain = $tg->kategori;
            endforeach;
            $viewGain = $this->miningModel->getViewTopGain($kategoriGain);
            foreach ($viewGain as $vg) :
                $id_gejala = $vg->id_gejala;
                $kategori = $vg->kategori;
                $gejala = $vg->gejala;
                $keputusan = $vg->id_penyakit;
                $entropyAtribut = $this->miningModel->getEntropy($id_gejala);

                // 3. Membuat pohon keputusan pada tabel pohon keputusan
                foreach ($entropyAtribut as $entropy) :
                    $value = $entropy->entropy;
                    if ($value == 0) {

                        if ($startCabang == "") {
                            $plus = "";
                        } else if ($startCabang == $id_gejala) {
                            $startCabang = "";
                            $startDetail = "";
                        } else {
                            $plus = " ";
                        }
                        $parent = $startCabang . $plus . $id_gejala;

                        $cekDetail = $kategori . ' ' . $gejala;
                        if ($startDetail == "") {
                            $and = "";
                        } else if ($startDetail == $cekDetail) {
                            $startDetail = "";
                        } else {
                            $and = " dan ";
                        }


                        $detail = $startDetail . $and . $kategori . ' ' . $gejala;

                        $this->miningModel->saveDecisionTree(

                            $parent,
                            $detail,
                            $kategori,
                            $keputusan
                        );

                        // 4. Menghapus data Node dari data sample
                        $this->miningModel->deleteMiningSampleGejala($id_gejala);
                        $this->miningModel->deleteMiningSamplePenyakit($keputusan);
                    } else {

                        $plus = " ";
                        if ($startCabang == "") {
                            $plus = "";
                        } else {
                            $plus = " ";
                        }

                        $cabang = $plus . $id_gejala;


                        $and = " ";
                        if (
                            $startDetail == ""
                        ) {
                            $and = "";
                        } else {
                            $and = " dan ";
                        }
                        $detailCabang = $and . $kategori . ' ' . $gejala;

                        // 4. Menghapus data Node dari data sample
                        // $this->miningModel->deleteMiningSample($id_gejala);
                        // $this->miningModel->deleteMiningSamplePenyakit($keputusan);
                        $this->miningModel->deleteMiningSampleGejala($id_gejala);
                    }
                // var_dump($entropyAtribut);
                endforeach;
            endforeach;
            if ($startCabang == $cabang) {
                $detailCabang = "";
                $startCabang = "";
            }
            $startCabang .= $cabang;
            $startDetail .= $detailCabang;



            // 5. Membuat pengulangan pada Mining 
            //============= End Membuat Cabang Pohon Keputusan =======
            $i--;
        } //akhir dari pengulangan while


        return redirect()->to('/admin/decisiontree');
    }

    public function kosong()
    {
        $this->miningModel->clearDecisionTree();
        $this->miningModel->clearMiningSample();
        $this->miningModel->startMiningSample();
    }
}
