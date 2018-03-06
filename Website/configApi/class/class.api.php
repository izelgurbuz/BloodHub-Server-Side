<?php

class API
{


	function sendMail($email,$name_surname,$message,$subject){


			$toMail = $email;

			$toFullName = $name_surname;

			$mailSubject = $subject;

			$messageContent = $message;

			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = 'mail.cinedia.net';
			$mail->Port = 587;
			$mail->Username = 'no-reply@cinedia.net';
			$mail->Password = 'm02800280c';

			$mail->SetFrom($mail->Username, '[BAIS-ANNC:BILKENT] COK COK ACIL KAN IHTIYACI');
			$mail->AddAddress($toMail, $toFullName);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = $mailSubject;
			$mail->MsgHTML($messageContent);
			$mail->Priority = 1;
			$mail->AddCustomHeader("X-MSMail-Priority: High");
			$mail->AddCustomHeader("Importance: High");
			if(isset($_GET['imageLink']))
				$mail->AddAttachment($_GET['imageLink']);
			if($mail->Send()) {
			    return true;
			    
			} else {
				return $mail->ErrorInfo;
	        	
			}
	}

	function sendSMS($phoneNumber,$name_surname,$bloodtype,$hospitalName){

		$postUrl='http://www.oztekbayi.com/panel/smsgonder1Npost.php';
		$MUSTERINO='26929'; //5 haneli müşteri numarası
		$KULLANICIADI='mustafaculban';
		$SIFRE='m02800280c';       
		$ORGINATOR="SMS TEST";        

		$TUR='Normal';  // Normal yada Turkce
		$ZAMAN='2014-04-07 10:00:00';

		$mesaj1=$hospitalName.'DE YATMAKTA OLAN '.$name_surname.' ADLI HASTA ICIN ACIL '.$bloodtype.' KANA IHTIYAC VARDIR.   BLOODHUB';
		$numara1=$phoneNumber;

		$xmlString='data=<sms>
		<kno>'. $MUSTERINO .'</kno>
		<kulad>'. $KULLANICIADI .'</kulad>
		<sifre>'.$SIFRE .'</sifre>
		<gonderen>'.  $ORGINATOR .'</gonderen>
		<mesaj>'. $mesaj1 .'</mesaj>
		<numaralar>'. $numara1.'</numaralar>
		<tur>'. $TUR .'</tur>
		</sms>';

		// Xml içinde aşağıdaki alanlarıda gönderebilirsiniz.
		//<zaman>'. $ZAMAN.'</zaman> İleri tarih için kullanabilirsiniz

		$Veriler =  $xmlString;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $Veriler);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$response = curl_exec($ch);
		curl_close($ch);
		echo $response;
		echo '<br>';



	}

}













?>