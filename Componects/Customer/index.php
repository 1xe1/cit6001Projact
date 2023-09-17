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

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['cusCode'])) {
    $cusCode = $_GET['cusCode'];
    // Perform the delete operation for a customer here
    $stmt = $conn->prepare("DELETE FROM customer WHERE cusCode = :cusCode");
    $stmt->bindParam(':cusCode', $cusCode);
    $stmt->execute();
    // Redirect back to the customer management page after deletion
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(cusCode) {
            var result = confirm("Are you sure you want to delete the customer?");
            if (result) {
                window.location.href = "index.php?action=delete&cusCode=" + cusCode;
            }
        }
    </script>
    
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    <div class="content">
            <h2>Customer List</h2>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>cusCode</th>
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
                $stmt = $conn->query("SELECT * FROM customer");
                $customers = $stmt->fetchAll();
                foreach ($customers as $customer) {
            ?>
                    <tr>
                        <td><?php echo $customer['cusCode']; ?></td>
                        <td><?php echo $customer['firstName']; ?></td>
                        <td><?php echo $customer['lastName']; ?></td>
                        <td><?php echo $customer['address']; ?></td>
                        <td><?php echo $customer['subDistrict']; ?></td>
                        <td><?php echo $customer['District']; ?></td>
                        <td><?php echo $customer['province']; ?></td>
                        <td><?php echo $customer['postalCode']; ?></td>
                        <td><?php echo $customer['phoneNumber']; ?></td>
                        <td>
                            <button class="btn"><a href='add_Cus.php?cusCode=<?php echo $customer['cusCode']; ?>'>Edit</a></button>
                            <button class="btn" onclick="confirmDelete('<?php echo $customer['cusCode']; ?>')">Delete</button>
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
