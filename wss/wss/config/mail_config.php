<?php
$mail_service_create = get_item( 'mail_create' );                   //当有新任务到达时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能
$mail_service_update = get_item( 'mail_update' );                   //当任务状态更新时提醒任务负责人(来自谁) "on" 为启用该功能, "off" 为禁用该功能
$mail_service_comment = get_item( 'mail_comment' );                   //当任务有新备注时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能

if($mail_service_create == "on" || $mail_service_update == "on" || $mail_service_comment == "on") {
function wss_post_office($to,$subject = "",$body = ""){

    require_once('mail/class.phpmailer.php');
    include("mail/class.smtp.php"); 
    $mail             = new PHPMailer(); 
    $body             = eregi_replace("[\]",'',$body); 
    $mail->CharSet = get_item( 'mail_charset' );                   //邮件编码格式设置
    $mail->IsSMTP(); 

    $mail->SMTPAuth   = get_item( 'mail_auth' );                  // 启用 SMTP 验证功能
 // $mail->SMTPSecure = "ssl";                 // SSL安全协议
    $mail->Host       = get_item( 'mail_host' );       // SMTP 邮件服务器地址,如:smtp.sina.com
    $mail->Port       = get_item( 'mail_port' );                    // SMTP 邮件服务器的端口号,默认为25
    $mail->Username   = get_item( 'mail_username' );   // 用户名:邮件帐号的用户名,如使用新浪邮箱，请填写完整的邮件地址,如: yourname@sina.com
    $mail->Password   = get_item( 'mail_password' );        // 密码:邮件帐号的密码
    $mail->From = get_item( 'mail_from' );         // 发送邮件的邮箱,如: yourname@sina.com
	$mail->FromName   = get_item( 'mail_fromname' );                 // 邮件发送人的显示名称
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $mailto);

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } 
    }
	}
?>