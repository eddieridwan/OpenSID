<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Login
 *
 * donjo-app/controllers/Siteman.php
 *
 */
/*
 *  File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Siteman extends CI_Controller
{

	private $ip_address;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('config_model', 'user_model', 'log_siteman_model'));
	}

	public function index()
	{
		if (isset($_SESSION['siteman']) and 1 == $_SESSION['siteman'])
		{
			redirect('main');
		}
		unset($_SESSION['balik_ke']);
		$data['header'] = $this->config_model->get_data();

		$ip_address = get_ip_address();
		$log_ip = $this->log_siteman_model->ambil_log_ip($ip_address);
		$status_blokir = $this->log_siteman_model->cek_blokir($ip_address);

		$data['status_blokir'] = $status_blokir;
		$data['masa_tunggu'] = $status_blokir ? DURASI_BLOKIR_SITEMAN - (time() - strtotime($log_ip->updated_at)) : 0;
		$data['sisa_percobaan'] = $status_blokir ? 0 : MAX_PERCOBAAN_SITEMAN - $log_ip->counter;

		//Initialize Session ------------
		$_SESSION['success'] = 0;
		$_SESSION['per_page'] = 10;
		$_SESSION['cari'] = '';
		$_SESSION['pengumuman'] = 0;
		$_SESSION['sesi'] = "kosong";
		//-------------------------------

		$this->load->view('siteman', $data);
	}

	public function auth()
	{
		$method = $this->input->method(TRUE);
				$allow_method = ['POST'];
		if(!in_array($method,$allow_method))
		{
			redirect('siteman/login');
		}

		$ip_address = get_ip_address();
		$this->user_model->siteman($ip_address);

		if ($this->session->userdata('siteman') != 1)
		{
			// Gagal otentifikasi
			redirect('siteman');
		}

		if (!$this->user_model->syarat_sandi() and !($this->session->user == 1 && $this->setting->demo_mode))
		{
			// Password tidak memenuhi syarat kecuali di website demo
			redirect('user_setting/change_pwd');
		}

		$_SESSION['dari_login'] = '1';
		// Notif bisa dipanggil sewaktu-waktu dan tidak digunakan untuk redirect
		if (isset($_SESSION['request_uri']) and strpos($_SESSION['request_uri'], 'notif/') === FALSE)
		{
			// Lengkapi url supaya tidak diubah oleh redirect
			$request_awal = $_SERVER['HTTP_ORIGIN'] . $_SESSION['request_uri'];
			unset($_SESSION['request_uri']);
			redirect($request_awal);
		}
		else
		{
			unset($_SESSION['request_uri']);
			unset($this->session->fm_key);
			$this->user_model->get_fm_key();
			redirect('main');
		}
	}

	public function login()
	{
		$this->user_model->login();
		$data['header'] = $this->config_model->get_data();
		$this->load->view('siteman', $data);
	}

	public function logout()
	{
		$this->user_model->logout();
		$this->index();
	}

}
