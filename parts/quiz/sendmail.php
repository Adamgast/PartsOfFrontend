<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require 'phpmailer/src/Exception.php';
	require 'phpmailer/src/PHPMailer.php';
	require 'phpmailer/src/SMTP.php';

	//Тело письма
	$body = '<h1>Встречайте супер письмо!</h1>';
    $file = $_FILES['file'];
	$c = true;
	// Формирование самого письма
	foreach ( $_POST as $key => $value ) {
		if ( $value != "" && $key != "form" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$body .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
	$body = "<table style='width: 100%;'>$body</table>";

	// Настройки PHPMailer
	$mail = new PHPMailer(true);
	$mail->CharSet = 'UTF-8';
	$mail->setLanguage('ru', 'phpmailer/language/');
	$mail->IsHTML(true);
	$mail->isSMTP();
	$mail->SMTPAuth   = true;

    // Настройки вашей почты
    $mail->Host       = 'smtp.gmail.com'; // SMTP сервера вашей почты
    $mail->Username   = 'agast6335@gmail.com'; // Логин на почте
    $mail->Password   = 'mvekbzbwcjwlsaoe'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

	//От кого письмо
	$mail->setFrom('agast6335@gmail.com', 'Заявка с вашего сайта'); // Указать нужный E-mail
	//Кому отправить
	$mail->addAddress('agast6335@gmail.com'); // Указать нужный E-mail
	
	//Тема письма
	$mail->Subject = 'Привет! Это с письмо с твоего QiuZ!';

     //Прикрипление файлов к письму
      if (!empty($file['name'][0])) {
        for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
          $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
          $filename = $file['name'][$ct];
          if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
             $mail->addAttachment($uploadfile, $filename);
             $rfile[] = "Файл $filename прикреплён";
          } else {
             $rfile[] = "Не удалось прикрепить файл $filename";
          }
        }
      }

	$mail->Body = $body;

	//Отправляем
	if (!$mail->send()) {
		$message = 'Ошибка';
	} else {
		$message = 'Данные отправлены!';
	}

	$response = ['message' => $message];

	header('Content-type: application/json');
	echo json_encode($response);
?>