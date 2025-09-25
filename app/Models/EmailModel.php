<?php

namespace App\Models;

use CodeIgniter\Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailModel extends Model
{
    public function sendEmail($name, $to, $subject, $bodyemail){
        require 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'info@crowdwings.net';
        $mail->Password = 'Vwebx#@!12';
        $mail->setFrom('info@crowdwings.net', 'Crowd Wings');
        $mail->addReplyTo('info@crowdwings.net', 'Crowd Wings');
        $mail->addAddress($to);
        
    
        $mail->Subject = $subject;
        

        $message=$this->template1($bodyemail, $name);

        $mail->msgHTML($message);

        //send the message, check for errors
        if (!$mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message sent!';
        }
    }
    
    public function sendEmail2($name, $to, $subject, $bodyemail){
        require 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'info@crowdwings.net';
        $mail->Password = 'Vwebx#@!12';
        $mail->setFrom('info@crowdwings.net', 'Crowd Wings');
        $mail->addReplyTo('info@crowdwings.net', 'Crowd Wings');
        $mail->addAddress($to);
        
    
        $mail->Subject = $subject;
        

        $message=$this->template2($bodyemail, $name);

        $mail->msgHTML($message);

        //send the message, check for errors
        if (!$mail->send()) {
            //secho 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message sent!';
        }
    }


    private function template1($bodyHTML, $name=''){
		if(!isset($name)){
			$name='Team';
		}
		
		$message = "<table style='width:100%; border:1px solid #ccc; padding:10px;'>
			<tr style='text-align:center;'>
				<td><img src='https://web.crowdwings.net/assets/img/mobileux/image-2.png' style='max-width:170px;'><br /><img src='https://web.crowdwings.net/cu.gif' style='max-width:120px;'></td>
			</tr>
			<tr>
			<td>
			<table style='width:100%; background: #f7f7f7; padding:10px;'>
			<tr>
			 <td>
				<p>Dear <b>$name</b>,</p>
                <p>Thankyou for the registration, You are now the part of Crowd Wings. Below id the details of your ID.</p>
				<p>$bodyHTML<br /><br /></p>
			<tr style='text-align:left;'>

				<p>Regards,<br />
				Crowd Wings Team
				</p>
			 </td>
			</tr>
			
			</table>
			</td>
			<tr>
		</table>";
		return $message;
	}
	
	private function template2($bodyHTML, $name=''){
		if(!isset($name)){
			$name='Team';
		}
		
		$message = "<table style='width:100%; border:1px solid #ccc; padding:10px;'>
			<tr style='text-align:center; widht'>
				<td><img src='https://web.crowdwings.net/assets/img/mobileux/image-2.png' style='max-width:120px;'></td>
			</tr>
			<tr>
			<td>
			<table style='width:100%; background: #f7f7f7; padding:10px;'>
			<tr>
			 <td>
				
				<p>$bodyHTML<br /><br /></p>

				<p>Regards,<br />
				Crowd Wings Team
				</p>
			 </td>
			</tr>
			
			</table>
			</td>
			<tr>
		</table>";
		return $message;
	}
}
