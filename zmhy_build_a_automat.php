<?php

// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'zmhy_automat';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define API Endpoints
/endpoints = [
    'users' => [
        'method' => 'GET',
        'callback' => 'getUsers'
    ],
    'user' => [
        'method' => 'GET',
        'callback' => 'getUser'
    ],
    'create-user' => [
        'method' => 'POST',
        'callback' => 'createUser'
    ],
    'update-user' => [
        'method' => 'PUT',
        'callback' => 'updateUser'
    ],
    'delete-user' => [
        'method' => 'DELETE',
        'callback' => 'deleteUser'
    ]
];

// API Service Dashboard
function apiServiceDashboard() {
    $method = $_SERVER['REQUEST_METHOD'];
    $endpoint = $_GET['endpoint'];

    if (array_key_exists($endpoint, $endpoints) && $endpoints[$endpoint]['method'] == $method) {
        call_user_func($endpoints[$endpoint]['callback']);
    } else {
        http_response_code(404);
        echo 'Endpoint not found';
    }
}

// API Endpoints Callback Functions

function getUsers() {
    $query = "SELECT * FROM users";
    $result = $conn->query($query);

    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}

function getUser() {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($query);

    $user = $result->fetch_assoc();
    echo json_encode($user);
}

function createUser() {
    $data = json_decode(file_get_contents('php://input'), true);
    $query = "INSERT INTO users (name, email) VALUES ('$data[name]', '$data[email]')";
    $conn->query($query);

    echo 'User created successfully';
}

function updateUser() {
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $query = "UPDATE users SET name = '$data[name]', email = '$data[email]' WHERE id = '$id'";
    $conn->query($query);

    echo 'User updated successfully';
}

function deleteUser() {
    $id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = '$id'";
    $conn->query($query);

    echo 'User deleted successfully';
}

// Run API Service Dashboard
apiServiceDashboard();

?>