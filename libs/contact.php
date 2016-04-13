<?php

/* For setting up gmail account http://stackoverflow.com/questions/21937586/phpmailer-smtp-error-password-command-failed-when-send-mail-from-my-server */

session_start();

require_once '../phpmailer/PHPMailerAutoload.php';

$errors = array();

if(isset($_POST['name'], $_POST['email'], $_POST['message'])){
	$fields = array(
		'name' => $_POST['name'],
		'email' => $_POST['email'],
		'message' => $_POST['message']
	);
	
	foreach($fields as $field => $data){
		if(empty($data)){
			$errors[] = 'The '.$field.' field is required';
		}
	}
	
	if(empty($errors)){
		$m= new PHPMailer;
		$m->isSMTP();
		$m->SMTPAuth = true;
		
		$m->Host = 'smtp.gmail.com';
		$m->Username = 'youremail@gmail.com';
		$m->Password = 'yourpass';
		$m->SMTPSecure = 'tls';
		$m->Port = 587;
		
		$m->isHTML();
		
		$m->Subject = 'Contact form submitted';
		$m->Body = 'From: '.$fields['name'].' ('.$fields['email'].')<p>'.$fields['message'].'</p>';
		
		$m->FromName = 'Contact';
		
		/*$m->AddReplyTo($fields['email'], $fields['name']);*/
		
		$m->AddAddress('youremail@gmail.com', 'My Name');
		
		if($m->send()){
			$errors[] = 'Email Sent Successfully';
			unset($fields);
		} 
		else {
			$errors[] = 'Something Went Wrong';
		}
	}
} 
else{
	$errors[] = 'something went wrong';
}

$_SESSION['errors'] = $errors;
$_SESSION['fields'] = $fields;

header('Location: index.php');

?>
