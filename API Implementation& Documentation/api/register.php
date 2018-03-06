<?php
 
require_once '../configApi/dbFunc.php';
require_once '../configApi/TcKimlikNoSorgula.php';
require_once '../configApi/YabanciKimlikNoDogrula.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);
 
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
         $response["error"] = TRUE;
        $response["error_msg"] = "This identitiy Number is fake";
        echo json_encode($response);
    }
    // check if user is already existed with the same email
    else if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with email: " . $email. "and username: " .$username;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($username, $firstname, $surname, $password, $email, $identityNum, $bloodType, $birthdate, $address, $telephone);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
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
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}

?>