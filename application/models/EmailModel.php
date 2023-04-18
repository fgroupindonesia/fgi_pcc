<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailModel extends CI_Model {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
	}
	
	private $debugModeHere = true;
	// turn this FALSE to make Email Works
	// turn this TRUE to make Email stop working
	private $disableEmailHere = true;
	private $emailAdministrator = "fgroupindonesia@gmail.com";
	
	// DEBUGMODE TRUE is so PRINTOUT the email content but NO SENDING
	// DEBUGMODE FALSE && DisableEmail is FALSE so it will SEND EMAIL
	// DEBUGMODE TRUE && DisableEmail is TRUE so it will PRINTOUT AND NOT SEND EMAIL
	// DEBUGMODE FALSE && DisableEmail is TRUE so it will NOT SEND EMAIL
	
	public function setDebugMode($n){
		$this->debugModeHere = $n;
	}
	
	public function setEmailMode($n){
		$this->disableEmailHere = !$n;
	}
	
	private function isEmailMode(){
		return !$this->disableEmailHere;
	}
	
	private function isDebugMode(){
		return $this->debugModeHere;
	}
	
	private function getEmailAdministrator(){
		return $this->emailAdministrator;
	}
	
	// status whether it is 'valid' or 'invalid'
	public function generateRespond($statusIn){
		
		$stat = array(
			'status' => $statusIn
		);
		
		return $stat;
	}
	
	private function printOrSendEmail($email, $title, $emailKonten){
		if($this->isDebugMode()){
			
			echo $emailKonten;
		
		} 
		
		if($this->isEmailMode()){
			$this->sendEmail($email, $title, $emailKonten);	
		}
		
	}
	
	// this is for CLIENT
	public function success_purchase_serial($lang, $fullname, $serial_number, $purchase_date, $email){
	
		if($lang=='en'){
		
			$title = "Serial Number Purchased";
			$lokasiTemplate = 'template/en/success_purchase_serial';
		}else{
			// indonesia
			$title = "Pembelian Serial Number";
			$lokasiTemplate = 'template/id/success_purchase_serial';
		}
		
		$tglBaru = $this->konversiTanggalEnglishToIndonesia($purchase_date);
		
		$dataArray = array(
				'fullname' 		=> $fullname,
				'serial_number' => $serial_number,
				'purchase_date' => $purchase_date
			);
		
		$emailKonten = $this->load->view($lokasiTemplate, $dataArray, TRUE);
		
		$this->printOrSendEmail($email, $title, $emailKonten);
		
		return true;
	}
	
	// this is for CLIENT
	public function success_account_created($lang, $fullname, $created_date, $email, $token){
	
		$linkActivation = base_url() . "activation/user?token=". $token;
	
		if($lang=='en'){
		
			$title = "Account Created";
			$lokasiTemplate = 'template/en/success_account_created';
		}else{
			// indonesia
			$title = "Akun Berhasil Dibuat";
			$lokasiTemplate = 'template/id/success_account_created';
			$created_date = $this->konversiTanggalEnglishToIndonesia($created_date);
		}
		
		$dataArray = array(
				'fullname' 		=> $fullname,
				'created_date' 	=> $created_date,
				'link'			=> $linkActivation,
				'email'			=> $email
		);
		
		$emailKonten = $this->load->view($lokasiTemplate, $dataArray, TRUE);
		
		$this->printOrSendEmail($email, $title, $emailKonten);
		
		return true;
	}
	
	// this is for ADMIN
	public function new_user_registered($fullname, $email, $phone, $created_date){
	
			// indonesia
			$title = "New Registered User";
			$lokasiTemplate = 'template/admin/new_user_registered';
		
		$dataArray = array(
				'fullname' 		=> $fullname,
				'created_date' 	=> $created_date,
				'phone'			=> $phone,
				'email'			=> $email
		);
		
		$emailKonten = $this->load->view($lokasiTemplate, $dataArray, TRUE);
		
		$this->printOrSendEmail($email, $title, $emailKonten);
		
		return true;
	}
	
	
	private function konversiHariEnglishToIndonesia($hari){
		
		$harina = $hari;
		
		$bEnglish = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday',
		'Friday', 'Saturday');
		
		$bIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis',
		'Jumat','Sabtu');
		
		if(in_array($hari, $bEnglish)){
			$nomer = array_search($hari, $bEnglish);
			$harina = $bIndonesia[$nomer];
		}
		
		return $harina;
		
	}
	
	private function konversiBulanEnglishToIndonesia($bulan){
		
		$bulanna = $bulan;
		
		$bEnglish = array('January', 'February', 'March', 'April','May', 'June',
		'July', 'August', 'September', 'October', 'November','December');
		
		$bIndonesia = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
		'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		
		if(in_array($bulan, $bEnglish)){
			$nomer = array_search($bulan, $bEnglish);
			$bulanna = $bIndonesia[$nomer];
		}
		
		return $bulanna;
		
	}
	
	// tanggal coming should be in format:
	// Monday, 11 March 2023 13:00
	private function konversiTanggalEnglishToIndonesia($tgl){
		
		// month in fullname
		$mon = date('F');
		// day in fullname
		$day = date('l');
		
		$monIndo = $this->konversiBulanEnglishToIndonesia($mon);
		$dayIndo = $this->konversiHariEnglishToIndonesia($day);
		
		$tglBaru = str_replace($day, $dayIndo, $tgl);
		$tglBaru = str_replace($mon, $monIndo, $tglBaru);
		
		return $tglBaru;
		
	}
	
	
	public function email_reset_pass($email, $username, $token){
		
		$title = "Reset Password Akun";
		
		$dateNa = $this->konversiTanggalEnglishToIndonesia(date('l, d-F-Y H:i:s'));
		
		$dataArray = array(
			'username' => $username,
			'token' => $token,
			'date' => $dateNa
		);
		
		$emailKonten = $this->load->view('template/_email_reset_password', $dataArray, TRUE);

		$this->printOrSendEmail($email, $title, $emailKonten);
		
		
		return true;
	}
	
	public function sendEmail($dest, $judul, $htmlkonten){
		
		//valid sampe 
		/*
		$config = array(
			'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
			'smtp_host' => 'ssl://smtp.elasticemail.com', 
			'smtp_port' => 2525,
			'smtp_user' => 'admin@rumahterapiherbal.web.id',
			'smtp_pass' => '15A849C22480B2E07306C1CCCEE80A3EDA90',
			'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
			'mailtype' => 'html', //plaintext 'text' mails or 'html'
			'smtp_timeout' => '7', //in seconds
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);

		$this->load->library('email', $config);
		$this->email->from('admin@rumahterapiherbal.web.id', 'RTH - Rumah Terapi Herbal');
		$this->email->to($dest);
		$this->email->subject($judul);
		$this->email->message($htmlkonten);
		$this->email->send();*/
		
		$this->sendEmailNewer($dest, $judul, $htmlkonten);
		
		// newer code for API Sending 96FF20FBE2098FE878C5E062070603AE92EF2B90837E7A7F1DB5A98145C2CD6304BD5E647CD053C0B30FEE9B9EA4883A
	}
	
	public function sendEmailNewer($dest, $judul, $htmlkonten){
		
		$keyAPINum = "96FF20FBE2098FE878C5E062070603AE92EF2B90837E7A7F1DB5A98145C2CD6304BD5E647CD053C0B30FEE9B9EA4883A";
		
		$url = 'https://api.elasticemail.com/v2/email/send';

		try{
				$post = array('from' => 'info@fgroupindonesia.com',
				'fromName' => 'FGroupIndonesia',
				'apikey' => $keyAPINum,
				'subject' => $judul,
				'to' => $dest,
				'bodyHtml' => $htmlkonten,
				'isTransactional' => false);
				
				$ch = curl_init();
				curl_setopt_array($ch, array(
					CURLOPT_URL => $url,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $post,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HEADER => false,
					CURLOPT_SSL_VERIFYPEER => false
				));
				
				$result=curl_exec ($ch);
				curl_close ($ch);
				
				//echo $result;	
		}
		catch(Exception $ex){
			echo $ex->getMessage();
		}
		
	}
	
	
}