<hr>
<h2>Account Created</h2>
<hr>
<br/>
<p>Hello, <?= $fullname; ?> !<br/>
You just create an account for Parent Control™ with the following access : <br/>
Email : <b><?= $email; ?></b> <br/>
at <b><?= $created_date; ?>.</b>  <br/>
<br/>
To start using the app, you must activate it by <b>Clicking the link below</b> :
<br/>
<a href="<?= $link; ?>">Activation Link</a> use it before 24 hours ended. <br/>
<br/>
Regards, <br/>
<br/>
<br/>
Automated System. <br/> 
Parent Control ™. <br/>
<br/>
</p>

<?php $this->load->view('template/footer.php'); ?>
