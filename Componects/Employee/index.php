<?php 
    include('ApiEmployee/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Employees</title>
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
            <h2>Employee List</h2>
            <br>
            <div class="addbtn"><button class="btn" onclick="showInsertPopup()">Add</button></div>
            <br>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Sub-District</th>
                        <th>District</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Start Date</th>
                        <th>Password</th>
                        <th>Job Position</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $conn->query("SELECT * FROM employee");
                        $employees = array();
                        
                        while ($row = $stmt->fetch_assoc()) {
                            $employees[] = $row;
                        }                     
                        foreach ($employees as $employee) {
                    ?>
                    <tr id="row_<?php echo $employee['EmID']; ?>">
                        <td><?php echo $employee['EmID']; ?></td>
                        <td><?php echo $employee['firstName']; ?></td>
                        <td><?php echo $employee['lastName']; ?></td>
                        <td><?php echo $employee['address']; ?></td>
                        <td><?php echo $employee['subDistrict']; ?></td>
                        <td><?php echo $employee['District']; ?></td>
                        <td><?php echo $employee['province']; ?></td>
                        <td><?php echo $employee['postalCode']; ?></td>
                        <td><?php echo $employee['phoneNumber']; ?></td>
                        <td><?php echo $employee['email']; ?></td>
                        <td><?php echo $employee['startDate']; ?></td>
                        <td><?php echo $employee['password']; ?></td>
                        <td><?php echo $employee['jobPosition']; ?></td>
                        <td>
                            <button class="btn" onclick="showDeleteConfirmation('<?php echo $employee['EmID']; ?>')">Delete</button>
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
            <h3>Add Employee</h3>
            <form id="insertForm">
            <span class="close" onclick="closeInsertPopup()">&times;</span>
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" required><br>

                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required><br>

                <label for="subDistrict">Sub-District:</label>
                <input type="text" id="subDistrict" name="subDistrict" required><br>

                <label for="District">District:</label>
                <input type="text" id="District" name="District" required><br>

                <label for="province">Province:</label>
                <input type="text" id="province" name="province" required><br>

                <label for="postalCode">Postal Code:</label>
                <input type="text" id="postalCode" name="postalCode" required><br>

                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" required><br>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required><br>

                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <label for="jobPosition">Job Position:</label>
                <input type="text" id="jobPosition" name="jobPosition" required><br>

                <button type="button" onclick="insertData()" class="btn">Submit</button>
            </form>
        </div>
    </div>

    <!-- The modal for delete confirmation -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this employee?</p>
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
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const address = document.getElementById('address').value;
            const subDistrict = document.getElementById('subDistrict').value;
            const District = document.getElementById('District').value;
            const province = document.getElementById('province').value;
            const postalCode = document.getElementById('postalCode').value;
            const phoneNumber = document.getElementById('phoneNumber').value;
            const email = document.getElementById('email').value;
            const startDate = document.getElementById('startDate').value;
            const password = document.getElementById('password').value;
            const jobPosition = document.getElementById('jobPosition').value;

            const data = {
                firstName: firstName,
                lastName: lastName,
                address: address,
                subDistrict: subDistrict,
                District: District,
                province: province,
                postalCode: postalCode,
                phoneNumber: phoneNumber,
                email: email,
                startDate: startDate,
                password: password,
                jobPosition: jobPosition
            };

            const xhr = new XMLHttpRequest();
            const url = "ApiEmployee/index.php";

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
        function showDeleteConfirmation(EmID) {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'block';

            // Store the employee ID in a data attribute for later use
            modal.setAttribute('data-EmID', EmID);
        }

        // Close the delete confirmation modal
        function closeDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'none';
        }

        // Confirm the delete operation
        function confirmDelete() {
            const modal = document.getElementById('deleteConfirmationModal');
            const EmID = modal.getAttribute('data-EmID');

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Define the URL for the delete action
            const url = "ApiEmployee/index.php?EmID=" + EmID; // Adjust the URL as needed

            xhr.open("DELETE", url, true); // Use DELETE for DELETE operation

            // Define the callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            const row = document.getElementById('row_' + EmID);
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
