<?php 
    include('ApiProject_close/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Project Closures</title>
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
            <h2>Project Closure List</h2>
            <br>
            <div class="addbtn"><button class="btn" onclick="showInsertPopup()">Add</button></div>
            <br>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Document Number</th>
                        <th>Closure Date</th>
                        <th>Project Code</th>
                        <th>Costs</th>
                        <th>Expenses</th>
                        <th>Employee Code</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $conn->query("SELECT * FROM project_close");
                        $closures = array();
                        
                        while ($row = $stmt->fetch_assoc()) {
                            $closures[] = $row;
                        }                     
                        foreach ($closures as $closure) {
                    ?>
                    <tr id="row_<?php echo $closure['documentNumber']; ?>">
                        <td><?php echo $closure['documentNumber']; ?></td>
                        <td><?php echo $closure['projectClosingDate']; ?></td>
                        <td><?php echo $closure['projectCode']; ?></td>
                        <td><?php echo $closure['costs']; ?></td>
                        <td><?php echo $closure['expenses']; ?></td>
                        <td><?php echo $closure['employeeCode']; ?></td>
                        <td><?php echo $closure['note']; ?></td>
                        <td>
                            <button class="btn" onclick="showDeleteConfirmation('<?php echo $closure['documentNumber']; ?>')">Delete</button>
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
            <h3>Add Project Closure</h3>
            <form id="insertForm">
            <span class="close" onclick="closeInsertPopup()">&times;</span>
                <label for="projectClosingDate">Closure Date:</label>
                <input type="date" id="projectClosingDate" name="projectClosingDate" required><br>

                <label for="projectCode">Project Code:</label>
                <input type="text" id="projectCode" name="projectCode" required><br>

                <label for="costs">Costs:</label>
                <input type="text" id="costs" name="costs" required><br>

                <label for="expenses">Expenses:</label>
                <input type="text" id="expenses" name="expenses" required><br>

                <label for="employeeCode">Employee Code:</label>
                <input type="text" id="employeeCode" name="employeeCode" required><br>

                <label for="note">Note:</label>
                <input type="text" id="note" name="note" required><br>

                <button type="button" onclick="insertData()" class="btn">Submit</button>
            </form>
        </div>
    </div>

    <!-- The modal for delete confirmation -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this project closure?</p>
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
            const projectClosingDate = document.getElementById('projectClosingDate').value;
            const projectCode = document.getElementById('projectCode').value;
            const costs = document.getElementById('costs').value;
            const expenses = document.getElementById('expenses').value;
            const employeeCode = document.getElementById('employeeCode').value;
            const note = document.getElementById('note').value;

            const data = {
                projectClosingDate: projectClosingDate,
                projectCode: projectCode,
                costs: costs,
                expenses: expenses,
                employeeCode: employeeCode,
                note: note
            };

            const xhr = new XMLHttpRequest();
            const url = "ApiProject_close/index.php";

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
        function showDeleteConfirmation(documentNumber) {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'block';

            // Store the document number in a data attribute for later use
            modal.setAttribute('data-document-number', documentNumber);
        }

        // Close the delete confirmation modal
        function closeDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'none';
        }

        // Confirm the delete operation
        function confirmDelete() {
            const modal = document.getElementById('deleteConfirmationModal');
            const documentNumber = modal.getAttribute('data-document-number');

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Define the URL for the delete action
            const url = "ApiProject_close/index.php?documentNumber=" + documentNumber; // Adjust the URL as needed

            xhr.open("DELETE", url, true); // Use DELETE for DELETE operation

            // Define the callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            const row = document.getElementById('row_' + documentNumber);
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
