<?php if(isset($_GET['beautify'])) {?><link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/xcode.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
 <script>hljs.initHighlightingOnLoad();</script> <?php }?>
<?php

require_once('../configApi/functions.php');
require_once('../configApi/class.api.php');

require_once '../configApi/dbFunc.php';
require_once '../configApi/TcKimlikNoSorgula.php';
require_once '../configApi/YabanciKimlikNoDogrula.php';
require_once '../configApi/class.phpmailer.php';
require_once('../configApi/PushNotifications.php');

$api = new API;
$db = new DB_Functions();

if((isset($_GET['secretCode']) && $db->isInOauthIDs($_GET['secretCode']) )||(isset($_POST['secretCode']) && $db->isInOauthIDs($_POST['secretCode']) )){
	
	 
	 $requestParts = explode('/',$_GET['request']);
	 $func = $requestParts[0];

	// json response array
	$response = array("error" => "FALSE");



	if($func == "login"){
		if ((isset($_GET['email']) && isset($_GET['password'])) || (isset($_POST['email']) && isset($_POST['password']))) {
	 
	 		$email = '';
	 		$password = '';
		    // receiving the post params
		    if(isset($_GET['email'])){
		    	$email = $_GET['email'];
		    	$password = $_GET['password'];
			}
			elseif(isset($_POST['email'])){
				$email = $_POST['email'];
		    	$password = $_POST['password'];
			}
		 
		    // get the user by email and password
		    $user = $db->getUserByEmailAndPassword($email, $password);
		 
		    if ($user != false) {

		        // use is found
		        $response["error"] = "FALSE";
		        $response["user"]["id"] = $user["id"];
		        $response["user"]["username"] = $user["username"];
		        $response["user"]["firstname"] = $user["firstname"];
		        $response["user"]["surname"] = $user["surname"];
		        $response["user"]["email"] = $user["email"];
		        $response["user"]["bloodType"] = $user["bloodType"];
		        $response["user"]["birthdate"] = $user["birthdate"];
		        $response["user"]["address"] = $user["address"];
		        $response["user"]["telephone"] = $user["telephone"];
		        $response["user"]["available"] = $user["available"];
		        $response["user"]["last_login_ip"] = $user["last_login_ip"];
		        $response["user"]["last_login_date"] = $user["last_login_date"];
		        $response["user"]["last_login_time"] = $user["last_login_time"];
		        toXML($response);
		    } 
		    else {
		        // user is not found with the credentials
		        $response["error"] = "TRUE";
		        $response["error_msg"] = "Login credentials are wrong. Please try again!";
		        toXML($response);
		    }
		} 
		else {
	    // required post params is missing
		    $response["error"] = "TRUE";
		    $response["error_msg"] = "Required parameters email or password is missing!";
		    toXML($response);
		}

	}
	elseif ($func == "register") {
		
		if (isset($_GET['username']) && isset($_GET['firstname']) && isset($_GET['surname'])&& isset($_GET['password'])&& isset($_GET['email'])&& isset($_GET['identityNum']) && isset($_GET['bloodType']) && isset($_GET['birthdate']) && isset($_GET['address'])  && isset($_GET['telephone'])) {
	 
		    // receiving the post params
		    $username = $_GET['username'];
		    $firstname = $_GET['firstname'];
		    $surname = $_GET['surname'];
		    $password = $_GET['password'];
		    $email = $_GET['email'];
		    $identityNum = $_GET['identityNum'];
		    $bloodType = $_GET['bloodType'];
		    $birthdate = $_GET['birthdate'];
		    $address = $_GET['address'];
		    $telephone = $_GET['telephone'];
		    
		    if (!TcKimlikNoSorgula::tcKimlikNo($identityNum)->ad(($firstname))->soyad(($surname))->dogumYili(substr($birthdate, -4))->sorgula() /*  ||  there will be yabanci kimlik no check*/ ) {
		         $response["errorr"] = TRUE;
		        $response["error_msg"] = "This identitiy Number is fake";
		        toXML($response);
		    }
		    // check if user is already existed with the same email
		    else if ($db->isUserExisted($email)) {
		        // user already existed
		        $response["errorr"] = TRUE;
		        $response["error_msg"] = "User already existed with email: " . $email. "and username: " .$username;
		        toXML($response);
			} 
		    else {
		        // create a new user
		        $user = $db->storeUser($username, $firstname, $surname, $password, $email, $identityNum, $bloodType, $birthdate, $address, $telephone);
		        if ($user) {
		            // user stored successfully
		            $response["errorr"] = FALSE;
		            $response["user"]["id"] = $user["id"];
		            $response["user"]["username"] = $user["username"];
		            $response["user"]["firstname"] = $user["firstname"];
		            $response["user"]["surname"] = $user["surname"];
		            $response["user"]["email"] = $user["email"];
		            $response["user"]["bloodType"] = $user["bloodType"];
		            $response["user"]["birthdate"] = $user["birthdate"];
		            $response["user"]["address"] = $user["address"];
		            $response["user"]["telephone"] = $user["telephone"];
		            $response["user"]["available"] = $user["available"];
		            toXML($response);
		        } 
		        else {
		            // user failed to store
		            $response["errorr"] = TRUE;
		            $response["error_msg"] = "Unknown error occurred in registration!";
		            toXML($response);
		        }
		    }
		} 
		else {
		    $response["errorr"] = TRUE;
		    $response["error_msg"] = "Required parameters are missing!";
		    toXML($response);
		}

	}

	elseif ($func == "sendMail") {
		$response["error"] = FALSE;
		
		if (isset($_GET['email']) && isset($_GET['name_surname']) && isset($_GET['message']) && isset($_GET['subject'])){
			$api->sendMail($_GET['email'],$_GET['name_surname'],$_GET['message'],$_GET['subject']);
			
		}
		else{

			$response["error"] = TRUE;
	        $response["error_msg"] = "Required parameters are missing!";
	        $response['req_params']['email'] = "mail will be sent to this mail";
	        $response['req_params']['name_surname'] = "name and the surname of the receiver";
	        $response['req_params']['message'] = "message to be sent";
	        $response['req_params']['subject'] = "subject of the mail that will be sent";
	        
	        toXML($response);
		}

	}

	elseif ($func == "sendNotification") {
		
		$response["error"] = FALSE;
		if (isset($_GET['devID']) && isset($_GET['message']) && isset($_GET['subject']) && isset($_GET['deviceType'])){

			$msg_payload = array (
				'mtitle' => $_GET['subject'],
				'mdesc' => $_GET['message'],
			);


			switch ($_GET['deviceType']) {
				case 'android':
					PushNotifications::android($msg_payload, $_GET['devID']);
					$response["error"] = FALSE;
			    	$response['success'] = "Push Message has been sent succesfully";
			    	toXML($response);
					break;
				case 'ios':
					PushNotifications::android($msg_payload, $_GET['devID']);
					$response["error"] = FALSE;
			    	$response['success'] = "Push Message has been sent succesfully";
			    	toXML($response);
					break;				
				default:

					break;
			}

		}
		else{

			$response["error"] = TRUE;
	        $response["error_msg"] = "Required parameters are missing!";
	        toXML($response);

		}

	}

	elseif ($func == "sendSMS") {
		
		if (isset($_GET['phone'])){


			$postUrl='http://www.oztekbayi.com/panel/smsgonder1Npost.php';
			$MUSTERINO='26929'; //5 haneli müşteri numarası
			$KULLANICIADI='mustafaculban';
			$SIFRE='m02800280c';       
			$ORGINATOR="SMS TEST";        

			$TUR='Normal';  // Normal yada Turkce
			$ZAMAN='2014-04-07 10:00:00';

			$mesaj1='ATATURK HASTANESINDE YATMAKTA OLAN HASTA ICIN ACIL A+ KANA IHTIYAC VARDIR.   BLOODHUB';
			$numara1=$_GET['phone'];

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

		}
		else{

			$response["error"] = TRUE;
	        $response["error_msg"] = "Required parameters are missing!";
	        toXML($response);


		}

	}

	elseif ($func == "sendBloodRequest") {

		if ((isset($_GET['notificationType']) && isset($_GET['bloodType']) && isset($_GET['hospitalName']) && isset($_GET['name_surname']) && isset($_GET['senderID'])) || (isset($_POST['notificationType']) && isset($_POST['bloodType']) && isset($_POST['hospitalName']) && isset($_POST['name_surname']) && isset($_POST['senderID']))){
				
				$notificationType = isset($_GET['notificationType']) ? $_GET['notificationType'] : $_POST['notificationType'];
				$bloodType = isset($_GET['bloodType']) ? $_GET['bloodType'] : $_POST['bloodType'];
				$hospitalName = isset($_GET['hospitalName']) ? $_GET['hospitalName'] : $_POST['hospitalName'];
				$name_surname = isset($_GET['name_surname']) ? $_GET['name_surname'] : $_POST['name_surname'];
				$senderID = isset($_GET['senderID']) ? $_GET['senderID'] : $_POST['senderID'];

			if($notificationType =='mail'){
				$userEmails=$db->getUserWithBloodType($bloodType,$senderID);
				if (is_array($userEmails) || is_object($userEmails)){
					if($userEmails == NULL){
						$response["error"] = TRUE;
		    			$response['error_msg'] = "There is no user with that type of blood.";
		    			toXML($response);
					}
					else{
						$dat = date('Y-m-d H:i:s');
						$msg_data['title'] = $name_surname. " icin acil ".$bloodType." kan ihtiyaci.";
						$msg_data['msg'] = $hospitalName . " hastanesinde yatmakta olan ". $name_surname . 
						" hasta icin acil ". $bloodType . " grubu kan gerekmektedir. Bu mesaj, hastaneye olan 
						mesafenize gore gonderilmistir.";
						$msg_id = $db->saveBloodRequestMessage($msg_data['title'], $msg_data['msg'], $dat);
						if($msg_id == 'error'){
							$response["error"] = "TRUE";
				        	$response["error_msg"] = "Database error. Plase check again later.";
				        	toXML($response);
						}
						else{
							$loop = 1;
							$patientID = $db->saveSenderasPatient($senderID, $dat);
							if($patientID == 'error'){
								$response["error"] = "TRUE";
					        	$response["error_msg"] = "Database error. Plase check again later.";
					        	toXML($response);
							}
							else{
								foreach ($userEmails as $key => $item) {
									$db->saveBloodRequests($key, $senderID ,"mail", $msg_id);
									if($api->sendMail($item,$name_surname,$msg_data['msg'] ,"[BAIS-ANNC:BILKENT] COK COK ACIL KAN IHTIYACI") !== true){
										$loop = 0;
										break;
									}
								}
								if($loop == 1){
									$response["error"] = "FALSE";
						   			$response['success'] = "Email has been sent succesfully to nearby devices.";
						    		toXML($response);
								}
								else{
									$response["error"] = "TRUE";
						        	$response["error_msg"] = "Email hasn't been sent successfully";
						        	toXML($response);
								}
							}
						}
					}
				}
				
			}
			elseif($notificationType =='sms'){
				$userPhones=$db->getPhoneWithBloodType($bloodType);
				foreach ($userPhones as $key => $item) {	
						//echo $item.'<br>';
						$api->sendSMS($item,$name_surname,$bloodType,$hospitalName);
					}

			}

			elseif($notificationType =='push'){
				$deviceTokens=$db->getDeviceIDWithBloodType($bloodType, $senderID);


				$msg_data['title'] = $name_surname. " icin acil ".$bloodType." kan ihtiyaci.";
				$msg_data['msg'] = $hospitalName . " hastanesinde yatmakta olan ". $name_surname . 
				" hasta icin acil ". $bloodType . " grubu kan gerekmektedir. Bu mesaj, hastaneye olan 
				mesafenize gore gonderilmistir.";
				if(empty($deviceTokens)){
					$response["error"] = "TRUE";
					$response['error_msg'] = "There are no nearby devices located.";
	    			toXML($response);

				}else{
					$dat = date('Y-m-d H:i:s');
					$msg_id = $db->saveBloodRequestMessage($msg_data['title'], $msg_data['msg'], $dat);
					if($msg_id == 'error'){
						$response["error"] = "TRUE";
			        	$response["error_msg"] = "Database error. Plase check again later.";
			        	toXML($response);
					}
					else{

						$patientID = $db->saveSenderasPatient($senderID, $dat);
						if($patientID == 'error'){
							$response["error"] = "TRUE";
				        	$response["error_msg"] = "Database error. Plase check again later.";
				        	toXML($response);
						}
						else{
							$returning = $db->sendPush($deviceTokens, $msg_data, $senderID, $msg_id);
							if($returning == TRUE){
								$response["error"] = "FALSE";
					    		$response['success'] = "Message was sent to the nearby devices succesfully.";
					    		toXML($response);
							}
							else{

								$response["error"] = "TRUE";
								$response['error_msg'] = "Following firebase id(s) didn't receive message succesfully.";
								foreach ($returning as $key => $item) {
									$response['error_msg'] .= $item;
								}
					    		toXML($response);
							}
						}
					}
				}
			}

			else{

				$response["error"] = TRUE;
	    		$response['error_msg'] = "You need to give notificationType as one of the {sms, mail, push}";
	    		toXML($response);
			}
			
		}
		else{
			$response["error"] = TRUE;
	    		$response['error_msg'] = "Missing Parameters!";
	    		toXML($response);
		}


		
	}

	elseif($func == "getUsers"){
		if (isset($_GET['available'])){
			toXML($db->getUsers($_GET['available']));
		}
		else{
			toXML($db->getUsers(1));
		}	

	}

	elseif($func == "getUser"){
		$theUser = $db->getUserbyID($requestParts[1]);
		if($theUser !== NULL){
			toXML($theUser);
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "There is no user with the given ID.";
	    	toXML($response);
		}	

	}

	elseif($func == "saveFirebaseRegID"){
		if((isset($_GET['uid']) && isset($_GET['token'])) || (isset($_POST['uid']) && isset($_POST['token']))){
			$uid = 0;
			$token = "";
			if(isset($_GET['uid'])){
		    	$uid = $_GET['uid'];
		    	$token = $_GET['token'];
			}
			elseif(isset($_POST['uid'])){
				$uid = $_POST['uid'];
		    	$token = $_POST['token'];
			}
			$result = $db->saveFirebaseRegistrationID($token,$uid);
			if($result == $token){
				$response["error"] = "FALSE";
	   			$response["success"] = "Token has been added succesfully.";
	    		toXML($response);
			}
			
			else{
				$response["error"] = "TRUE";
		    	$response["error_msg"] = "Error on recording the token for a user.";
		    	toXML($response);
			}
		}

		else{
			$response["error"] = "TRUE";
	    	$response["error_msg"] = "Missing Parameters.";
	    	toXML($response);
		}

	}

	elseif($func == "tree"){
		function tree($path){
		  static $match;

		  // Find the real directory part of the path, and set the match parameter
		  $last=strrpos($path,"/");
		  if(!is_dir($path)){
		    $match=substr($path,$last);
		    while(!is_dir($path=substr($path,0,$last)) && $last!==false)
		      $last=strrpos($path,"/",-1);
		  }
		  if(empty($match)) $match="/*";
		  if(!$path=realpath($path)) return;

		  // List files
		  foreach(glob($path.$match) as $file){
		    $list[]=substr($file,strrpos($file,"/")+1);
		  }  

		  // Process sub directories
		  foreach(glob("$path/*", GLOB_ONLYDIR) as $dir){
		    $list[substr($dir,strrpos($dir,"/",-1)+1)]=tree($dir);
		  }
		  
		  return @$list;
		}

		$files = tree('/home/mustafa2/cs491-2.mustafaculban.net/'.$requestParts[1].'/');
		toXML($files);

	}
	
	elseif($func == "addEvent"){
		// class QueryBuilder {
		//     private $params = array();

		//     public function addParameter($key, $value) {
		//         $this->params[$key] = $value;
		//     }

		//     public function send() {
		//         $query = http_build_query($this->params);
		//         // whatever else has to be done to send.
		//         // for the sake of this example, it just returns the query string:
		//         return $query;
		//     }
		// }
		// $obj = new QueryBuilder();


		// echo $obj->send();
		if ((isset($_GET['name']) && isset($_GET['city']) && isset($_GET['content']) && isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['latitude']) && isset($_GET['longitude']) /*end of get*/) || /*start of post*/(isset($_POST['name']) && isset($_POST['city']) && isset($_POST['content']) && isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['latitude']) && isset($_POST['longitude']) /*check for post*/) /*end of if*/) {

			$name = isset($_GET['name']) ? $_GET['name'] : $_POST['name'];
			$city = isset($_GET['city']) ? $_GET['city'] : $_POST['city'];
			$content = isset($_GET['content']) ? $_GET['content'] : $_POST['content'];
			$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : $_POST['startDate'];
			$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : $_POST['endDate'];
			$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : $_POST['latitude'];
			$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : $_POST['longitude'];

			$result = $db->addEvent($name, $city, $content, $latitude, $longitude, $startDate, $endDate);

			if($result){
				$response["error"] = "FALSE";
	   			$response['success'] = "Event was added succesfully.";
	   			$response['event']['name'] = $name;
	   			$response['event']['city'] = $city;
	   			$response['event']['content'] = $content;
	   			$response['event']['latitude'] = $latitude;
	   			$response['event']['longitude'] = $longitude;
	   			$response['event']['startDate'] = $startDate;
	   			$response['event']['endDate'] = $endDate;

	    		toXML($response);
			}
			else{
				$response["error"] = "TRUE";
		    	$response['error_msg'] = "Error on saving event into database.";
		    	toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}


	}

	elseif($func == "getEvent"){

		if(isset($requestParts[1])){

			$event = $db->getEventbyID($requestParts[1]);

			if($event != NULL){
				$response["error"] = "FALSE";
				$response['event'] = $event;
				toXML($response);
			}
			else{
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is no event with the given ID.";
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}
	}
	
	elseif($func == "getEvents"){
		$events = $db->getEvents();
		if($events != NULL){
			$response["error"] = "FALSE";
		    $response['events'] = $events;
		    toXML($response);
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "There are no events registered in the system";
		}
	}

	elseif($func == "addBlogPost"){

		if ((isset($_GET['post_title']) && isset($_GET['date']) && isset($_GET['post_text']) && isset($_GET['image_link']) && isset($_GET['active']) && isset($_GET['userid'])  /*end of get*/) || /*start of post*/(isset($_POST['post_title']) && isset($_POST['date']) && isset($_POST['post_text']) && isset($_POST['image_link']) && isset($_POST['active']) && isset($_POST['userid'])  /*end of post*/) /*end of if*/) {
			$post_title = isset($_GET['post_title']) ? $_GET['post_title'] : $_POST['post_title'];
			$post_text = isset($_GET['post_text']) ? $_GET['post_text'] : $_POST['post_text'];
			$image_link = isset($_GET['image_link']) ? $_GET['image_link'] : $_POST['image_link'];
			$date = isset($_GET['date']) ? $_GET['date'] : $_POST['date'];
			$userid = isset($_GET['userid']) ? $_GET['userid'] : $_POST['userid'];
			$active = isset($_GET['active']) ? $_GET['active'] : $_POST['active'];
			
			$result = $db->addBlogPost($post_title, $post_text, $image_link, $date, $userid, $active);

			if($result){
				$response["error"] = "FALSE";
	   			$response['success'] = "Blog Post was added succesfully.";
	   			$response['post']['post_title'] = $post_title;
	   			$response['post']['post_text'] = $post_text;
	   			$response['post']['image_link'] = $image_link;
	   			$response['post']['date'] = $date;
	   			$response['post']['active'] = $active;

	    		toXML($response);
			}
			else{
				$response["error"] = "TRUE";
		    	$response['error_msg'] = "Error saving blog post into database .";
		    	toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}

	}

	elseif($func == "getBlogPost"){

		if(isset($requestParts[1])){

			$blogpost = $db->getBlogPostbyID($requestParts[1]);

			if($blogpost != NULL){
				$response["error"] = "FALSE";
				$response['post'] = $blogpost;
				toXML($response);
			}
			else{
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is no blog post with the given ID.";
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}
	}

	elseif($func == "getBlogPosts"){
		$limit = 0;
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		else
			$limit = 10;

		$posts = $db->getBlogPosts($limit);
		if($posts != NULL){
			$response["error"] = "FALSE";
		    $response['posts'] = $posts;
		    toXML($response);
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "There are no posts in the system yet.";
	    	toXML($response);
		}
	}

	elseif($func == "createEM5"){

		if ((isset($_GET['ownerID']) && ( isset($_GET['firstID']) || isset($_GET['secondID']) || isset($_GET['thirdID']) || isset($_GET['fourthID']) || isset($_GET['fifthID']) ) /*end of get*/) || 
			/*start of post*/
			(isset($_POST['ownerID']) && ( isset($_POST['firstID']) || isset($_POST['secondID']) || isset($_POST['thirdID']) || isset($_POST['fourthID']) || isset($_POST['fifthID']) ) /*end of post*/) /*end of if*/) {
			
			
			$ownerID = 0;

			$IDs = array();
			$i = 0;

			if(isset($_GET)){
				$ownerID = $_GET['ownerID'];
				
				if(isset($_GET['firstID'])){
					$IDs['first'] = $_GET['firstID'];
					$i++;
				}
				if(isset($_GET['secondID'])){
					$IDs['second'] = $_GET['secondID'];
					$i++;
				}
				if(isset($_GET['thirdID'])){
					$IDs['third'] = $_GET['thirdID'];
					$i++;
				}
				if(isset($_GET['fourthID'])){
					$IDs['fourth'] = $_GET['fourthID'];
					$i++;
				}
				if(isset($_GET['fifthID'])){
					$IDs['fifth'] = $_GET['fifthID'];
					$i++;
				}

				
			}
			else if(isset($_POST)){
				$ownerID = $_POST['ownerID'];
				
				if(isset($_POST['firstID'])){
					$IDs['first'] = $_POST['firstID'];
					$i++;
				}
				if(isset($_POST['secondID'])){
					$IDs['second'] = $_POST['secondID'];
					$i++;
				}
				if(isset($_POST['thirdID'])){
					$IDs['third'] = $_POST['thirdID'];
					$i++;
				}
				if(isset($_POST['fourthID'])){
					$IDs['fourth'] = $_POST['fourthID'];
					$i++;
				}
				if(isset($_POST['fifthID'])){
					$IDs['fifth'] = $_POST['fifthID'];
					$i++;
				}

				
			}else{}

			$result = $db->createEM5($ownerID,$IDs);

			if($result == "TRUE"){
				$response["error"] = "FALSE";
			    $response['success'] = "You have successfully created EM5 List.";
			    toXML($response);
			}
			else if($result == 'dublicate'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is a dublicated id in the list please correct that first.";
			    toXML($response);
			}

			else if($result == 'exist'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "User already has EM5 List. Try to update that.";
			    toXML($response);
			}
			else if($result == 'self'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "User cannot add himself or herself into EM5 List.";
			    toXML($response);
			}

			else{
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is an error on the server while recording. Please try again later.";
			    toXML($response);
			}

		}

	}

	elseif($func == "modifyEM5"){
		if ((isset($_GET['ownerID']) &&  isset($_GET['id']) && isset($_GET['order']) ) || 
			/*start of post*/
			(isset($_POST['ownerID']) &&  isset($_POST['id']) && isset($_POST['order']) /*end of post*/) /*end of if*/) {
			


		}

	}
	elseif($func == "approveEM5Request"){

		if ((isset($_GET['ownerID']) &&  isset($_GET['uid']) && isset($_GET['order']) && isset($_GET['choice'])) || 
			/*start of post*/
			(isset($_POST['ownerID']) &&  isset($_POST['uid']) && isset($_POST['order']) && isset($_POST['choice']) /*end of post*/) /*end of if*/) 
		{
			
			$ownerID = isset($_GET['ownerID']) ? $_GET['ownerID'] : $_POST['ownerID'];
			$uid = isset($_GET['uid']) ? $_GET['uid'] : $_POST['uid'];
			$order = isset($_GET['order']) ? $_GET['order'] : $_POST['order'];
			$choice = isset($_GET['choice']) ? $_GET['choice'] : $_POST['choice'];

			$result = $db->approveEM5Request($ownerID, $uid, $order, $choice);

			if($result == "TRUE"){
				$response["error"] = "FALSE";
			    $response['success'] = "You have successfully updated EM5 List.";
			    toXML($response);
			}
			else if($result == "notbelong"){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "This user is not in that order. Plase specify the order of the user.";
			    toXML($response);
			}
			else{
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is a problem while updating EM5 List. Please try again later.";
			    toXML($response);
			}

		}

		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}

		
		
	}

	elseif($func == "getEM5List"){

		if( isset($_POST['uid']) || isset($_GET['uid'])){
			$uid = isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];

			$list = $db->getEM5List($uid);

			if($list == 'notexist'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is no EM5 List for the given user ID.";
			    toXML($response);
			}
			else if($list == "nolist"){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "User has a EM5 List but there is no person in the list. ";
			    toXML($response);
			}
			else{
				$response["error"] = "FALSE";
			    $response['em5list'] = $list;
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}


	}

	elseif($func == "getSentNotification"){
		
		if( isset($_POST['uid']) || isset($_GET['uid'])){
			$uid = isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];

			$limit = 0;
			$type = 'non';

			if(isset($_GET['limit']))
				$limit = $_GET['limit'];
			else if(isset($_POST['limit']))
				$limit = $POST['limit'];

			if(isset($_GET['type']))
				$type = $_GET['type'];
			else if(isset($_POST['type']))
				$type = $POST['type'];

			$sentNotifications = $db->getSentNotification($uid, $limit, $type);

			if($sentNotifications == 'notexist'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "The user with the given ID does not exist.";
			    toXML($response);
			}
			else if($sentNotifications == 'nonotif'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is no notification created by the given user ID.";
			    toXML($response);
			}
			else if($sentNotifications == 'incorrecttype'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "Given type for the notification is not one of the {'sms', 'push', 'mail'}";
			    toXML($response);
			}
			
			else{
				$response["error"] = "FALSE";
			    $response['sentNotifications'] = $sentNotifications;
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}

	}

	elseif($func == "getReceivedNotification"){
		
		

		if( isset($_POST['uid']) || isset($_GET['uid'])){
			$uid = isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];

			$limit = 0;
			$type = "non";
			if(isset($_GET['limit']))
				$limit = $_GET['limit'];
			else if(isset($_POST['limit']))
				$limit = $POST['limit'];

			if(isset($_GET['type']))
				$type = $_GET['type'];
			else if(isset($_POST['type']))
				$type = $POST['type'];


			$receivedNotifications = $db->getReceivedNotification($uid, $limit, $type);

			if($receivedNotifications == 'notexist'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "The user with the given ID does not exist.";
			    toXML($response);
			}
			else if($receivedNotifications == 'nonotif'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "There is no notification received by the given user ID.";
			    toXML($response);
			}
			else if($receivedNotifications == 'incorrecttype'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "Given type for the notification is not one of the {'sms', 'push', 'mail'}";
			    toXML($response);
			}
			else{
				$response["error"] = "FALSE";
			    $response['receivedNotifications'] = $receivedNotifications;
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}

	}

	elseif($func == "getNotification"){
		if(isset($requestParts[1])){
			$notification = $db->getNotification($requestParts[1]);
			if($notification == 'notexist'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "The notification does not exist.";
			    toXML($response);
			}
			elseif($notification == 'unknown'){
				$response["error"] = "TRUE";
			    $response['error_msg'] = "Error occured in the database connection. Please try again later.";
			    toXML($response);
			}
			else{
				$response["error"] = "FALSE";
			    $response['notification'] = $notification;
			    toXML($response);
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}


	}

	elseif($func == "saveReceiverCondition"){
		if( ( isset($_GET['choice']) && isset($_GET['patientID']) && isset($_GET['donorID']) && isset($_GET['bloodRequestID']) ) || ( isset($_POST['choice']) && isset($_POST['patientID']) && isset($_POST['donorID']) && isset($_POST['bloodRequestID']))) {
			$choice = isset($_GET['choice']) ? $_GET['choice'] : $_POST['choice'];
			if(!($choice == 1 || $choice == -1)){
				$response["error"] = "TRUE";
		    	$response['error_msg'] = "Your choice must be '1' or '-1'. Plase try again.";
		    	toXML($response);
			}
			else{

				$patientID = isset($_GET['patientID']) ? $_GET['patientID'] : $_POST['patientID'];
				$donorID = isset($_GET['donorID']) ? $_GET['donorID'] : $_POST['donorID'];
				$bloodRequestID = isset($_GET['bloodRequestID']) ? $_GET['bloodRequestID'] : $_POST['bloodRequestID'];
				$dat = date('Y-m-d H:i:s');
				if(!$db->isUserClaimedConfirmorReject($donorID, $bloodRequestID)){
					if($choice == 1){
						$row_id = $db->saveReceiverasAwatingDonor($patientID, $donorID, $dat, $bloodRequestID);
						if($row_id == 'error'){
							$response["error"] = "TRUE";
				        	$response["error_msg"] = "Database error. Plase try again later.";
				        	toXML($response);
						}
						else{
							$response["error"] = "FALSE";
				        	$response["success"] = "You have been successfully added to Awaiting Donor list. You can learn what is meant to be in Awaiting Donor List in our program.";
				        	toXML($response);
						}
					}
					else if($choice == -1){
						$row_id = $db->saveReceiverasRejectedDonor($patientID, $donorID, $dat, $bloodRequestID);
						if($row_id == 'error'){
							$response["error"] = "TRUE";
				        	$response["error_msg"] = "Database error. Plase try again later.";
				        	toXML($response);
						}
						else{
							$response["error"] = "FALSE";
				        	$response["success"] = "You have been successfully added to Donor list because you dont want to donate your blood.";
				        	toXML($response);
						}
					}
				}
				else{
					$response["error"] = "FALSE";
		        	$response["success"] = "You have already chosen Confirm or Reject the request. You cannot change that again. ";
		        	toXML($response);
				}
				
			}
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}
		


	}

	elseif($func == "approveDonatedBlood"){
		if( ( isset($_GET['patientID']) && isset($_GET['donorID']) && isset($_GET['date']) ) || ( isset($_POST['patientID']) && isset($_POST['donorID']) && isset($_POST['date']) )) {
			
			$patientID = isset($_GET['patientID']) ? $_GET['patientID'] : $_POST['patientID'];
			$donorID = isset($_GET['donorID']) ? $_GET['donorID'] : $_POST['donorID'];
			$date = isset($_GET['date']) ? $_GET['date'] : $_POST['date'];

			$result = $db->moveFromAwaitingtoDonor($patientID, $donorID, $date );

			if ($result == 'nopair') {
				$response["error"] = "TRUE";
	    		$response['error_msg'] = "There is no patient with a given id who is waiting donation from given donor id.";
	    		toXML($response);
			}
			elseif ($result == 'falsedonor') {
				$response["error"] = "TRUE";
	    		$response['error_msg'] = "There is an error on adding user to donor list. Please contact with administration.";
	    		toXML($response);
			}
			elseif ($result == 'falsedeletedonor') {
				$response["error"] = "TRUE";
	    		$response['error_msg'] = "There is error on deleting user from awaiting list. Please contact wiht administration.";
	    		toXML($response);
			}

			elseif ($result == 'true') {
				$response["error"] = "FALSE";
	    		$response['error_msg'] = "Haleloyua";
	    		toXML($response);
			}
			
			
			else{
				$response["error"] = "FALSE";
	    		$response['success'] = "Donor has successfully moved from awaiting list to the donors list.";
	    		toXML($response);
			}

			
		}
		else{
			$response["error"] = "TRUE";
	    	$response['error_msg'] = "Missing Parameters.";
	    	toXML($response);
		}	

		
		
	}

	elseif($func == ""){
		
		$response["error"] = "TRUE";
	    $response['error_msg'] = "You have to use one endpoint to access.";
	    toXML($response);
	}

}
else{

	$response["error"] = "TRUE";
    $response['error_msg'] = "You are not allowed to use this API system.";
    toXML($response);
}





?>