<?php 
require_once('../../configApi/functions.php');
require_once '../../configApi/dbFunc.php';
	
$db = new DB_Functions();

$accescode = "";
if((isset($_GET['secretCode']) && $_GET['secretCode'] == "abc" ) || (isset($_POST['secretCode']) && $_POST['secretCode'] == "abc" )){
	if(isset($_GET['secretCode'])){
		$accescode = $_GET['secretCode'];
	}
	if(isset($_POST['secretCode'])){
		$accescode = $_POST['secretCode'];
	}
	if(isset($_POST['id']) && isset($_POST['apikey']) ){
		$id = $_POST['id'];
		$key = $_POST['apikey'];
		$query = mysqli_query($db->connect(), "INSERT INTO apikeys (id, apikey) VALUES ('$id','$key')");
        
        if($query){
        	echo "true";
        }
        else{
        	echo "false";
        }
	}
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>BloodHub API Key Generator</title>
  
  
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>

  	<div class="wrapper">

		  	<button class="form-control" id="keygen">Generate API Key</button>
		  
		  	<input class="form-control" id="apikey" type="text" value="" placeholder="Click on the button to generate a new API key ..."  />
			<br></div>
<div class="wrapper">
	<form method="post" action="index.php">
		<label for="id" class="form-control">your user ID:</label>
		<input class="form-control" id="id" type="text" value="1" placeholder="Your User ID"  style="margin-top:20px"/>
		<br>
		<label for="id" class="form-control">Api key taken from above:</label>
		<input class="form-control" id="apikey" type="text" value="" placeholder="Api key taken from above."  style="margin-top:20px"/>
		<br>
		<input name="secretCode" id="secretCode" type="text" value="<?php echo $accescode;?>" hidden/>
		
		<input type="submit" value="Submit" />
	</form>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/index.js"></script>




</body>

</html>
<?php }else{

	$response["error"] = "TRUE";
    $response['error_msg'] = "You are not allowed to use this API system.";
    toXML($response);

}


	?>