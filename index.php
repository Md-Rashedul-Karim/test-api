<?php
header('Content-Type: application/json');

date_default_timezone_set('Asia/Dhaka');

$servername 	= "192.168.20.14:3306";
$username 		= "root";
$password 		= "351f0*57034e1a025#";
$dbname 		= "html5.b2mwap.com";


$conn = mysqli_connect($servername, $username, $password, $dbname); 

// Retrieve query parameters
$msisdn = isset($_GET['msisdn']) ? $_GET['msisdn'] : null;
$service = isset($_GET['service']) ? $_GET['service'] : null;
if (isset($_GET['msisdn']) && isset($_GET['service'])) {

    $queryParams = array(
        'msisdn' => $msisdn,
        'service' => $service
    );
    // Convert the array to JSON
    $get_response = json_encode($queryParams); // $queryParams = $_GET;
}

// Validate and process the parameters as needed
if ($msisdn && $service && $get_response) {
    // Example response data
    $sql = "insert into games (msisdn,service,row_data) values('$msisdn', '$service' ,'$get_response')";
			mysqli_query($conn,$sql);
    $response = array(
        "msisdn" => $msisdn,
        "service" => $service,
        "status" => "success",
        "message" => "Request processed successfully."
    );
} else {
    // Handle missing parameters
    $response = array(
        "status" => "error",
        "message" => "Missing required parameters."
    );
}

// Output the JSON-encoded response
echo json_encode($response);

?>
