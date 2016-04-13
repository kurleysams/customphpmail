<?php

require_once '../helpers/security.php';
require_once '../phpmailer/PHPMailerAutoload.php';
$errors = array();
?>
<?php 

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
		$m->Username = 'yourmail@gmail.com';
		$m->Password = 'yourpass';
		$m->SMTPSecure = 'tls';
		$m->Port = 587;
		
		$m->isHTML();
		
		$m->Subject = 'Contact form submitted';
		$m->Body = 'From: '.$fields['name'].' ('.$fields['email'].')<p>'.$fields['message'].'</p>';
		
		$m->FromName = 'Contact';
		
		/*$m->AddReplyTo($fields['email'], $fields['name']);*/
		
		$m->AddAddress('yourmail@gmail.com', 'My Name');
		
		if($m->send()){
			$errors[] = 'Email Sent Successfully';
			header( "refresh:5; url=index.php" ); /* Redirect on same page after a delay to show message sent greetings*/
			unset($fields);
			unset($_POST);
		} 
		else {
			$errors[] = 'Something Went Wrong';
		}
	}
} 
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PHPmailer</title>
		<link rel="stylesheet" href="../css/style.css">
	</head>
	<body>
		<div class="contact">
			<?php if(!empty($errors)): ?>
				<div class="panel">
					<?php echo "<ul class='errorinfo'><li>" . implode("</li><li>", $errors) . "</li></ul>"; ?>
				</div>
			<?php endif; ?>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
				<label>
					Your Name*
					<input type="text" name="name" autocomplete="off"<?php $tr='border: 2px solid red;'; echo isset($fields['name']) ? 'value="'.e($fields['name']).'"' : '' ?>>
				</label>
				<label>
					Your Email*
					<input type="text" name="email" autocomplete="off"<?php echo isset($fields['email']) ? 'value="'.e($fields['email']).'"' : '' ?>>
				</label>
				<label>
					Your Message*
					<textarea name="message" rows="8"><?php echo isset($fields['message']) ? e($fields['message']) : '' ?></textarea>
				</label>
				<input type="submit" value="Send">
				<p class="muted">* means a required field</p>
			</form>
		</div>
	</body>
</html>
