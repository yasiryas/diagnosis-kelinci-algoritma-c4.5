<?php

namespace App\Controllers;

class Home extends BaseController
{
	protected $artikelModel;

	public function __construct()
	{
		$this->artikelModel = new \App\Models\artikelModel();
	}

	public function index()
	{
		return view('welcome_message');
	}

	public function landing()
	{
		session()->set('redirect_url', site_url('user'));

		return view('landing', ['artikelRingkasan' => $this->artikelModel->getPublished(3)]);
	}

	public function startDiagnosis()
	{
		return redirect()->to(site_url('diagnosa'));
	}
}
