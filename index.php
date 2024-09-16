<?php
header("Content-Type: application/json");
date_default_timezone_set('Asia/Dhaka');
// Database connection
$dsn = 'mysql:host=localhost;dbname=myapi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Get HTTP method
$requestMethod = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Handle the request method
switch ($requestMethod) {
    case 'GET':
        handleGetRequest($pdo);
        break;
    case 'POST':
        handlePostRequest($pdo, $input);
        break;
    case 'PUT':
        handlePutRequest($pdo, $input);
        break;
    case 'DELETE':
        handleDeleteRequest($pdo);
        break;
   
    default:
        echo json_encode(['message' => 'Method not allowed']);
        http_response_code(405); // Method Not Allowed
        break;
}

// Handle GET request (Read)
function handleGetRequest($pdo) {
    // ------------ GET request data insert--------
    $servername 	= "localhost"; //"192.168.20.14:3306";
    $dbname 		= "myapi";
    $username 		= "root";
    $password 		= "";
    
    
    $conn = mysqli_connect($servername, $username, $password, $dbname); 
    
    // Retrieve query parameters
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $email = isset($_GET['email']) ? $_GET['email'] : null;
    if (isset($_GET['name']) && isset($_GET['email'])) {
    
        $queryParams = array(
            'name' => $name,
            'email' => $email
        );
    
        $get_response = json_encode($queryParams);
        // $queryParams = $_GET;
    }
    
    // Output the JSON-encoded response
    
    
    // Validate and process the parameters as needed
    if ($name && $email && $get_response) {
        // Example response data
        $sql = "insert into users (name,email,row_data) values('$name', '$email' ,'$get_response')";
                mysqli_query($conn,$sql);
        $response = array(
            "name" => $name,
            "email" => $email,
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
    

// ------------------------------
    if (isset($_GET['id'])) {
        // Get a single user by ID
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['message' => 'User not found']);
            http_response_code(404);
        }
    } else {
        // Get all users
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }

}

// Handle POST request (Create)
function handlePostRequest($pdo, $input) {
    if (isset($input['name']) && isset($input['email'])) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $result = $stmt->execute([$input['name'], $input['email']]);

        if ($result) {
            echo json_encode(['message' => 'User created successfully']);
            http_response_code(201); // Created
        } else {
            echo json_encode(['message' => 'Failed to create user']);
            http_response_code(500); // Internal Server Error
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
        http_response_code(400); // Bad Request
    }
}

// Handle PUT request (Update)
function handlePutRequest($pdo, $input) {
    if (isset($_GET['id']) && isset($input['name']) && isset($input['email'])) {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $result = $stmt->execute([$input['name'], $input['email'], $id]);

        if ($result) {
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            echo json_encode(['message' => 'Failed to update user']);
            http_response_code(500);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
        http_response_code(400);
    }
}

// Handle DELETE request (Delete)
function handleDeleteRequest($pdo) {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result) {
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete user']);
            http_response_code(500);
        }
    } else {
        echo json_encode(['message' => 'ID is required']);
        http_response_code(400);
    }
}
?>
