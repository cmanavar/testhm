<?php 		//print_r($_REQUEST);exit;
		$msg=$_REQUEST['msg'];
         	$to= $_REQUEST['tomail'];
                $name= $_REQUEST['name'];
                $subject= $_REQUEST['subject'];
                $attachment = $_REQUEST['attachment'];
                $result = sendemail($msg,$to,$name,$subject,$attachment); 
              
         	  if($result){
			echo json_encode(array('success' => $result,'status'=>'1','msg'=>$msg));
	        }
	       	exit;


function sendemail($msg,$to,$name=null,$subject=null,$attachment= null) {
   //echo 'http://localhost/scanmax/webroot/'.$attachment;exit;
		require 'vendor/autoload.php';
                require 'lib/SendGrid.php';                 
                $sendgrid = new SendGrid("Lanoversolutions", "lS$$$333", array("turn_off_ssl_verification" => true));
                $email = new SendGrid\Email();
                $email->addTo($to,$name)->
                       setFrom('Arpit@lanover.com')-> 
                       setFromName('Arpit')->
                       addAttachment('D:\xampp\htdocs\scanmax\webroot'."/".$attachment)->
                       setSubject($subject)->                       
                       setHtml($msg)->                      
                       addHeader('X-Sent-Using', 'SendGrid-API')->
                       addHeader('X-Transport', 'web');
                	
                    try {
                	
                    $sendgrid->send($email);
                    
                } catch(\SendGrid\Exception $e) {
                	
                }
                
}        
        ?>
    