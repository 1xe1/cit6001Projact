<?php 
    include('ApiCustomer/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Customers</title>
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
            <h2>Customer List</h2>
            <br>
            <div class="addbtn"><button class="btn" onclick="showInsertPopup()">Add</button></div>
            <br>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Customer Code</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Sub-District</th>
                        <th>District</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $conn->query("SELECT * FROM customer");
                        $customers = array();
                        
                        while ($row = $stmt->fetch_assoc()) {
                            $customers[] = $row;
                        }                     
                        foreach ($customers as $customer) {
                    ?>
                    <tr id="row_<?php echo $customer['cusCode']; ?>">
                        <td><?php echo $customer['cusCode']; ?></td>
                        <td><?php echo $customer['firstName']; ?></td>
                        <td><?php echo $customer['lastName']; ?></td>
                        <td><?php echo $customer['address']; ?></td>
                        <td><?php echo $customer['subDistrict']; ?></td>
                        <td><?php echo $customer['District']; ?></td>
                        <td><?php echo $customer['province']; ?></td>
                        <td><?php echo $customer['postalCode']; ?></td>
                        <td><?php echo $customer['phoneNumber']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td>
                        <button class="btn" onclick="showEditPopup('<?php echo $customer['cusCode']; ?>')">Edit</button>
                        <button class="btn" onclick="showDeleteConfirmation('<?php echo $customer['cusCode']; ?>')">Delete</button>
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
            
            <h3>Add Customer</h3>
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

                <button type="button" onclick="insertData()" class="btn">Submit</button>
            </form>
        </div>
    </div>

    <!-- The edit data modal -->
    <div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Customer</h3>
        <form id="editForm">
            <span class="close" onclick="closeEditPopup()">&times;</span>
            <input type="hidden" id="editCusCode" name="cusCode">
            <label for="editFirstName">First Name:</label>
            <input type="text" id="editFirstName" name="firstName" required><br>

            <label for="editLastName">Last Name:</label>
            <input type="text" id="editLastName" name="lastName" required><br>

            <!-- Add other fields for editing here -->
            <label for="editAddress">Address:</label>
            <input type="text" id="editAddress" name="address" required><br>

            <label for="editSubDistrict">Sub-District:</label>
            <input type="text" id="editSubDistrict" name="subDistrict" required><br>

            <label for="editDistrict">District:</label>
            <input type="text" id="editDistrict" name="District" required><br>

            <label for="editProvince">Province:</label>
            <input type="text" id="editProvince" name="province" required><br>

            <label for="editPostalCode">Postal Code:</label>
            <input type="text" id="editPostalCode" name="postalCode" required><br>

            <label for="editPhoneNumber">Phone Number:</label>
            <input type="text" id="editPhoneNumber" name="phoneNumber" required><br>

            <label for="editEmail">Email:</label>
            <input type="text" id="editEmail" name="email" required><br>

            <!-- Include other edited fields here -->

            <button type="button" onclick="updateData()" class="btn">Save Changes</button>
        </form>
    </div>
    </div>

    <!-- The modal for delete confirmation -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this customer?</p>
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

            const data = {
                firstName: firstName,
                lastName: lastName,
                address: address,
                subDistrict: subDistrict,
                District: District,
                province: province,
                postalCode: postalCode,
                phoneNumber: phoneNumber,
                email: email
            };

            const xhr = new XMLHttpRequest();
            const url = "ApiCustomer/index.php";

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
        function showDeleteConfirmation(cusCode) {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'block';

            // Store the customer code in a data attribute for later use
            modal.setAttribute('data-cusCode', cusCode);
        }

        // Close the delete confirmation modal
        function closeDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            modal.style.display = 'none';
        }

        // Confirm the delete operation
        function confirmDelete() {
            const modal = document.getElementById('deleteConfirmationModal');
            const cusCode = modal.getAttribute('data-cusCode');

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Define the URL for the delete action
            const url = "ApiCustomer/index.php?cusCode=" + cusCode; // Adjust the URL as needed

            xhr.open("DELETE", url, true); // Use DELETE for DELETE operation

            // Define the callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            const row = document.getElementById('row_' + cusCode);
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
    <script>
            // Function to show the edit data modal
        function showEditPopup(cusCode) {
            const modal = document.getElementById('editModal');
            modal.style.display = 'block';
        
            // Fetch customer data based on cusCode and populate the edit form
            const xhr = new XMLHttpRequest();
            const url = "ApiCustomer/index.php?cusCode=" + cusCode;
        
            xhr.open("GET", url, true);
        
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const customer = JSON.parse(xhr.responseText)[0];
                        document.getElementById('editCusCode').value = customer.cusCode;
                        document.getElementById('editFirstName').value = customer.firstName;
                        document.getElementById('editLastName').value = customer.lastName;
                        document.getElementById('editAddress').value = customer.address;
                        document.getElementById('editSubDistrict').value = customer.subDistrict;
                        document.getElementById('editDistrict').value = customer.District;
                        document.getElementById('editProvince').value = customer.province;
                        document.getElementById('editPostalCode').value = customer.postalCode;
                        document.getElementById('editPhoneNumber').value = customer.phoneNumber;
                        document.getElementById('editEmail').value = customer.email;
                    } else {
                        alert("Error: " + xhr.status);
                    }
                }
            };
        
            xhr.send();
        }

        // Function to close the edit data modal
        function closeEditPopup() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        // Function to update customer data
        function updateData() {
            const cusCode = document.getElementById('editCusCode').value;
            const firstName = document.getElementById('editFirstName').value;
            const lastName = document.getElementById('editLastName').value;
            const address = document.getElementById('editAddress').value;
            const subDistrict = document.getElementById('editSubDistrict').value;
            const district = document.getElementById('editDistrict').value;
            const province = document.getElementById('editProvince').value;
            const postalCode = document.getElementById('editPostalCode').value;
            const phoneNumber = document.getElementById('editPhoneNumber').value;
            const email = document.getElementById('editEmail').value;
        
            const data = {
                cusCode: cusCode,
                firstName: firstName,
                lastName: lastName,
                address: address,
                subDistrict: subDistrict,
                District: district,
                province: province,
                postalCode: postalCode,
                phoneNumber: phoneNumber,
                email: email,
            };
        
            const xhr = new XMLHttpRequest();
            const url = "ApiCustomer/index.php";
        
            xhr.open("PUT", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
        
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            closeEditPopup();
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
</body>
</html>
