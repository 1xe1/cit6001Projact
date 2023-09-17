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
        if (isset($_GET['projectCode'])) {
            $projectCode = $_GET['projectCode'];
            $sql = "SELECT * FROM project WHERE ProjectCode='$projectCode'";
        } else {
            $sql = "SELECT * FROM project";
        }

        $result = $conn->query($sql);
        $response = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            $response = array('status' => 'error', 'message' => 'No records found for the provided projectCode');
        }

        echo json_encode($response);
        break;

    case 'POST':
        // Handle POST request to insert data
        $projectName = $data['projectName'];
        $cusCode = $data['cusCode'];
        $projectStartDate = $data['projectStartDate'];
        $projectEndDate = $data['projectEndDate'];
        $projectValue = $data['projectValue'];
        $EmID = $data['EmID'];
        $projectStatus = $data['projectStatus'];

        // Insert a new record into the "project" table
        $sql = "INSERT INTO project (projectName, cusCode, projectStartDate, projectEndDate, projectValue, EmID, projectStatus)
                VALUES ('$projectName', '$cusCode', '$projectStartDate', '$projectEndDate', '$projectValue', '$EmID', '$projectStatus')";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request to update data
        $projectCode = $data['projectCode'];
        $projectName = $data['projectName'];
        $cusCode = $data['cusCode'];
        $projectStartDate = $data['projectStartDate'];
        $projectEndDate = $data['projectEndDate'];
        $projectValue = $data['projectValue'];
        $EmID = $data['EmID'];
        $projectStatus = $data['projectStatus'];

        $sql = "UPDATE project SET 
                    projectName='$projectName', cusCode='$cusCode', projectStartDate='$projectStartDate', 
                    projectEndDate='$projectEndDate', projectValue='$projectValue', EmID='$EmID', 
                    projectStatus='$projectStatus' 
                WHERE ProjectCode='$projectCode'";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error);
        }

        echo json_encode($response);
        break;

        case 'DELETE':
            // Handle DELETE request to delete data
            $projectCode = $_GET['projectCode']; // Adjust the parameter name as needed
        
            $sql = "DELETE FROM project WHERE ProjectCode='$projectCode'";
        
            if ($conn->query($sql) === TRUE) {
                $response = array('status' => 'success', 'message' => 'Record deleted successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error deleting record: ' . $conn->error);
            }
        
            echo json_encode($response);
            break;
        
        
}
?>
