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

// Check if the delete action is triggered
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['ProjectCode'])) {
    $projectCode = $_GET['ProjectCode'];
    // Perform the delete operation here
    $stmt = $conn->prepare("DELETE FROM project WHERE ProjectCode = :projectCode");
    $stmt->bindParam(':projectCode', $projectCode);
    $stmt->execute();
    // Redirect back to the project management page after deletion
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Projects</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(ProjectCode) {
            var result = confirm("Are you sure you want to delete this project?");
            if (result) {
                window.location.href = "index.php?action=delete&ProjectCode=" + ProjectCode;
            }
        }
    </script>
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    <div class="content">
            <h2>Project List</h2>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>ProjectCode</th>
                        <th>projectName</th>
                        <th>cusCode</th>
                        <th>projectStartDate</th>
                        <th>projectEndDate</th>
                        <th>projectValue</th>
                        <th>EmID</th>
                        <th>projectStatus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $stmt = $conn->query("SELECT * FROM project");
                    $projects = $stmt->fetchAll();
                    foreach ($projects as $project) {
                ?>
                    <tr>
                        <td><?php echo $project['ProjectCode']; ?></td>
                        <td><?php echo $project['projectName']; ?></td>
                        <td><?php echo $project['cusCode']; ?></td>
                        <td><?php echo $project['projectStartDate']; ?></td>
                        <td><?php echo $project['projectEndDate']; ?></td>
                        <td><?php echo $project['projectValue']; ?></td>
                        <td><?php echo $project['EmID']; ?></td>
                        <td><?php echo $project['projectStatus']; ?></td>
                        <td>
                            <button class="btn" onclick="confirmDelete('<?php echo $project['ProjectCode']; ?>')">Delete</button>
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
