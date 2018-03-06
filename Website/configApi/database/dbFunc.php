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

        $count = mysqli_query($this->conn, "SELECT count(*) as count FROM firebaseTokens WHERE token = '$token' AND uid= '$uid'");
        while($row = mysqli_fetch_assoc($count)){
            if($row["count"] == 1)
                return "count1";
        }


        $stmt = $this->conn->prepare("INSERT INTO firebaseTokens (uid, token) VALUES(?, ?)");
        $stmt->bind_param("ss",$uid, $token);
        $result = $stmt->execute();
        $stmt->close();

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

    /**
     * Check user is existed or not
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

    public function getUserWithBloodType($bloodtype) {
        $emails = array();
        $i = 0;
        $query = mysqli_query($this->conn, "SELECT * FROM user WHERE bloodType = '$bloodtype' and available = 1");
        
        if ($query) {
            while($row = mysqli_fetch_assoc($query)){
                $emails[$i] = $row['email'];
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


    function storeSentSMS($toNo, $sentDate, $msgUniqueID){

        $stmt = $this->conn->prepare("INSERT INTO sentSMS (toNo, sentDate, msgUniqueID) VALUES(?, ?, ?)");
        $stmt->bind_param("sss",$toNo, $sentDate, $msgUniqueID);
        $result = $stmt->execute();
        $stmt->close();


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