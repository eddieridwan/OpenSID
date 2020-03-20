<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Suplemen extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('suplemen_model');
		$this->modul_ini = 2;
	}

	public function index()
	{
		$_SESSION['per_page'] = 50;
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['suplemen'] = $this->suplemen_model->list_data();
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/daftar', $data, $nav);
	}

	public function form_terdata($id)
	{
		$data['sasaran'] = unserialize(SASARAN);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);
		$sasaran = $data['suplemen']['sasaran'];
		$data['list_sasaran'] = $this->suplemen_model->list_sasaran($id, $sasaran);
		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->suplemen_model->get_terdata($_POST['terdata'], $sasaran);
		}
		else
		{
			$data['individu'] = NULL;
		}
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['form_action'] = site_url("suplemen/add_terdata");
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/form_terdata', $data, $nav);
	}

	public function panduan()
	{
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/panduan', $data, $nav);
	}

	public function sasaran($sasaran = 0)
	{
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['tampil'] = $sasaran;
		$data['program'] = $this->suplemen_model->list_suplemen($sasaran);
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/suplemen', $data, $nav);
	}

	public function rincian($p = 1, $id)
	{
		$nav['act'] = 2;
		$nav['act_sub'] = 25;

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];
		$data = $this->suplemen_model->get_rincian($p, $id);
		$data['sasaran'] = unserialize(SASARAN);
		$data['per_page'] = $_SESSION['per_page'];
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/rincian', $data, $nav, TRUE);
	}

	public function terdata($sasaran = 0, $id = 0)
	{
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data = $this->suplemen_model->get_terdata_suplemen($sasaran, $id);
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/terdata', $data, $nav);
	}

	public function data_terdata($id)
	{
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['terdata'] = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
		$data['individu'] = $this->suplemen_model->get_terdata($data['terdata']['id_terdata'], $data['suplemen']['sasaran']);
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('suplemen/data_terdata', $data, $nav);
	}

	public function add_terdata($id)
	{
		$this->suplemen_model->add_terdata($_POST, $id);
		redirect("suplemen/rincian/1/$id");
	}

	public function hapus_terdata($id_suplemen, $id_terdata)
	{
		$this->redirect_hak_akses('h', "suplemen/rincian/1/$id_suplemen");
		$this->suplemen_model->hapus_terdata($id_terdata);
		redirect("suplemen/rincian/1/$id_suplemen");
	}

	public function edit_terdata($id)
	{
		$this->suplemen_model->edit_terdata($_POST, $id);
		$id_suplemen = $_POST['id_suplemen'];
		redirect("suplemen/rincian/1/$id_suplemen");
	}

	public function edit_terdata_form($id = 0)
	{
		$data = $this->suplemen_model->get_suplemen_terdata_by_id($id);
		$data['form_action'] = site_url("suplemen/edit_terdata/$id");
		$this->load->view('suplemen/edit_terdata', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');
		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['form_action'] = "suplemen/create";
		if ($this->form_validation->run() === FALSE)
		{
			$tampil = 'suplemen/form';
		}
		else
		{
			$this->suplemen_model->create();
			redirect("suplemen/");
		}
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view($tampil, $data, $nav, TRUE);
		
	}

	public function edit($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Data', 'required');

		$nav['act'] = 2;
		$nav['act_sub'] = 25;
		$data['form_action'] = "suplemen/edit/$id";
		$data['suplemen'] = $this->suplemen_model->get_suplemen($id);

		if ($this->form_validation->run() === FALSE)
		{
			$tampil = 'suplemen/form';
		}
		else
		{
			$this->suplemen_model->update($id);
			redirect("suplemen/");
		}
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view($tampil, $data, $nav, TRUE);
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "suplemen/");
		$this->suplemen_model->hapus($id);
		redirect("suplemen/");
	}

	public function unduhsheet($id = 0)
	{
		if ($id > 0)
		{
			/*
			 * Print xls untuk data x
			 * */
			$_SESSION['per_page'] = 0; // Unduh semua data
			$data = $this->suplemen_model->get_rincian(1, $id);
			$data['sasaran'] = unserialize(SASARAN);
			$data['desa'] = $this->header_model->get_data();
			$_SESSION['per_page'] = 50; // Kembalikan ke paginasi default

			$this->load->view('suplemen/unduh-sheet', $data);

		}
	}
}
