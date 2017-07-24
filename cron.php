<?php
include_once('conf/config.inc.php');
$db_obj = new validation();
$dataRes = $db_obj->get_results("select * from ".TAB_PREFIX."email where status='0' limit 0,1");

foreach($dataRes as $dataRe)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "whmcsmail.go4hosting.in";
    $mail->Username = "support@go4hosting.com";
    $mail->Password = "OKI87%$#DD%^";
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
    );  
    $mail->SetFrom('noreply@gstkeeper.com', 'GST Keeper');
    $mail->Subject=$dataRe->subject;
    $mail->MsgHTML(html_entity_decode($dataRe->body));
    $mail->setFrom($dataRe->from_send);
    $mail->addAddress($dataRe->to_send);
    $bcc = explode(',',$dataRe->bcc);
    for($x=0;$x<count($bcc);$x++)
    {
            $mail->addAddress($bcc[$x]);
    }
    $mailer->AddBCC("foo@gmail.com", "test");
    /*if(!$mail->send()) 
    {
        echo 'Message was not sent.';
        echo 'Mailer error: ' . $mail->ErrorInfo;
    }
    else 
    {
		$db_obj->update(TAB_PREFIX."email",array('status'=>'1','mail_send_datetime'=>date('Y-m-d H:i:s')),array('id'=>$dataRe->id));
        echo 'Message has been sent.';
    }*/
    $mail->clearAllRecipients();
}