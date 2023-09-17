<?php 
    include('ApiProduct/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="../../Sidebar.css">
    <link rel="stylesheet" href="../Show/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>
<body>
    <?php include('../../Sidebar.php');?>
    <section class="home">
        <div class="content">
            <h2>Product List</h2>
            <br>
            <div class="addbtn"><button class="btn" onclick="showInsertPopup()">Add</button></div>
            <br>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>ProductCode</th>
                        <th>ProductName</th>
                        <th>UnitCount</th>
                        <th>PriceUnit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $conn->query("SELECT * FROM stock");
                        $products = array();
                        
                        while ($row = $stmt->fetch_assoc()) {
                            $products[] = $row;
                        }                     
                        foreach ($products as $product) {
                    ?>
                    <tr id="row_<?php echo $product['productCode']; ?>">
                        <td><?php echo $product['productCode']; ?></td>
                        <td><?php echo $product['productName']; ?></td>
                        <td><?php echo $product['unitCount']; ?></td>
                        <td><?php echo $product['priceUnit']; ?></td>
                        <td>
                            <button class="btn" onclick="showDeleteConfirmation('<?php echo $product['productCode']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- The insert data modal -->
    <div id="insertModal" class="modal">
        <div class="modal-content">
            <h3>Add Product</h3>
            <form id="insertForm">
            <span class="close" onclick="closeInsertPopup()">&times;</span>
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required><br>

                <label for="unitCount">Unit Count:</label>
                <input type="text" id="unitCount" name="unitCount" required><br>

                <label for="priceUnit">Price Unit:</label>
                <input type="text" id="priceUnit" name="priceUnit" required><br>

                <button type="button" onclick="insertData()" class="btn">Submit</button>
            </form>
        </div>
    </div>

    <!-- The modal for delete confirmation -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this product?</p>
            <div class="modal-buttons">
                <button onclick="confirmDelete()" class="btn">Yes</button>
                <button onclick="closeDeleteConfirmation()" class="btn">No</button>
            </div>
        </div>
    </div>

    <!-- ------------------Add----------------------- -->
    <script>
        // Function to show the insert data modal
        function showInsertPopup() {
            const modal = document.getElementById('insertModal');
            modal.style.display = 'block';
        }

        // Function to close the insert data modal
        function closeInsertPopup() {
            const modal = document.getElementById('insertModal');
            modal.style.display = 'none';
        }

        // Function to insert data
        function insertData() {
            const productName = document.getElementById('productName').value;
            const unitCount = document.getElementById('unitCount').value;
            const priceUnit = document.getElementById('priceUnit').value;

            const data = {
                productName: productName,
                unitCount: unitCount,
                priceUnit: priceUnit
            };

            const xhr = new XMLHttpRequest();
            const url = "ApiProduct/index.php";

            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            closeInsertPopup();
                            window.location.reload(); // Reload the page to show the updated data
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert("Error: " + xhr.status);
                    }
                }
            };

            xhr.send(JSON.stringify(data));
        }
    </script>
    <!-- ----------------------delete--------------------- -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        // Show the delete confirmation modal
        function showDeleteConfirmation(productCode) {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'block';

            // Store the product code in a data attribute for later use
            modal.setAttribute('data-product-code', productCode);
        }

        // Close the delete confirmation modal
        function closeDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'none';
        }

        // Confirm the delete operation
        function confirmDelete() {
            const modal = document.getElementById('deleteConfirmationModal');
            const productCode = modal.getAttribute('data-product-code');

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Define the URL for the delete action
            const url = "ApiProduct/index.php?productCode=" + productCode; // Adjust the URL as needed

            xhr.open("DELETE", url, true); // Use DELETE for DELETE operation

            // Define the callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            const row = document.getElementById('row_' + productCode);
                            row.parentNode.removeChild(row); // Remove the deleted row from the table
                        } else {
                            alert(response.message);
                        }
                        closeDeleteConfirmation();
                    } else {
                        alert("Error: " + xhr.status);
                    }
                }
            };

            // Send the DELETE request
            xhr.send();
        }
    </script>
</body>
</html>
