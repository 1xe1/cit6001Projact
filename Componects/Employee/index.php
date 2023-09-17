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

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['EmID'])) {
    $EmID = $_GET['EmID'];
    // Perform the delete operation for an employee here
    $stmt = $conn->prepare("DELETE FROM employee WHERE EmID = :EmID");
    $stmt->bindParam(':EmID', $EmID);
    $stmt->execute();
    // Redirect back to the employee management page after deletion
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(EmID) {
            var result = confirm("Are you sure you want to delete the employee?");
            if (result) {
                window.location.href = "index.php?action=delete&EmID=" + EmID;
            }
        }

    </script>
    
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    <div class="content">
            <h2>Employee List</h2>
            <table id="myTable" class="display">
    <thead>
        <tr>
            <th>EmID</th>
            <th>firstName</th>
            <th>lastName</th>
            <th>address</th>
            <th>subDistrict</th>
            <th>District</th>
            <th>province</th>
            <th>postalCode</th>
            <th>phoneNumber</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $stmt = $conn->query("SELECT * FROM employee");
        $employees = $stmt->fetchAll();
        foreach ($employees as $employee) {
    ?>
            <tr>
                <td><?php echo $employee['EmID']; ?></td>
                <td><?php echo $employee['firstName']; ?></td>
                <td><?php echo $employee['lastName']; ?></td>
                <td><?php echo $employee['address']; ?></td>
                <td><?php echo $employee['subDistrict']; ?></td>
                <td><?php echo $employee['District']; ?></td>
                <td><?php echo $employee['province']; ?></td>
                <td><?php echo $employee['postalCode']; ?></td>
                <td><?php echo $employee['phoneNumber']; ?></td>
                <td>
                    <button class="btn"><a href='add_Cus.php?EmID=<?php echo $employee['EmID']; ?>'>Edit</a></button>
                    <button class="btn" onclick="confirmDelete('<?php echo $employee['EmID']; ?>')">Delete</button>
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
