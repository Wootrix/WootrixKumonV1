<?php

function sendAndroidPush($deviceToken, $msg,$badge,$check ) {
	//$registrationIDs = array($deviceToken);
    
	if (is_array($deviceToken)) {

		$registrationIDs = $deviceToken;
	} else {
		$registrationIDs = array($deviceToken);
	}
	
	// Message to be sent
	$message = $msg;
	//echo $message;die;
	//Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';

	$fields = array(
		'registration_ids' => $registrationIDs,
		'data' => array("message" => $message, "type" => "1",'badge'=>$badge,'check'=>$check),
	);
    //key=AIzaSyBaK2WrdwjWB7mli7f6Tb5xv7eG2Nv7EQ4
	$headers = array(
		'Authorization: key=AIzaSyAJDF-R5Ji90kxlGALEg6dlpWcmzrbKZnM',
		'Content-Type: application/json'
	);
	//AIzaSyACvXL6SzRvoRjk_qsOD_aLhuXatBvnlYQ
	//Open connection
	$ch = curl_init();

	//Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
	//Execute post
	$result = curl_exec($ch);
	//return $result;
	//Close connection
	curl_close($ch);
    
	//echo $result ."<br/>";
}

function sendIphonePush($deviceToken,$msg,$badge,$check) {

	//echo $deviceToken;
	//$apnsHost = 'gateway.sandbox.push.apple.com';	
	$apnsHost = 'gateway.push.apple.com';
	$apnsPort = '2195';
	$apnsCert = 'ck.pem';
	//$apnsCert = 'ck1.pem';
	$passPhrase = '123456';
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
	$apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
	if ($apnsConnection == false) {
		echo "Failed to connect {$error} {$errorString}\n";
		//print "Failed to connect {$error} {$errorString}\n";
		//error_log($error.chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
		return;
	} else {
			//echo "Connection successful";	
		//error_log('Connection successful', 3, "/mnt/srv/MOOVWORKER/push-errors.log");
	}
	$message = $msg;
	$payload['aps'] = array('alert' => $message, 'sound' => 'default','badge'=>(int)$badge,'check'=>$check);
	$payload = json_encode($payload);
	//print_r($payload);
	//$deviceToken = "dfe587d02a99d57fa7d785c1901409d408dfa920fa90890fbe3fed1fc090c7ee";
	//$deviceToken = $deviceToken;//"dfe587d02a99d57fa7d785c1901409d408dfa920fa90890fbe3fed1fc090c7ee";

	try {

		if ($message != "") {
			//echo $deviceToken;
			//echo $message;
			$apnsMessage = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;
			$fwrite = fwrite($apnsConnection, $apnsMessage);
			if ($fwrite) {
				//echo "true";
				//error_log($fwrite.chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
			} else {
				//echo "false";
			}
		}
	} catch (Exception $e) {
		//echo 'Caught exception: '.  $e->getMessage(). "\n";
		//error_log($e->getMessage().chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
	}
}

function generatePush($deviceType, $deviceToken, $message) {

	if ($deviceType == 'android') {

		sendAndroidPush($deviceToken, $message);
	} else if ($deviceType == 'iphone') {

		sendPush($deviceToken, $message);
	} else {

		/*
		 * do nothing
		 */
	}
}

function sendDriverPush($deviceToken, $msg) {

	//echo $deviceToken;
	//$apnsHost = 'gateway.sandbox.push.apple.com';	
	$apnsHost = 'gateway.push.apple.com';
	$apnsPort = '2195';
	$apnsCert = 'driver_dis.pem';
	$passPhrase = '';
	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
	$apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
	if ($apnsConnection == false) {
		//echo "Failed to connect {$error} {$errorString}\n";
		//print "Failed to connect {$error} {$errorString}\n";
		error_log($error . chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
		return;
	} else {
		//	echo "Connection successful";	
		error_log('Connection successful', 3, "/mnt/srv/MOOVWORKER/push-errors.log");
	}
	$message = $msg;
	$payload['aps'] = array('alert' => $message, 'sound' => 'default');
	$payload = json_encode($payload);
	//$deviceToken = "dfe587d02a99d57fa7d785c1901409d408dfa920fa90890fbe3fed1fc090c7ee";
	//$deviceToken = $deviceToken;//"dfe587d02a99d57fa7d785c1901409d408dfa920fa90890fbe3fed1fc090c7ee";

	try {

		if ($message != "") {
			//echo $deviceToken;
			//echo $message;
			$apnsMessage = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;
			$fwrite = fwrite($apnsConnection, $apnsMessage);
			if ($fwrite) {
				//echo "true";
				error_log($fwrite . chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
			} else {
				//echo "false";
			}
		}
	} catch (Exception $e) {
		//echo 'Caught exception: '.  $e->getMessage(). "\n";
		error_log($e->getMessage() . chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
	}
}

function sendbulkIphonePush($deviceToken,$msg,$check) {

    //print_r($deviceToken);
    
    //echo count($deviceToken);
    $devices = array_chunk($deviceToken, 40);
    
    echo count($devices)."</br>";
    //echo "<pre>";
    //var_dump($devices);
   // die;
   
    
   // array('device_token'=>'', 'badge'=>'');
	//echo $deviceToken;
	//$apnsHost = 'gateway.sandbox.push.apple.com';	
	$apnsHost = 'gateway.push.apple.com';
	$apnsPort = '2195';
	$apnsCert = 'ck.pem';
	//$apnsCert = 'ck1.pem';
	$passPhrase = '123456';
    $i=0;
   for($cnt=0; $cnt<count($devices); $cnt++) { 
       
      $deviceArray = array_values($devices[$cnt]);
       
       echo count($deviceArray);
       echo "<br/>";
       
      echo var_dump($deviceArray);
       
       echo "<br/>";
       echo "<br/>";
       
            $streamContext = stream_context_create();
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
            $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            if ($apnsConnection == false) {
                echo "Failed to connect {$error} {$errorString}\n";
                //print "Failed to connect {$error} {$errorString}\n";
                //error_log($error.chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
                //return;
            } else {
                    echo "Connection successful";	
                //error_log('Connection successful', 3, "/mnt/srv/MOOVWORKER/push-errors.log");
            }
            $message = $msg;
            try {

            foreach ($deviceArray as $device) {                
                //echo "<br/>";
                $payload = array();
                $badge= (int)$device['badge'];
                $payload['aps'] = array('alert' => $message, 'sound' => 'default','badge'=>$badge,'check'=>$check);
                $payload = json_encode($payload);
                
              //  echo "<br/><br/>";
                $apnsMessage = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $device['device_token'])) . pack("n", strlen($payload)) . $payload;

                //echo $apnsMessage;
               // echo "<br/><br/>";
            
                $fwrite = fwrite($apnsConnection, $apnsMessage);
                if ($fwrite) {
                    echo "true";
                    echo "<br/>";
                } else {
                    echo "false";
                    echo "<br/>";
                }
            echo $i++;
            
            
            
        } 
        //echo 'test';
		//echo "<br/>";
	} catch (Exception $e) {
		echo 'Caught exception: '.  $e->getMessage(). "\n";
		//error_log($e->getMessage().chr(13), 3, "/mnt/srv/MOOVWORKER/push-errors.log");
	}
    
   }
}

?>