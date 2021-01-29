<?php
	if ( ! isset($this->session->mandiri) OR $this->session->mandiri <> 1) {
		if ($this->session->mandiri_wait == 1) { ?>
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-user"></i> Layanan Mandiri</h3><br/>
					Silakan datang atau hubungi operator <?= $this->setting->layanan_mandiri?> untuk mendapatkan kode PIN anda.
				</div>
				<div class="box-body">
					<h4>Gagal 3 kali, silakan coba kembali dalam <?= waktu_ind((time()- $this->session->mandiri_timeout)*(-1)); ?> detik lagi</h4>
						<div id="note">
							Login Gagal. Username atau Password yang anda masukkan salah!
						</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-user"></i> Layanan Mandiri</h3><br/>
					Silakan datang atau hubungi operator <?= $this->setting->layanan_mandiri; ?> untuk mendapatkan kode PIN anda.
				</div>
				<div class="box-body">
					<h4>Masukan NIK dan PIN</h4>
					<form action="<?= site_url('first/auth'); ?>" method="post">
					<input name="nik" type="text" placeholder="NIK" style="margin-left:0px" value="" required>
					<input name="pin" type="password" placeholder="PIN" style="margin-left:0px" value="" required>
					<button type="submit" id="but" style="margin-left:0px">Masuk</button>
						<?php if ($this->session->mandiri_try && isset($this->session->mandiri) && $this->session->mandiri == -1) { ?>
						<div id="note">
							Kesempatan mencoba <?= ($this->session->mandiri_try-1); ?> kali lagi.
						</div>
						<?php }?>
						<?php if (isset($this->session->mandiri) && $this->session->mandiri == -1) { ?>
						<div id="note">
							Login Gagal. Username atau Password yang Anda masukkan salah!
						</div>
						<?php }?>
					</form>
				</div>
			</div>
		<?php }
	} else { ?>
		<div class="box box-primary box-solid">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-user"></i> Layanan Mandiri</h3>
			</div>
			<div class="box-body">
				<ul id="ul-mandiri">
				<table id="mandiri" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="25%">Nama</td>
						<td width="2%" class="titik">:</td>
						<td width="73%"><?= $this->session->nama; ?></td>
					</tr>
					<tr>
						<td bgcolor="#eee">NIK</td>
						<td class="titik" bgcolor="#eee">:</td>
						<td bgcolor="#eee"><?= $this->session->nik; ?></td>
					</tr>
					<tr>
						<td>No KK</td>
						<td class="titik">:</td>
						<td ><?= $this->session->no_kk; ?></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url(); ?>mandiri_web/mandiri/1/1" class=""><button type="button" class="btn btn-primary btn-block">PROFIL</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url(); ?>mandiri_web/mandiri/1/2" class=""><button type="button" class="btn btn-primary btn-block">LAYANAN</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url(); ?>mandiri_web/mandiri/1/3" class=""><button type="button" class="btn btn-primary btn-block">LAPOR</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url(); ?>mandiri_web/mandiri/1/4" class=""><button type="button" class="btn btn-primary btn-block">BANTUAN</button></a></h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url('first/logout'); ?>" class=""><button type="button" class="btn btn-danger btn-block">KELUAR</button></a></h4></td>
					</tr>
				</table>
			</div>
		</div>
		<?php if (isset($this->session->lg) && $this->session->lg == 1) { ?>
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-user"></i> Layanan Mandiri</h3><br/>
					Untuk keamanan silahkan ubah kode PIN Anda.
				</div>
				<div class="box-body">
					<h4>Masukkan PIN Baru</h4>
					<form action="<?= site_url('ganti'); ?>" method="post">
						<input name="pin1" type="password" placeholder="PIN" value="" style="margin-left:0px">
						<input name="pin2" type="password" placeholder="Ulangi PIN" value="" style="margin-left:0px">
						<button type="submit" id="but" style="margin-left:0px">Ganti</button>
					</form>
					<?php if ($flash_message) { ?>
						<div id="notification" class='box-header label-danger'><?= $flash_message ?></div>
					<?php } ?>
					<div id="note">
						Silahkan coba login kembali setelah PIN baru disimpan.
					</div>
				</div>
			</div>
		<?php } else if (isset($this->session->lg) && $this->session->lg == 2) { ?>
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-user"></i> Layanan Mandiri</h3><br/>
					Untuk keamanan silahkan ubah kode PIN Anda.
				</div>
				<div class="box-body">
						<div id="note">
							PIN Baru berhasil Disimpan!
						</div>
				</div>
			</div>
			<?php unset($this->session->lg);
		}
	}
?>

<script type="text/javascript">
	$('document').ready(function() {
		$('#notification').delay(4000).fadeOut();
	});
</script>
