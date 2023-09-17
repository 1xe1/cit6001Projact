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
        if (isset($_GET['EmID'])) {
            $EmID = $_GET['EmID'];
            $sql = "SELECT * FROM employee WHERE EmID='$EmID'";
        } else {
            $sql = "SELECT * FROM employee";
        }

        $result = $conn->query($sql);
        $response = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            $response = array('status' => 'error', 'message' => 'No records found for the provided EmID');
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
        $startDate = $data['startDate'];
        $password = $data['password'];
        $jobPosition = $data['jobPosition'];

        // Insert a new record into the "employee" table
        $sql = "INSERT INTO employee (firstName, lastName, address, subDistrict, District, province, postalCode, phoneNumber, email, startDate, password, jobPosition)
                VALUES ('$firstName', '$lastName', '$address', '$subDistrict', '$District', '$province', '$postalCode', '$phoneNumber', '$email', '$startDate', '$password', '$jobPosition')";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request to update data
        $EmID = $data['EmID'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $address = $data['address'];
        $subDistrict = $data['subDistrict'];
        $District = $data['District'];
        $province = $data['province'];
        $postalCode = $data['postalCode'];
        $phoneNumber = $data['phoneNumber'];
        $email = $data['email'];
        $startDate = $data['startDate'];
        $password = $data['password'];
        $jobPosition = $data['jobPosition'];

        $sql = "UPDATE employee SET 
                    firstName='$firstName', lastName='$lastName', address='$address', subDistrict='$subDistrict', 
                    District='$District', province='$province', postalCode='$postalCode', phoneNumber='$phoneNumber', 
                    email='$email', startDate='$startDate', password='$password', jobPosition='$jobPosition' 
                WHERE EmID='$EmID'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Handle DELETE request to delete data
        $EmID = $_GET['EmID']; // Adjust the parameter name as needed

        $sql = "DELETE FROM employee WHERE EmID='$EmID'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record deleted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error deleting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;
}
?>
