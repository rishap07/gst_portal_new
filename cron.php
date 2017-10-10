<?php
include_once('conf/config.inc.php');
$db_obj = new validation();
$dataRes = $db_obj->get_results("select * from ".TAB_PREFIX."email where status='0'");

foreach($dataRes as $dataRe)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = "49.50.104.11";
    $mail->Port = 25;
    //$mail->SMTPDebug = 2;

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
	echo PROJECT_ROOT.$dataRe->attachment;
	$mail->AddAttachment(PROJECT_ROOT.$dataRe->attachment);
    $bcc = explode(',',$dataRe->bcc);
    for($x=0;$x<count($bcc);$x++)
    {
            $mail->AddBCC($bcc[$x]);
    }
    $mail->AddBCC("rishap07@gmail.com", "Rishap");
    if(!$mail->send()) 
    {
        echo 'Message was not sent.';
        echo 'Mailer error: ' . $mail->ErrorInfo;
    }
    else 
    {
		$db_obj->update(TAB_PREFIX."email",array('status'=>'1','mail_send_datetime'=>date('Y-m-d H:i:s')),array('id'=>$dataRe->id));
        echo 'Message has been sent.';
    }
    $mail->clearAllRecipients();
}