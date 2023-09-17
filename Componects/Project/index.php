<?php 
    include('ApiProject/connection.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Projects</title>
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
            <h2>Project List</h2>
            <br>
            <div class="addbtn"><button class="btn" onclick="showInsertPopup()">Add</button></div>
            <br>
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
                        $projects = array();
                        
                        while ($row = $stmt->fetch_assoc()) {
                            $projects[] = $row;
                        }                     
                        foreach ($projects as $project) {
                    ?>
                    <tr id="row_<?php echo $project['ProjectCode']; ?>">
                        <td><?php echo $project['ProjectCode']; ?></td>
                        <td><?php echo $project['projectName']; ?></td>
                        <td><?php echo $project['cusCode']; ?></td>
                        <td><?php echo $project['projectStartDate']; ?></td>
                        <td><?php echo $project['projectEndDate']; ?></td>
                        <td><?php echo $project['projectValue']; ?></td>
                        <td><?php echo $project['EmID']; ?></td>
                        <td><?php echo $project['projectStatus']; ?></td>
                        <td>
                            <button class="btn" onclick="showDeleteConfirmation('<?php echo $project['ProjectCode']; ?>')">Delete</button>
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
            <h3>Add Project</h3>
            <form id="insertForm">
            <span class="close" onclick="closeInsertPopup()">&times;</span>
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName" name="projectName" required><br>

                <label for="cusCode">Customer Code:</label>
                <input type="text" id="cusCode" name="cusCode" required><br>

                <label for="projectStartDate">Start Date:</label>
                <input type="date" id="projectStartDate" name="projectStartDate" required><br>

                <label for="projectEndDate">End Date:</label>
                <input type="date" id="projectEndDate" name="projectEndDate" required><br>

                <label for="projectValue">Project Value:</label>
                <input type="text" id="projectValue" name="projectValue" required><br>

                <label for="EmID">Employee ID:</label>
                <input type="text" id="EmID" name="EmID" required><br>

                <label for="projectStatus">Project Status:</label>
                <input type="text" id="projectStatus" name="projectStatus" required><br>

                <button type="button" onclick="insertData()" class="btn">Submit</button>
            </form>
        </div>
    </div>

    <!-- The modal for delete confirmation -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this project?</p>
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
            const projectName = document.getElementById('projectName').value;
            const cusCode = document.getElementById('cusCode').value;
            const projectStartDate = document.getElementById('projectStartDate').value;
            const projectEndDate = document.getElementById('projectEndDate').value;
            const projectValue = document.getElementById('projectValue').value;
            const EmID = document.getElementById('EmID').value;
            const projectStatus = document.getElementById('projectStatus').value;

            const data = {
                projectName: projectName,
                cusCode: cusCode,
                projectStartDate: projectStartDate,
                projectEndDate: projectEndDate,
                projectValue: projectValue,
                EmID: EmID,
                projectStatus: projectStatus
            };

            const xhr = new XMLHttpRequest();
            const url = "ApiProject/index.php";

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
        function showDeleteConfirmation(ProjectCode) {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'block';

            // Store the project code in a data attribute for later use
            modal.setAttribute('data-project-code', ProjectCode);
        }

        // Close the delete confirmation modal
        function closeDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'none';
        }

        // Confirm the delete operation
        function confirmDelete() {
            const modal = document.getElementById('deleteConfirmationModal');
            const projectCode = modal.getAttribute('data-project-code');

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Define the URL for the delete action
            const url = "ApiProject/index.php?projectCode=" + projectCode; // Adjust the URL as needed

            xhr.open("DELETE", url, true); // Use DELETE for DELETE operation

            // Define the callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            const row = document.getElementById('row_' + projectCode);
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
