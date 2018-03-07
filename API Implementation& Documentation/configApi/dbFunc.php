<?php
 
/**
 * @author Ravi Tamada
 * @link https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'dbConn.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    function isInOauthIDs( $outhID){
        $oauth = array();
        
        $query = mysqli_query($this->conn, "SELECT token as OAuthToken FROM admin");
        
        if (mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)){
                $oauth[$i] = $row['OAuthToken'];
                
            }
        }

        return  in_array($outhID,$oauth);
    }
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($username, $firstname, $surname, $password, $email, $identityNum, $bloodType, $birthdate, $address, $telephone) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $hash2 = $this->hashSSHA($identityNum);
        $encryptedidentityNum = $hash2["encrypted"]; // encrypted password
        $identitySalt = $hash["salt"]; // salt
 
        $stmt = $this->conn->prepare("INSERT INTO user (username, firstname, surname, password, salt, email, identitiyNum, identitySalt, bloodType,birthdate, address,telephone, available) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("ssssssssssss",$username, $firstname, $surname, $encrypted_password, $salt, $email, $encryptedidentityNum, $identitySalt, $bloodType, $birthdate, $address, $telephone);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $user = $this->fetchAssocStatement($stmt);
            $stmt->close();
            
            date_default_timezone_set('Europe/Istanbul');
            $current_time = date("g:i A");                                                                                                                                                                                       
            $current_date = date("l, F jS, Y");
            $ip = $_SERVER['REMOTE_ADDR'];

            $stmt2 = $this->conn->prepare("UPDATE user set last_login_ip = ?, last_login_date = ? , last_login_time = ? where user.email = ?");
            $stmt2->bind_param("ssss",$ip, $current_date, $current_time, $email);
            $result = $stmt2->execute();
            $stmt2->close();
            if ($result) {

                return $user;
            }
            else{
                return NULL;
            }
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $stmt->store_result();
            $user = $this->fetchAssocStatement($stmt);
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                date_default_timezone_set('Europe/Istanbul');
                $current_time = date("g:i A");                                                                                                                                                                                       
                $current_date = date("l, F jS, Y");
                $ip = $_SERVER['REMOTE_ADDR'];

                $stmt2 = $this->conn->prepare("UPDATE user set last_login_ip = ?, last_login_date = ? , last_login_time = ? where user.email = ?");
                $stmt2->bind_param("ssss",$ip, $current_date, $current_time, $email);
                $result = $stmt2->execute();
                $stmt2->close();
                if ($result) {
                // user authentication details are correct
                    return $user;
                }
                else{
                    return NULL;
                }
            }
        } else {
            return NULL;
        }
    }

    public function saveFirebaseRegistrationID($token, $uid) {
        $stmtF = $this->conn->prepare("");

        $flag = false;
        $count = mysqli_query($this->conn, "SELECT count(*) as count FROM firebaseTokens WHERE token = '$token' AND uid= '$uid'");
        while($row = mysqli_fetch_assoc($count)){
            if($row["count"] > 0)
                $flag = true;
        }
        $result = NULL;
        if($flag) {
            $stmt2 = $this->conn->prepare("UPDATE firebaseTokens set uid = ?, token = ? where uid = ?");
            $stmt2->bind_param("sss",$uid, $token,$uid);
            $result = $stmt2->execute();
            $stmt2->close();
        }
        else{
            $stmt = $this->conn->prepare("INSERT INTO firebaseTokens (uid, token) VALUES(?, ?)");
            $stmt->bind_param("ss",$uid, $token);
            $result = $stmt->execute();
            $stmt->close();
        }

        if($result){
            return $token;
        }
        else{
            return "not";
        }
 
        
    }

    function getUserbyID($id){
        $stmt = $this->conn->prepare(" ?");
        $count = mysqli_query($this->conn, "SELECT count(*) as count FROM user WHERE id ='$id'");
        $returnUser = NULL;

        while($row = mysqli_fetch_assoc($count)){
            if($row["count"] === 0)
                return NULL;
        }

        $query = mysqli_query($this->conn, "SELECT id,username,firstname,surname,email,bloodType,
                                            birthdate,address,telephone,available,last_login_ip,
                                            last_login_date,last_login_time FROM user WHERE id ='$id'");
        
        if($row = mysqli_fetch_assoc($query)){
                $returnUser = $row;
        }
        
        if($returnUser !== NULL){
            $ip = $returnUser['last_login_ip'];
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            $returnUser['last_city'] = $details->city;

            return $returnUser;
        }
        else
            return NULL;
    }



    public function addEvent($name, $city, $content, $latitude, $longitude, $startDate, $endDate) {
        $stmtF = $this->conn->prepare("");

        $stmt = $this->conn->prepare("INSERT INTO event (name, city, content, latitude, longitude, startDate, endDate) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss",$name, $city, $content, $latitude, $longitude, $startDate, $endDate);
        $result = $stmt->execute();
        $stmt->close();

        if($result){
            return TRUE;
        }
        else{
            return FALSE;
        }
 
        
    }

        /*
     Return the event with given ID
    */

     public function getEventbyID($id) {

        $event = NULL;

        $stmt = $this->conn->prepare("SELECT * from event WHERE id = ?");
 
        $stmt->bind_param("i", $id);
 
        $stmt->execute();
 
        $stmt->store_result();

        $event = $this->fetchAssocStatement($stmt);
        
        if($stmt->num_rows <= 0)
            return NULL;

        if ($stmt->num_rows == 1) {
            // user existed 
            $stmt->close();
            return $event;
        } else {
            // user not existed
            $stmt->close();
            return NULL;
        }
    }


    /*
    GET most recently 10 events
    */
    public function getEvents() {
        $events = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT * FROM event ORDER BY id DESC LIMIT 0,10");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $events[$i] = $row;
                $i++;
            }

            return $events;
        }
 
         else {
            return NULL;
        }
    }
    /*
        Add Blog Post 
    */
    public function addBlogPost($post_title, $post_text, $image_link, $date, $userid, $active) {

        $stmt = $this->conn->prepare("INSERT INTO blogpost (post_title, post_text, image_link, post_date, uid, active) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii",$post_title, $post_text, $image_link, $date, $userid, $active);
        $result = $stmt->execute();
        $stmt->close();

        if($result){
            return TRUE;
        }
        else{
            return FALSE;
        }
 
        
    }

    /*
     Return the blogpost with given ID
    */

     public function getBlogPostbyID($id) {

        $blogpost = NULL;

        $stmt = $this->conn->prepare("SELECT * from blogpost WHERE id = ?");
 
        $stmt->bind_param("i", $id);
 
        $stmt->execute();
 
        $stmt->store_result();

        $blogpost = $this->fetchAssocStatement($stmt);
        
        if($stmt->num_rows <= 0)
            return NULL;

        if ($stmt->num_rows >= 1) {
            // user existed 
            $stmt->close();
            return $blogpost;
        } else {
            // user not existed
            $stmt->close();
            return NULL;
        }
    }

        /*
    GET most recently 10 posts
    */
    public function getBlogPosts($limit) {
        $posts = array();
        $i = 0;
        $sql = "SELECT * FROM blogpost ORDER BY id DESC LIMIT 0,".$limit;
        $query = mysqli_query($this->conn, $sql);
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $posts[$i] = $row;
                $i++;
            }

            return $posts;
        }
 
         else {
            return NULL;
        }
    }

    

    /**
     * Check user is existed or not
     * by checking his/her email
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from user WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Check user is existed or not
     * by checking his/her ID
     */
    public function isUserExistedByID($id) {
        $stmt = $this->conn->prepare("SELECT id from user WHERE id = ?");
 
        $stmt->bind_param("s", $id);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function getUsers($availibility) {
        $users = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT * FROM user WHERE available = '$availibility'");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $users[$i] = $row;
                $i++;
            }

            return $users;
        }
 
         else {
            return NULL;
        }
    }

    public function getUserWithBloodType($bloodtype, $senderID) {
        $emails = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT * FROM user WHERE bloodType = '$bloodtype' and available = 1 and id <> '$senderID'");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $emails[$row['id']] = $row['email'];
                $i++;
            }

            return $emails;
        }
 
         else {
            return NULL;
        }
    }

    public function getPhoneWithBloodType($bloodtype) {
        $phones = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT * FROM user WHERE bloodType = '$bloodtype' and available = 1");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $phones[$i] = $row['telephone'];
                $i++;
            }

            return $phones;
        }
 
         else {
            return array();
        }
    }
    /**
     *  sender cannot send push notifcation him/herself 
     *  return tokens if there exists 
     *  if there is no return empty array for checking on the api.php file.
     */
    public function getDeviceIDWithBloodType($bloodtype, $senderID) {
        $tokens = array();
        $query = mysqli_query($this->conn, "SELECT firebaseTokens.token as token, user.id as id FROM user join firebaseTokens on user.id = firebaseTokens.uid WHERE user.bloodType = '$bloodtype' and user.available = 1 and user.id <> '$senderID'");
        
        if ($query) {

            while($row = mysqli_fetch_assoc($query)){
                $tokens[$row['id']] = $row['token'];
            }
            if(empty($tokens))
                return array();
            else 
                return $tokens;
        }
 
         else {
            return array();
        }
    }

    /**
     *  function to send push message which is constructed with the token list (if any exists)
     *  and message data which includes title and message
     */

    function sendPush($tokens, $msg_data, $senderID, $msg_id){
        include '../api/firebase/firebase.php';
        include '../api/firebase/push.php';

        $firebase = new Firebase();
        $push = new Push();
        $errors = array();
        $i = 0;
        foreach ($tokens as $key => $item) {  
            $this->saveBloodRequests($key, $senderID ,"push", $msg_id);
            $return = $this->pushHelper($item, $push, $firebase, $msg_data);
            $res = json_decode($return,true); 
            if($res['success'] != 1){
                $errors[$i] = $item;
            }
        }

        if(empty($errors))
            return TRUE;
        else
            return $errors;


        
                        
    }

    /**
     *  push helper to send the notification to the poroper firebase ID.
     *  this function will send message to a device.
     */

    function pushHelper($token, $push, $firebase ,$msg_data){


    // optional payload
        $payload = array();
        $payload['team'] = 'xdxdxd';
        $payload['score'] = '10';

        // notification title
        $title = $msg_data['title'];
        
        // notification message
        $message = $msg_data['msg'];
        

        // push type - single user / topic
        $push_type = isset($_GET['push_type']) ? $_GET['push_type'] : '';
        
        // whether to include to image or not
        $include_image = isset($_GET['include_image']) ? TRUE : FALSE;


        $push->setTitle($title);
        $push->setMessage($message);
        
        $push->setImage('http://cs491-2.mustafaculban.net/images/push.png');
        $push->setIsBackground(FALSE);
        $push->setPayload($payload);


        $json = '';
        $response = '';

        $json = $push->getPush();
        $response = $firebase->send($token, $json);

        return $response;
}

    function storeSentSMS($toNo, $sentDate, $msgUniqueID){

        $stmt = $this->conn->prepare("INSERT INTO sentSMS (toNo, sentDate, msgUniqueID) VALUES(?, ?, ?)");
        $stmt->bind_param("sss",$toNo, $sentDate, $msgUniqueID);
        $result = $stmt->execute();
        $stmt->close();


    }


    function saveBloodRequestMessage( $title, $msg, $datestamp){

        $msgID=0;
        $sql = "INSERT INTO bloodRequestsMessage (title, msg, datestamp) VALUES ('$title', '$msg', '$datestamp');";
        
        if(mysqli_query($this->conn, $sql)){
            $msgID = mysqli_insert_id($this->conn);
            return $msgID;
        }
        else{
            return 'error';
        }

        return $msgID;


    }

    function saveSenderasPatient( $senderID, $dat){

        $msgID=0;
        $sql = "INSERT INTO patient (uid, datetime) VALUES ('$senderID', '$dat');";
        
        if(mysqli_query($this->conn, $sql)){
            $msgID = mysqli_insert_id($this->conn);
            return $msgID;
        }
        else{
            return 'error';
        }

        return $msgID;


    }

    public function isUserClaimedConfirmorReject($donorID, $bloodRequestID) {

        $stmt = $this->conn->prepare("SELECT donorID, bloodreqID from awaitingDonor WHERE donorID = ? AND bloodreqID = ?");
 
        $stmt->bind_param("ii", $donorID, $bloodRequestID);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true; // user exists in 'awaitingDonor' table.

        } 
        else {
            $stmt->close();

            $stmt2 = $this->conn->prepare("SELECT donorID, bloodreqID from donorRejecting WHERE donorID = ? AND bloodreqID = ?");
 
            $stmt2->bind_param("ii", $donorID, $bloodRequestID);
     
            $stmt2->execute();
     
            $stmt2->store_result();

            if ($stmt2->num_rows > 0) {

                $stmt2->close();
                return true; // user exists in 'donorRejecting' table.
            }
            else{
                $stmt2->close();
                return false; // user both not exists in 'donorRejecting' and 'awaitingDonor' table.
            }
        }
    }


    function saveReceiverasAwatingDonor( $patientID, $donorID, $dat, $bloodRequestID){

        $msgID=0;
        $sql = "INSERT INTO awaitingDonor (patientID, donorID, bloodreqID, datestamp) VALUES ('$patientID', '$donorID', '$bloodRequestID', '$dat');";
        
        if(mysqli_query($this->conn, $sql)){
            $msgID = mysqli_insert_id($this->conn);
            return $msgID;
        }
        else{
            return 'error';
        }

        return $msgID;


    }

    function saveReceiverasRejectedDonor( $patientID, $donorID, $dat, $bloodRequestID){

        $msgID=0;
        $sql = "INSERT INTO donorRejecting (patientID, donorID, bloodreqID, datestamp) VALUES ('$patientID', '$donorID', '$bloodRequestID', '$dat');";
        
        if(mysqli_query($this->conn, $sql)){
            $msgID = mysqli_insert_id($this->conn);
            return $msgID;
        }
        else{
            return 'error';
        }

        return $msgID;


    }



    function moveFromAwaitingtoDonor( $patientID, $donorID, $dat){

        $msgID=0;

        $query= mysqli_query($this->conn, "SELECT id, datestamp from awaitingDonor where patientID = '$patientID' and donorID = '$donorID' and datestamp = '$dat'");

        if(mysqli_num_rows($query) < 1){
            return 'nopair';
        }
        else{
            $date = date('Y-m-d H:i:s');
            $row = mysqli_fetch_assoc($query);
            $awaitingDate = $row['datestamp'];
            $sql = "INSERT INTO donors (patientID, donorID, whenBecameAwaiting, whenBecameDonor) VALUES ('$patientID', '$donorID', '$awaitingDate' ,'$date');";
            if(mysqli_query($this->conn, $sql)){
                
                $sql2 = "DELETE from awaitingDonor WHERE id ='".$row['id']."';";
                if(mysqli_query($this->conn, $sql2)){
                    return 'true';
                }
                else{
                    return 'falsedeletedonor';
                }
            }
            else{
                return 'falsedonor';
            }

            


        }



        

        return $msgID;


    }



    /**
     *  this function is used to store sent notification in database for analytics and privacy purposes.
     */
    function saveBloodRequests($receiverID, $senderID, $type, $msg_id){

        $stmt = $this->conn->prepare("INSERT INTO bloodrequests (receiverID,senderID, type, msgID) VALUES (?, ?, ?, ?);");
        $stmt->bind_param("iisi",$receiverID, $senderID, $type, $msg_id);
        $result = $stmt->execute();
        $stmt->close();
        
        
    }

    /**
     * Check user is existed or not
     * by checking his/her ID
     */
    public function isNotificationExists($nid) {
        $stmt = $this->conn->prepare("SELECT id from bloodrequests WHERE id = ?");
        $stmt->bind_param("s", $nid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /*
        check em5 is exist 
    */
    public function isEM5Exists($uid) {

        $stmt = $this->conn->prepare("SELECT requestOwnerID from emergencyFiveLists WHERE requestOwnerID = ?");
 
        $stmt->bind_param("i", $uid);
 
        $stmt->execute();

        //echo $stmt->error;
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
 
        
    }


    /*
        create em5 
    */
    public function createEM5($ownerID, $IDs) {
        //echo "owner ID - ". $ownerID. "<br>";
        $flag = FALSE;

        foreach ($IDs as $key => $item) {
            if($ownerID == $item){
                return "self";
            }
        }

        foreach ($IDs as $key => $item) {
            foreach ($IDs as $key2 => $item2) {
                if($item2== $item){
                    return "dublicate";
                }
            }
        }

        if($this->isEM5Exists($ownerID))
            return "exist";


        foreach ($IDs as $key => $item) {
            //echo $key. " - ". $item. "<br>"; 
            if($key == "first"){

                $stmt = $this->conn->prepare("INSERT INTO emergencyFiveLists (requestOwnerID, first_ID, first_isApproved) VALUES(?, ?, 0)");
                $stmt->bind_param("ii",$ownerID,$item);
                $result = $stmt->execute();
                $stmt->close();
                $flag = $result;
            }
            else if($key == "second"){
                
                $stmt2 = $this->conn->prepare("UPDATE emergencyFiveLists set second_ID = ?,second_isApproved = 0 where requestOwnerID = ?");
                $stmt2->bind_param("ii", $item, $ownerID);
                $result = $stmt2->execute();
                $stmt2->close();
                $flag = $result;
            }
            else if($key == "third"){
                $stmt3 = $this->conn->prepare("UPDATE emergencyFiveLists set third_ID = ?, third_isApproved = 0 where requestOwnerID = ?");
                $stmt3->bind_param("ii", $item, $ownerID);
                $result = $stmt3->execute();
                $stmt3->close();
                $flag = $result;
            } 
            else if($key == "fourth"){
                $stmt4 = $this->conn->prepare("UPDATE emergencyFiveLists set fourth_ID = ?, fourth_isApproved = 0 where requestOwnerID = ?");
                $stmt4->bind_param("ii", $item, $ownerID);
                $result = $stmt4->execute();
                $stmt4->close();
                $flag = $result;
            }
            else if($key == "fifth"){
                $stmt5 = $this->conn->prepare("UPDATE emergencyFiveLists set fifth_ID = ?, fifth_isApproved = 0 where requestOwnerID = ?");
                $stmt5->bind_param("ii", $item, $ownerID);
                $result = $stmt5->execute();
                $stmt5->close();
                $flag = $result;
            }          
        }
        

        if($flag){
            return "TRUE";
        }
        else{
            return "FALSE";
        }
 
        
    }

    public function approveEM5Request($ownerID, $uid, $order, $choice) {

        if(!$this->isOrderBelongtoID($uid, $order, $ownerID))
            return "notbelong";

        $query = "";
        switch ($order) {
            case 'first':
                $query = "UPDATE emergencyFiveLists set first_isApproved = ?, first_date = ? where requestOwnerID = ? and first_ID = ?";
                break;
            case 'second':
                $query = "UPDATE emergencyFiveLists set second_isApproved = ?, second_date = ? where requestOwnerID = ? and second_ID = ?";
                break;
            case 'third':
                $query = "UPDATE emergencyFiveLists set third_isApproved = ?, third_date = ? where requestOwnerID = ? and third_ID = ?";
                break;
            case 'fourth':
                $query = "UPDATE emergencyFiveLists set fourth_isApproved = ?, fourth_date = ? where requestOwnerID = ? and fourth_ID = ?";
                break;
            case 'fifth':
                $query = "UPDATE emergencyFiveLists set fifth_isApproved = ?, fifth_date = ? where requestOwnerID = ? and fifth_ID = ?";
                break;
            default:
                # code...
                break;
        }
        $date = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isii",$choice, $date, $ownerID, $uid );
        $result = $stmt->execute();
        $stmt->close();
        if($result){
            return "TRUE";
        }
        else{
            return "FALSE";
        }
            
    }

    public function isOrderBelongtoID($id, $order, $ownerID){

        switch ($order) {
            case 'first':
                $stmt = $this->conn->prepare("SELECT first_ID from emergencyFiveLists WHERE requestOwnerID = ?");
                $stmt->bind_param("i",$ownerID);
                if ($stmt->execute()) {
                $stmt->store_result();
                $usr = $this->fetchAssocStatement($stmt);
                    return $id == $usr['first_ID'];
                }
                break;
            case 'second':
                $stmt = $this->conn->prepare("SELECT second_ID from emergencyFiveLists WHERE requestOwnerID = ?");
                $stmt->bind_param("i",$ownerID);
                if ($stmt->execute()) {
                $stmt->store_result();
                $usr = $this->fetchAssocStatement($stmt);
                    return $id == $usr['second_ID'];
                }
                break;
            case 'third':
                $stmt = $this->conn->prepare("SELECT third_ID from emergencyFiveLists WHERE requestOwnerID = ?");
                $stmt->bind_param("i",$ownerID);
                if ($stmt->execute()) {
                $stmt->store_result();
                $usr = $this->fetchAssocStatement($stmt);
                    return $id == $usr['third_ID'];
                }
                break;
            case 'fourth':
                $stmt = $this->conn->prepare("SELECT fourth_ID from emergencyFiveLists WHERE requestOwnerID = ?");
                $stmt->bind_param("i",$ownerID);
                if ($stmt->execute()) {
                $stmt->store_result();
                $usr = $this->fetchAssocStatement($stmt);
                    return $id == $usr['fourth_ID'];
                }
                break;
            case 'fifth':
                $stmt = $this->conn->prepare("SELECT fifth_ID from emergencyFiveLists WHERE requestOwnerID = ?");
                $stmt->bind_param("i",$ownerID);
                if ($stmt->execute()) {
                $stmt->store_result();
                $usr = $this->fetchAssocStatement($stmt);
                    return $id == $usr['fifth_ID'];
                }
                break;
            
            default:
                return FALSE;
                break;
        }
    } 

    /*
    * This method is used to fetch the EM5 list if there exists one for the user.
    */
    public function getEM5List($ownerID) {

        if(!$this->isEM5Exists($ownerID))
            return "notexist";

        $em5List = NULL;

        $stmt = $this->conn->prepare("SELECT    requestOwnerID as owner_id, 
                                                first_ID as firstPersonID,
                                                (select email from `user` where id = first_ID) as first_email,
                                                (select concat(firstname,' ', surname) from `user` where id = first_ID) as first_name,
                                                first_isApproved as isApprovedforFirstPerson,
                                                first_date as firstPersonChangeDate,
                                                second_ID as secondPersonID,
                                                (select email from `user` where id = second_ID) as second_email,
                                                (select concat(firstname,' ', surname) from `user` where id = second_ID) as second_name,
                                                second_isApproved as isApprovedforSecondPerson,
                                                second_date as secondPersonChangeDate,
                                                third_ID as thirdPersonID,
                                                (select email from `user` where id = third_ID) as third_email,
                                                (select concat(firstname,' ', surname) from `user` where id = third_ID) as third_name,
                                                third_isApproved as isApprovedforThirdPerson,
                                                third_date as thirdPersonChangeDate, 
                                                fourth_ID as fourthPersonID,
                                                (select email from `user` where id = fourth_ID) as fourth_email,
                                                (select concat(firstname,' ', surname) from `user` where id = fourth_ID) as fourth_name,
                                                fourth_isApproved as isApprovedforFourthPerson,
                                                fourth_date as fourthPersonChangeDate,
                                                fifth_ID as fifthPersonID,
                                                (select email from `user` where id = fifth_ID) as fifth_email,
                                                (select concat(firstname,' ', surname) from `user` where id = fifth_ID) as fifth_name,
                                                fifth_isApproved as isApprovedforFifthPerson,
                                                fifth_date as fifthPersonChangeDate from emergencyFiveLists WHERE requestOwnerID = ?
");
 
        $stmt->bind_param("i", $ownerID);
        $stmt->execute(); 
        $stmt->store_result();
        $em5List = $this->fetchAssocStatement($stmt);
        
        if($stmt->num_rows <= 0)
            return 'nolist';
        if ($stmt->num_rows >= 1) {
            $stmt->close();
            return $em5List;
        } else {
            $stmt->close();
            return 'nolist';
        }
    }

    /*
    * This method is used to get sent notification by the user with given uid.
    */
    public function getSentNotification($uid, $limit, $type) {

        if( $limit == 0){
            $limit = 10;
        }

        $str = "";

        if($type != "non" &&  !$this->correctNotificationType($type))
            return "incorrecttype";

        if(!$this->isUserExistedByID($uid))
            return "notexist";

        if($type != "non"){
            $str = " and type = '".$type."' ";
        }


        $sentNotifications = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT bloodrequests.id, CONCAT(SUBSTR(msg,1,100), '...') as msg, 
                                            receiverID as sentUserID, type, title, datestamp FROM bloodrequests join bloodRequestsMessage on bloodrequests.msgID = bloodRequestsMessage.id where senderID = '$uid' ".$str."
                                            ORDER BY datestamp  DESC LIMIT 0,".$limit.";");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $sentNotifications[$i] = $row;
                $i++;
            }
            if(mysqli_num_rows($query) == 0)
                return 'nonotif';
            else
                return $sentNotifications;
        }
 
         else {
            return 'nonotif';
        }
        
    }

    /*
    * This method is used to get received notification by the user with given uid.
    */
    public function getReceivedNotification($uid, $limit, $type) {

        if( $limit == 0){
            $limit = 10;
        }
        $str = "";

        if($type != "non" && !$this->correctNotificationType($type))
            return "incorrecttype";

        if($type != "non"){
            $str = " and type = '".$type."' ";
        }
        if(!$this->isUserExistedByID($uid))
            return "notexist";

        $receivedNotifications = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT bloodrequests.id, CONCAT(SUBSTR(bloodRequestsMessage.msg,1,100), '...') as msg, 
                                                bloodrequests.senderID as sentUserID, bloodrequests.type, title, datestamp FROM bloodrequests join bloodRequestsMessage on bloodrequests.msgID = bloodRequestsMessage.id where bloodrequests.receiverID  = '$uid' ".$str." 
                                            AND bloodrequests.id NOT IN (SELECT bloodreqID FROM awaitingDonor WHERE  donorID = bloodrequests.receiverID )
                                            AND bloodrequests.id NOT IN (SELECT bloodreqID FROM donorRejecting WHERE  donorID = bloodrequests.receiverID )
                                            AND bloodrequests.id NOT IN (SELECT bloodreqID FROM donors WHERE  donorID = bloodrequests.receiverID ) ORDER BY bloodRequestsMessage.datestamp  DESC LIMIT 0,".$limit.";"
                                            );
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $receivedNotifications[$i] = $row;
                $i++;
            }
            if(mysqli_num_rows($query) == 0)
                return 'nonotif';
            else
                return $receivedNotifications;
        }
 
         else {
            return 'nonotif';
        }

        

        
    }

    /*
    * method to get a notification with a given id. 
    * If there is no notification return error.
    */
    public function getNotification($nid) {

        if(!$this->isNotificationExists($nid)){
            return 'notexist';
        }

        $notification = NULL;

        $query = mysqli_query($this->conn, "SELECT bloodrequests.id, CONCAT(SUBSTR(msg,1,100), '...') as msg, 
                                            senderID, type, title, datestamp FROM bloodrequests join bloodRequestsMessage on bloodrequests.msgID = bloodRequestsMessage.id where bloodrequests.id = '$nid' ");
        
        if ($query) {
            $notification = mysqli_fetch_assoc($query);
            if(mysqli_num_rows($query) == 0)
                return 'notexist';
            else
                return $notification;
        }
         else {
            return 'unknown';
        }



    }
    /*
    * This class is a helper to check whether the given type is one of these three: push, sms, mail
    */
    private function correctNotificationType ($type ){

        return ($type == 'push') || ($type == 'mail') || ($type == 'sms');

    }
    


    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }


 
    public function fetchAssocStatement($stmt){
        if($stmt->num_rows>0)
        {
            $result = array();
            $md = $stmt->result_metadata();
            $params = array();
            while($field = $md->fetch_field()) {
                $params[] = &$result[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);
            if($stmt->fetch())
                return $result;
        }

        return null;
}
 
}
 
?>