<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

// Include the database connection file
require_once 'connection.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Retrieve the input data
$data = json_decode(file_get_contents("php://input"), true);

// Perform operations based on the request method
switch ($method) {
    case 'GET':
        if (isset($_GET['cusCode'])) {
            $cusCode = $_GET['cusCode'];
            $sql = "SELECT * FROM customer WHERE cusCode='$cusCode'";
        } else {
            $sql = "SELECT * FROM customer";
        }

        $result = $conn->query($sql);
        $response = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            $response = array('status' => 'error', 'message' => 'No records found for the provided cusCode');
        }

        echo json_encode($response);
        break;

    case 'POST':
        // Handle POST request to insert data
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $address = $data['address'];
        $subDistrict = $data['subDistrict'];
        $District = $data['District'];
        $province = $data['province'];
        $postalCode = $data['postalCode'];
        $phoneNumber = $data['phoneNumber'];
        $email = $data['email'];

        // Insert a new record into the "customer" table
        $sql = "INSERT INTO customer (firstName, lastName, address, subDistrict, District, province, postalCode, phoneNumber, email)
                VALUES ('$firstName', '$lastName', '$address', '$subDistrict', '$District', '$province', '$postalCode', '$phoneNumber', '$email')";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request to update data
        $cusCode = $data['cusCode'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $address = $data['address'];
        $subDistrict = $data['subDistrict'];
        $District = $data['District'];
        $province = $data['province'];
        $postalCode = $data['postalCode'];
        $phoneNumber = $data['phoneNumber'];
        $email = $data['email'];

        $sql = "UPDATE customer SET 
                    firstName='$firstName', lastName='$lastName', address='$address', subDistrict='$subDistrict', 
                    District='$District', province='$province', postalCode='$postalCode', phoneNumber='$phoneNumber', email='$email' 
                WHERE cusCode='$cusCode'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Handle DELETE request to delete data
        $cusCode = $_GET['cusCode']; // Adjust the parameter name as needed

        $sql = "DELETE FROM customer WHERE cusCode='$cusCode'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record deleted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error deleting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;
}
?>
