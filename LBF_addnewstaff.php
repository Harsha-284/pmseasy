<head>

    <?php include 'conn.php';

    include 'udf.php'; ?>
    <?php include("head.php"); ?>
    <?php include("js.php"); ?>
</head>
<style>
    .needs-validation label.form-check-label {
        margin-top: -2px;
    }

    label {
        margin-bottom: 0px;
        font-weight: 500
    }

    .form-check {
        margin-right: 15px;
        display: inline-flex;
        align-items: center;
    }

    /* Container for all permissions */
    #permissions-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    #permissions-container1 {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    /* Group each set of checkboxes */
    .permission-group {
        /* flex: 1 1 20%; */
        /* This ensures up to 3 items per row */
        margin-bottom: 15px;
        width: 168px !important;
    }

    /* Optional: Style the service label */
    .permission-group h6 {
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 500;
    }
</style>

<body style="padding: 0px; ">
    <div class="modal-body" style="overflow-y: auto; height:89%">
        <?php
        if (!isset($_GET['id'])) {
        ?>
            <div class="olddetails">
                <h5 style="font-weight:bold">New staff Details</h5>
                <h5 class="modal-title" id="dataTo2ndModal" style="display: none;"></h5>
            </div>
            <hr>

            <div class="information">
                <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap;">

                    <div id="rate-card-${0}" style="width: 778px; margin: 0 11px 15px; text-align: center; ">

                        <div style="padding: 0px 10px; font-size: 13px;">
                            <div style="display: flex; flex-direction: row; gap: 10px;  align-items: flex-start; margin-top: 2px;">
                                <label for="">User status:</label>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="2" checked>
                                    <label class="form-check-label" for="option1">
                                        Admin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="3" checked>
                                    <label class="form-check-label" for="option1">
                                        Subadmin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="4" checked>
                                    <label class="form-check-label" for="option1">
                                        Receptionist
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom01" class="form-label">First Name </label>
                        <input type="text" class="form-control" id="validationCustom01" placeholder="Enter your first name" name="first_name" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter your last name" name="last_name" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Contact Details</label>
                        <input type="number" class="form-control" id="validationCustom02" placeholder="Enter your contact details" name="contact" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Email</label>
                        <input type="email" class="form-control" id="validationCustom02" placeholder="abc@gmail.com" name="email" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Password</label>
                        <input type="password" class="form-control" id="validationCustom02" name="password" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Country</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter Country Name" name="country" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">State</label>
                        <!-- <input type="text" class="form-control" id="validationCustom02" placeholder="Enter State name" name="state" style="width:176px" required> -->
                        <select name="state" id="stateDropDown" class="form-control" required="" style="width: 176px;">
                            <option value="">State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra" selected="">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                            <option value="Daman and Diu">Daman and Diu</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="National Capital Territory of Delhi">National Capital Territory of Delhi</option>
                            <option value="Puducherry">Puducherry</option>
                            <option value="Telangana">Telangana</option>
                            <option value="New Delhi">New Delhi</option>
                        </select>

                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">City</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter city name" name="city" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">ZipCode</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter ZipCode" name="zip_code" style="width:176px" required>
                    </div>
                    <!-- <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom03" class="form-label">Identity proof</label>
                        <select name="payment_mode" id="identityproof" style="display: block;width:176px; height: 30px; border-color: gainsboro">
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Pan Card">Pan Card</option>
                            <option value="Passport">Passport</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom03" class="form-label">Identity proof</label>
                        <input type="file" class="form-control" id="fileinput-user-details-modal" style="width:176px" required>
                    </div> -->






                    <div class="submit-button" style="width: 100%;float: right;position: fixed;    text-align: center;bottom: 18" ;>
                        <a id="submitAnchor" class="btn btn-primary" style="margin: 0px 23px 0px auto; width: 60px; height:27px;" onClick="handleSubmit()">
                            Submit
                        </a>
                    </div>
                </form>

                <div class="cards-container">
                    <div class="cards-header" style="display: flex; align-items: center;justify-content: center;">
                        <div class="text-center">
                            Permissions
                        </div>
                    </div>
                    <div class="cards-body" id="permissions-container"></div>
                </div>
                <script>
                    const permissionsData = [{
                            label: "Hotel Setup",
                            permissions: ["Allow"]
                        },
                        {
                            label: "DashBoard",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Rooms",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Hotel Staff Details",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Hotel Task Log",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Assign Room",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Booking Map",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Cancellation",
                            permissions: ["Allow"]
                        },
                        {
                            label: "MIS Reports",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Invoice and GST",
                            permissions: ["Allow"]
                        },
                    ];

                    const permissionsContainer = document.getElementById("permissions-container");
                    const permissionsState = {};

                    function createPermissionGroup(service) {
                        // Create the container for the service
                        const groupDiv = document.createElement("div");
                        groupDiv.classList.add("permission-group");

                        // Create the label for the service
                        const serviceLabel = document.createElement("h6");
                        serviceLabel.textContent = service.label;
                        groupDiv.appendChild(serviceLabel);

                        // Create checkboxes for each permission
                        service.permissions.forEach(permission => {
                            const formCheckDiv = document.createElement("div");
                            formCheckDiv.classList.add("form-check");

                            const checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.classList.add("form-check-input");
                            checkbox.id = `${service.label}-${permission}`;
                            checkbox.value = permission;

                            const label = document.createElement("label");
                            label.classList.add("form-check-label");
                            label.htmlFor = checkbox.id;
                            label.textContent = permission;

                            checkbox.addEventListener("change", () => handleCheckboxChange(service.label, permission, checkbox.checked));

                            formCheckDiv.appendChild(checkbox);
                            formCheckDiv.appendChild(label);
                            groupDiv.appendChild(formCheckDiv);
                        });

                        // Initialize the state
                        permissionsState[service.label] = {};

                        service.permissions.forEach(permission => {
                            permissionsState[service.label][permission] = false;
                        });

                        permissionsContainer.appendChild(groupDiv);
                    }

                    function handleCheckboxChange(service, permission, isChecked) {
                        permissionsState[service][permission] = isChecked;
                    }

                    function savePermissions() {
                        console.log("Saved Permissions:", permissionsState);
                    }

                    // Generate the checkboxes dynamically
                    permissionsData.forEach(service => createPermissionGroup(service));


                    function handleSubmit() {
                        let first_name = document.querySelector('input[name="first_name"]').value;
                        let last_name = document.querySelector('input[name="last_name"]').value;
                        let contact = document.querySelector('input[name="contact"]').value;
                        let email = document.querySelector('input[name="email"]').value;
                        let password = document.querySelector('input[name="password"]').value;
                        let country = document.querySelector('input[name="country"]').value;
                        let state = document.getElementById('stateDropDown').value;
                        let city = document.querySelector('input[name="city"]').value;
                        let zip_code = document.querySelector('input[name="zip_code"]').value;
                        let user_status = document.querySelector('input[name="options"]:checked').value;
                        let address = city + ' ' + zip_code + ' ' + state + ' ' + country;

                        $.ajax({
                            type: 'POST',
                            url: 'bookingajax.php',
                            data: {
                                action: "add_hotel_staff",
                                fullname: `${first_name} ${last_name}`,
                                contact: contact,
                                email: email,
                                password: password,
                                address: address,
                                user_status: user_status,
                                user: <?= $_SESSION['id'] ?>,
                                hotel: <?= $_SESSION['hotel'] ?>,
                                permission: JSON.stringify(permissionsState)
                            },
                            success: function(response) {
                                let res = JSON.parse(response);
                                if (res.status === 'success') {
                                    location.reload();
                                }
                            }
                        });
                    }
                </script>


            </div>
        <?php
        } ?>

        <?php
        if (isset($_GET['id'])) {
            // echo "SELECT u.groupid,u.fullname,u.email,u.contact FROM hotel_staff hs JOIN users u ON u.id=hs.user_id WHERE hs.id=$_GET[id]";

            $row =  execute("SELECT hs.id, u.groupid,u.fullname,u.email,u.contact,u.address1,hs.permission FROM hotel_staff hs JOIN users u ON u.id=hs.user_id WHERE hs.id=$_GET[id]");

            $isAdmin = $row['groupid'] == 2 ? 'checked' : '';
            $isSubAdmin = $row['groupid'] == 3 ? 'checked' : '';
            $isReceptionist = $row['groupid'] == 4 ? 'checked' : '';

            $decoded_data = html_entity_decode($row['permission']);

            $decoded_data = trim($decoded_data, '"');

            $decoded_data = stripslashes($decoded_data);

            $data = json_decode($decoded_data, true);

        ?>
            <div class="olddetails">
                <h5 style="font-weight:bold">Staff Details</h5>
                <h5 class="modal-title" id="dataTo2ndModal" style="display: none;"></h5>
            </div>
            <hr>

            <div class="information">
                <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap;">

                    <div id="rate-card-${0}" style="width: 778px; margin: 0 11px 15px; text-align: center; ">

                        <div style="padding: 0px 10px; font-size: 13px;">
                            <div style="display: flex; flex-direction: row; gap: 10px;  align-items: flex-start; margin-top: 2px;">
                                <label for="">User status:</label>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="2" <?= $isAdmin ?>>
                                    <label class="form-check-label" for="option1">
                                        Admin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="3" <?= $isSubAdmin ?>>
                                    <label class="form-check-label" for="option1">
                                        Subadmin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input style=" float: left; margin-right: 4px; height: 13px;margin-top: -2px;" class="form-check-input" type="radio" name="options" id="option1" value="4" <?= $isReceptionist ?>>
                                    <label class="form-check-label" for="option1">
                                        Receptionist
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom01" class="form-label">Full Name </label>
                        <input disabled type="text" class="form-control" id="validationCustom01" value="<?= $row['fullname'] ?>" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Contact Details</label>
                        <input disabled type="number" class="form-control" id="validationCustom02" placeholder="Enter your contact details" value="<?= $row['contact'] ?>" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Email</label>
                        <input disabled type="email" value="<?= $row['email'] ?>" class="form-control" id="validationCustom02" style="width:176px" required>
                    </div>

                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Address</label>
                        <input disabled type="text" value="<?= $row['address1'] ?>" class="form-control" id="validationCustom02" style="width:176px" required>
                    </div>


                    <div class="submit-button" style="width: 100%;float: right;position: fixed;    text-align: center;bottom: 18" ;>
                        <a id="submitAnchor" class="btn btn-primary" style="margin: 0px 23px 0px auto; width: 60px; height:27px;" onClick="reSubmit()">
                            Submit
                        </a>
                    </div>
                </form>
                <!-- <div>
                    <div class="head available" style="text-align: center; margin-block: 20px; ">
                        Permissions
                    </div>
                    <div class="body">

                    </div>
                </div> -->
                <div class="cards-container">
                    <div class="cards-header" style="display: flex; align-items: center;justify-content: center;">
                        <div class="text-center">
                            Permissions
                        </div>
                    </div>
                    <div class="cards-body" id="permissions-container1"></div>
                </div>

                <div id="permissions-container1"></div>

                <script>
                    let obj = <?= json_encode($data) ?>;
                    const permissionsData1 = [{
                            label: "Hotel Setup",
                            permissions: ["Allow"]
                        },
                        {
                            label: "DashBoard",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Rooms",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Hotel Staff Details",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Hotel Task Log",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Assign Room",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Booking Map",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Cancellation",
                            permissions: ["Allow"]
                        },
                        {
                            label: "MIS Reports",
                            permissions: ["Allow"]
                        },
                        {
                            label: "Invoice and GST",
                            permissions: ["Allow"]
                        },
                    ];

                    // This is the data that you will be passing
                    let permissionsState1 = obj;

                    const permissionsContainer1 = document.getElementById("permissions-container1");

                    function createPermissionGroup(service) {
                        // Create the container for the service
                        const groupDiv = document.createElement("div");
                        groupDiv.classList.add("permission-group");

                        // Create the label for the service
                        const serviceLabel = document.createElement("h6");
                        serviceLabel.textContent = service.label;
                        groupDiv.appendChild(serviceLabel);

                        // Create checkboxes for each permission
                        service.permissions.forEach(permission => {
                            const formCheckDiv = document.createElement("div");
                            formCheckDiv.classList.add("form-check");

                            const checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.classList.add("form-check-input");
                            checkbox.id = `${service.label}-${permission}`;
                            checkbox.value = permission;

                            // Check if the permission is true or false in permissionsState1 and set the checkbox state
                            if (permissionsState1[service.label] && permissionsState1[service.label][permission]) {
                                checkbox.checked = true;
                            }

                            const label = document.createElement("label");
                            label.classList.add("form-check-label");
                            label.htmlFor = checkbox.id;
                            label.textContent = permission;

                            checkbox.addEventListener("change", () => handleCheckboxChange(service.label, permission, checkbox.checked));

                            formCheckDiv.appendChild(checkbox);
                            formCheckDiv.appendChild(label);
                            groupDiv.appendChild(formCheckDiv);
                        });

                        permissionsContainer1.appendChild(groupDiv); // Corrected the container reference here
                    }

                    function handleCheckboxChange(service, permission, isChecked) {
                        permissionsState1[service][permission] = isChecked;
                    }

                    function savePermissions1() {
                        console.log("Saved Permissions:", permissionsState1);
                    }

                    // Generate the checkboxes dynamically
                    permissionsData1.forEach(service => createPermissionGroup(service));


                    function reSubmit() {

                        $.ajax({
                            type: 'POST',
                            url: 'bookingajax.php',
                            data: {
                                action: "update_hotel_staff",
                                id: <?= $row['id'] ?>,
                                user: <?= $_SESSION['id'] ?>,
                                hotel: <?= $_SESSION['hotel'] ?>,
                                permission: JSON.stringify(permissionsState1)
                            },
                            success: function(response) {
                                let res = JSON.parse(response);
                                if (res.status === 'success') {
                                    location.reload();
                                }
                            }
                        });
                    }
                </script>



            </div>


        <?php
        } ?>

    </div>

</body>

</html>