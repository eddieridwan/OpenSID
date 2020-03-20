<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_kontruksi extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('inventaris_kontruksi_model');
		$this->load->model('referensi_model');
		$this->load->model('config_model');
		$this->load->model('surat_model');
		$this->modul_ini = 15;
		$this->tab_ini = 6;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('inventaris');
	}

	public function index()
	{
		$data['main'] = $this->inventaris_kontruksi_model->list_inventaris();
		$data['total'] = $this->inventaris_kontruksi_model->sum_inventaris();
		$data['pamong'] = $this->surat_model->list_pamong();
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/table', $data, $nav, TRUE);
	}

	public function view($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/view_inventaris', $data, $nav, TRUE);
	}

	public function view_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/view_mutasi', $data, $nav, TRUE);
	}

	public function edit($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/edit_inventaris', $data, $nav, TRUE);
	}

	public function edit_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->edit_mutasi($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/edit_mutasi', $data, $nav, TRUE);
	}

	public function form()
	{
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/form_tambah', $data, $nav, TRUE);
	}

	public function form_mutasi($id)
	{
		$data['main'] = $this->inventaris_kontruksi_model->view($id);
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/form_mutasi', $data, $nav, TRUE);
	}

	public function mutasi()
	{
		$data['main'] = $this->inventaris_kontruksi_model->list_mutasi_inventaris();
		$nav['act'] = 15;
		$nav['act_sub'] = 61;
		$data['tip'] = 1;
		
		// Isi nilai true jika menggunakan minisidebar
		$this->render_view('inventaris/kontruksi/table_mutasi', $data, $nav, TRUE);
	}

	public function cetak($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_kontruksi_model->sum_print($tahun);
		$data['print'] = $this->inventaris_kontruksi_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_kontruksi_model->pamong($penandatangan);
		$this->load->view('inventaris/kontruksi/inventaris_print', $data);
	}

	public function download($tahun, $penandatangan)
	{
		$data['header'] = $this->config_model->get_data();
		$data['total'] = $this->inventaris_kontruksi_model->sum_print($tahun);
		$data['print'] = $this->inventaris_kontruksi_model->cetak($tahun);
		$data['pamong'] = $this->inventaris_kontruksi_model->pamong($penandatangan);
		$this->load->view('inventaris/kontruksi/inventaris_excel', $data);
	}

}