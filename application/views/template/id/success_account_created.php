<hr>
<h2>Akun Berhasil Dibuat</h2>
<hr>
<br/>
<p>Hello, <?= $fullname; ?> !<br/>
Baru saja kamu telah membuat akun untuk Parent Control™ dengan akses berikut : <br/>
Email : <b><?= $email; ?></b> <br/>
yang dibuat pada <b><?= $created_date; ?>.</b>  <br/>
<br/>
Untuk dapat segera menggunakan aplikasi ini, kamu harus melakukan aktifasi dengan cara <b>Klik link dibawah ini </b> :
<br/>
<a href="<?= $link; ?>">Link Aktifasi</a>. Gunakan aktifasi ini sebelum 24 jam berakhir. <br/>
<br/>
Salam hangat, <br/>
<br/>
<br/>
Automated System. <br/> 
Parent Control ™. <br/>
<br/>
</p>

<?php $this->load->view('template/footer.php'); ?>
