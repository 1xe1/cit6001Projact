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
        if (isset($_GET['employeeCode'])) {
            $employeeCode = $_GET['employeeCode'];
            $sql = "SELECT * FROM employee WHERE employeeCode='$employeeCode'";
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
            $response = array('status' => 'error', 'message' => 'No records found for the provided employeeCode');
        }

        echo json_encode($response);
        break;

    case 'POST':
        // Handle POST request to insert data
        $employeeName = $data['employeeName'];
        $employeeRole = $data['employeeRole'];
        $employeeSalary = $data['employeeSalary'];

        // Insert a new record into the "employee" table
        $sql = "INSERT INTO employee (employeeName, employeeRole, employeeSalary)
                VALUES ('$employeeName', '$employeeRole', '$employeeSalary')";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request to update data
        $employeeCode = $data['employeeCode'];
        $employeeName = $data['employeeName'];
        $employeeRole = $data['employeeRole'];
        $employeeSalary = $data['employeeSalary'];

        $sql = "UPDATE employee SET 
                    employeeName='$employeeName', employeeRole='$employeeRole', employeeSalary='$employeeSalary' 
                WHERE employeeCode='$employeeCode'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Handle DELETE request to delete data
        $employeeCode = $_GET['employeeCode']; // Adjust the parameter name as needed

        $sql = "DELETE FROM employee WHERE employeeCode='$employeeCode'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record deleted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error deleting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;
}
?>
