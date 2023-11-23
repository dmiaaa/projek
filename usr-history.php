<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['u_id'];

if (isset($_POST['month'])) {
    $selectedMonth = $_POST['month'];
    $ret = "SELECT * FROM tms_user WHERE (u_car_book_status = 'Approved' OR u_car_book_status = 'Pending') AND DATE_FORMAT(u_car_bookdate, '%M %Y') = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param("s", $selectedMonth);
    $stmt->execute();
    $res = $stmt->get_result();
    $cnt = 1;

    while ($row = $res->fetch_object()) {
        echo "<tr>";
        echo "<td>{$row->u_fname} {$row->u_lname}</td>";
        echo "<td>{$row->u_phone}</td>";
        echo "<td>{$row->u_car_type}</td>";
        echo "<td>{$row->u_pic}</td>";
        echo "<td>{$row->u_car_regno}</td>";
        echo "<td>{$row->u_car_bookdate}</td>";
        echo "<td>{$row->u_startbooktime}</td>";
        echo "<td>{$row->u_endbooktime}</td>";
        echo "<td>";
        if ($row->u_car_book_status == "Pending") {
            echo '<span class="badge badge-warning">' . $row->u_car_book_status . '</span>';
        } else {
            echo '<span class="badge badge-success">' . $row->u_car_book_status . '</span>';
        }
        echo "</td>";
        echo "</tr>";
    }

    // Terminate the script after loading history via AJAX
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<title>CleanConnect Pro - History</title>
<?php include("vendor/inc/head.php");?>
<style>
    #dataTable th {
        white-space: nowrap;
    }

    #dataTable th.Name,
    #dataTable th.Phone,
    #dataTable th.Location,
    #dataTable th.PICName,
    #dataTable th.PICNoPhone,
    #dataTable th.BookDate,
    #dataTable th.StartBookTime,
    #dataTable th.EndBookTime,
    #dataTable th.Status {
        width: auto; /* Adjust the width as needed */
    }
</style>
<body id="page-top">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-...your-integrity-hash-here..." crossorigin="anonymous" />

    <!--Start Navigation Bar-->
    <?php include("vendor/inc/nav.php");?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php");?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs and Month Selection -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="user-dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item  active ">History</li>
                </ol>
                <div class="form-group">
                    <label for="monthSelect">Select Month:</label>
                    <select class="form-control" id="monthSelect" name="monthSelect" onchange="loadHistory()">
                        <option value="">-- Select Month --</option>
                        <?php
                        // Generate options for the last 12 months
                        for ($i = 0; $i < 12; $i++) {
                            $month = date('F Y', strtotime("-$i month"));
                            echo "<option value=\"$month\">$month</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- My Bookings Table -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-history"></i>
                        View My History
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>PIC Name</th>
                                        <th>PIC No Phone</th>
                                        <th>Booking date</th>
                                        <th>Start Booking Time</th>
                                        <th>End Booking time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <!-- Table content will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

            <!-- Sticky Footer -->
            <?php include("vendor/inc/footer.php");?>

        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="user-logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="vendor/js/sb-admin.min.js"></script>

    <!-- Load History Script -->
    <script>
        function loadHistory() {
            var selectedMonth = document.getElementById("monthSelect").value;
            // Perform AJAX request to load bookings for the selected month
            $.ajax({
                url: "usr-history.php", // Same file handles the request
                type: "POST",
                data: { month: selectedMonth },
                success: function(response) {
                    // Update the table with the new data
                    $("#historyTableBody").html(response);
                }
            });
        }
    </script>
</body>

</html>
