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
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['username'])) {
    $username = $_GET['username'];
    // Perform the delete operation here
    $stmt = $conn->prepare("DELETE FROM customer WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(username) {
            var result = confirm("Are you sure you want to delete the user?");
            if (result) {
                window.location.href = "your_page.php?action=delete&username=" + username;
            }
        }
    </script>
    
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    
    </section>
</body>
</html>