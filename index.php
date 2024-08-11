<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="./index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
</head>

<body>
    <div class="container text-center">
        <img src="./admin/images/super-express-cargo.png" alt="Super Express Cargo Logo" class="logo" />
        <h1 class="display-4">Super Express Cargo</h1>
        <form class="mt-4" method="post" action="index.php">
            <div class="form-group mb-3">
                <label for="cnNumber">Track Your Shipment</label>
                <input type="text" class="form-control" id="cnNumber" name="cnNumber"
                    placeholder="Enter Receipt Number" />
            </div>
            <button type="submit" class="btn btn-primary">Track Shipment</button>
        </form>
        <div>
            <!-- Include database config -->
            <?php include './admin/config.php';



            ?>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <?php
                // Get CN number from POST request
                $cnNumber = $_POST['cnNumber'];

                // Prepare and execute query
                $stmt = $con->prepare("SELECT status, shipper_name, consignee_name FROM shipments WHERE receipt_no = ?");
                $stmt->bind_param("s", $cnNumber);
                $stmt->execute();
                $stmt->store_result();

                // Check if any result is found
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($status, $shipperName, $consigneeName);
                    $stmt->fetch();

                    // Determine status class
                    $statusClass = '';
                    if ($status === 'DISPATCHED') {
                        $statusClass = 'status-dispatched';
                    } elseif ($status === 'DELIVERED') {
                        $statusClass = 'status-delivered';
                    }
                    ?>
                    <!-- <div class="card mt-4 mx-auto" style="max-width: 600px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between w-100">
                                <div class="w-50 text-start">
                                    <p class="card-text"><strong>Receipt No:</strong></p>
                                    <p class="card-text"><strong>Status:</strong></p>
                                    <p class="card-text"><strong>Consignee:</strong></p>
                                    <p class="card-text"><strong>Shipper:</strong></p>
                                </div>
                                <div class="w-50 text-end">
                                    <p class="card-text fw-bold reciept_value"><?php echo htmlspecialchars($cnNumber); ?></p>
                                    <p class="card-text fw-bold <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </p>
                                    <p class="card-text"><?php echo htmlspecialchars($consigneeName); ?></p>
                                    <p class="card-text"><?php echo htmlspecialchars($shipperName); ?></p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="table-responsive mt-4 mx-auto" style="max-width: 600px;">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Receipt No:</th>
                                    <td class="fw-bold receipt_value"><?php echo htmlspecialchars($cnNumber); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Status:</th>
                                    <td class="fw-bold <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Consignee:</th>
                                    <td><?php echo htmlspecialchars($consigneeName); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Shipper:</th>
                                    <td><?php echo htmlspecialchars($shipperName); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php
                } else {
                    echo '<div class="alert alert-danger mt-4" role="alert">';
                    echo 'No records found for CN Number ' . htmlspecialchars($cnNumber);
                    echo '</div>';
                }

                $stmt->close();
                $con->close();
                ?>
            <?php endif; ?>

        </div>
        <!-- Footer -->
        <footer class="footer">
            <p>Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi</p>
            <p>Contact: 0321-9285851, 0321-8756687</p>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
</body>

</html>