<?php
$mail_service_create = get_item( 'mail_create' );                   //���������񵽴�ʱ��������ִ���� "on" Ϊ���øù���, "off" Ϊ���øù���
$mail_service_update = get_item( 'mail_update' );                   //������״̬����ʱ������������(����˭) "on" Ϊ���øù���, "off" Ϊ���øù���
$mail_service_comment = get_item( 'mail_comment' );                   //���������±�עʱ��������ִ���� "on" Ϊ���øù���, "off" Ϊ���øù���

if($mail_service_create == "on" || $mail_service_update == "on" || $mail_service_comment == "on") {
function wss_post_office($to,$subject = "",$body = ""){

    require_once('mail/class.phpmailer.php');
    include("mail/class.smtp.php"); 
    $mail             = new PHPMailer(); 
    $body             = eregi_replace("[\]",'',$body); 
    $mail->CharSet = get_item( 'mail_charset' );                   //�ʼ������ʽ����
    $mail->IsSMTP(); 

    $mail->SMTPAuth   = get_item( 'mail_auth' );                  // ���� SMTP ��֤����
 // $mail->SMTPSecure = "ssl";                 // SSL��ȫЭ��
    $mail->Host       = get_item( 'mail_host' );       // SMTP �ʼ���������ַ,��:smtp.sina.com
    $mail->Port       = get_item( 'mail_port' );                    // SMTP �ʼ��������Ķ˿ں�,Ĭ��Ϊ25
    $mail->Username   = get_item( 'mail_username' );   // �û���:�ʼ��ʺŵ��û���,��ʹ���������䣬����д�������ʼ���ַ,��: yourname@sina.com
    $mail->Password   = get_item( 'mail_password' );        // ����:�ʼ��ʺŵ�����
    $mail->From = get_item( 'mail_from' );         // �����ʼ�������,��: yourname@sina.com
	$mail->FromName   = get_item( 'mail_fromname' );                 // �ʼ������˵���ʾ����
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