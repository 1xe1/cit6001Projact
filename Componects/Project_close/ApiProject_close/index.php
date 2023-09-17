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
        if (isset($_GET['documentNumber'])) {
            $documentNumber = $_GET['documentNumber'];
            $sql = "SELECT * FROM project_close WHERE documentNumber='$documentNumber'";
        } else {
            $sql = "SELECT * FROM project_close";
        }

        $result = $conn->query($sql);
        $response = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            $response = array('status' => 'error', 'message' => 'No records found for the provided documentNumber');
        }

        echo json_encode($response);
        break;

    case 'POST':
        // Handle POST request to insert data
        $projectClosingDate = $data['projectClosingDate'];
        $projectCode = $data['projectCode'];
        $costs = $data['costs'];
        $expenses = $data['expenses'];
        $employeeCode = $data['employeeCode'];
        $note = $data['note'];

        // Insert a new record into the "project_close" table
        $sql = "INSERT INTO project_close (projectClosingDate, projectCode, costs, expenses, employeeCode, note)
                VALUES ('$projectClosingDate', '$projectCode', '$costs', '$expenses', '$employeeCode', '$note')";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request to update data
        $documentNumber = $data['documentNumber'];
        $projectClosingDate = $data['projectClosingDate'];
        $projectCode = $data['projectCode'];
        $costs = $data['costs'];
        $expenses = $data['expenses'];
        $employeeCode = $data['employeeCode'];
        $note = $data['note'];

        $sql = "UPDATE project_close SET 
                    projectClosingDate='$projectClosingDate', projectCode='$projectCode', 
                    costs='$costs', expenses='$expenses', employeeCode='$employeeCode', note='$note' 
                WHERE documentNumber='$documentNumber'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Handle DELETE request to delete data
        $documentNumber = $_GET['documentNumber']; // Adjust the parameter name as needed

        $sql = "DELETE FROM project_close WHERE documentNumber='$documentNumber'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record deleted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error deleting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;
}
?>
