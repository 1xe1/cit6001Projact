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

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['productCode'])) {
    $productCode = $_GET['productCode'];
    // Perform the delete operation for a stock item here
    $stmt = $conn->prepare("DELETE FROM stock WHERE productCode = :productCode");
    $stmt->bindParam(':productCode', $productCode);
    $stmt->execute();
    // Redirect back to the stock management page after deletion
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Stock</title>
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        function confirmDelete(productCode) {
            var result = confirm("Are you sure you want to delete the product?");
            if (result) {
                window.location.href = "index.php?action=delete&productCode=" + productCode;
            }
        }
    </script>
    
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
    <div class="content">
            <h2>Stock List</h2>
            <table id="myTable" class="display">
    <thead>
        <tr>
            <th>productCode</th>
            <th>productName</th>
            <th>unitCount</th>
            <th>priceUnit</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $stmt = $conn->query("SELECT * FROM stock");
        $stocks = $stmt->fetchAll();
        foreach ($stocks as $stock) {
    ?>
            <tr>
                <td><?php echo $stock['productCode']; ?></td>
                <td><?php echo $stock['productName']; ?></td>
                <td><?php echo $stock['unitCount']; ?></td>
                <td><?php echo $stock['priceUnit']; ?></td>
                <td>
                    <button class="btn"><a href='add_Stock.php?productCode=<?php echo $stock['productCode']; ?>'>Edit</a></button>
                    <button class="btn" onclick="confirmDelete('<?php echo $stock['productCode']; ?>')">Delete</button>
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
