<?php

$msg = $_REQUEST['msg'];
$to = $_REQUEST['tomail'];
$cc = isset($_REQUEST['cc_email']) && $_REQUEST['cc_email'] != '' ? $_REQUEST['cc_email'] : '';
$name = $_REQUEST['from_name'];
$subject = $_REQUEST['subject'];
$toemail = [];
$toemail = (isset($to) && $to != "") ? explode(',', $to) : "";
$ccEmail = array();
$ccEmail = (isset($cc) && $cc != "") ? explode(',', $cc) : "";

$result = sendemail($msg, $toemail, $ccEmail, $name, $subject);
if ($result) {
    echo json_encode(array('success' => $result, 'status' => '1', 'msg' => $msg));
}

function sendemail($msg, $to, $ccEmail, $name = null, $subject = null) {
    $username = base64_decode('dW5jb2Rl');
    $password = base64_decode('dU4kJCQzMzM=');
    require 'vendor/autoload.php';
    require 'lib/SendGrid.php';
    $sendgrid = new SendGrid($username, $password, array("turn_off_ssl_verification" => true));
    $email = new SendGrid\Email();
    $email->addTo($to)->
            addCc($ccEmail)->
            setFrom('uncodelab@gmail.com')->
            setFromName($name)->
            setSubject($subject)->
            setHtml($msg)->
            addHeader('X-Sent-Using', 'SendGrid-API')->
            addHeader('X-Transport', 'web');    
    try {

        $e = $sendgrid->send($email);
    } catch (\SendGrid\Exception $e) {
        $e;
    }
}

?>