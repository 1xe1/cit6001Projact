<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=db_project", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['documentNumber'])) {
    $documentNumber = $_GET['documentNumber'];
    // Perform the delete operation here
    $stmt = $conn->prepare("DELETE FROM project_close WHERE documentNumber = :documentNumber");
    $stmt->bindParam(':documentNumber', $documentNumber);
    $stmt->execute();
    // Redirect back to the project closing management page after deletion
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Project Closings</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(documentNumber) {
            var result = confirm("Are you sure you want to delete this project closing entry?");
            if (result) {
                window.location.href = "index.php?action=delete&documentNumber=" + documentNumber;
            }
        }

    </script>
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    <div class="content">
            <h2>Project Closing List</h2>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>documentNumber</th>
                        <th>projectClosingDate</th>
                        <th>projectCode</th>
                        <th>costs</th>
                        <th>expenses</th>
                        <th>employeeCode</th>
                        <th>note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $stmt = $conn->query("SELECT * FROM project_close");
                    $projectClosings = $stmt->fetchAll();
                    foreach ($projectClosings as $closing) {
                ?>
                    <tr>
                        <td><?php echo $closing['documentNumber']; ?></td>
                        <td><?php echo $closing['projectClosingDate']; ?></td>
                        <td><?php echo $closing['projectCode']; ?></td>
                        <td><?php echo $closing['costs']; ?></td>
                        <td><?php echo $closing['expenses']; ?></td>
                        <td><?php echo $closing['employeeCode']; ?></td>
                        <td><?php echo $closing['note']; ?></td>
                        <td>
                            <button class="btn" onclick="confirmDelete('<?php echo $closing['documentNumber']; ?>')">Delete</button>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
