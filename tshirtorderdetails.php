<?php
include 'conn.php'; // Include your database connection file

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM t_shirt_order_details WHERE order_id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Order deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting order: " . $conn->error . "');</script>";
    }
}

// Handle status update request
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE t_shirt_order_details SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch T-shirt orders
$sql = "SELECT * FROM t_shirt_order_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page  T-shirts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        table {
            padding: 0;
        }

        img.screenshot {
            width: 100px;
            height: auto;
        }

        .status-badge {
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            color: white;
        }

        .pending {
            background-color: orange;
        }

        .confirmed {
            background-color: blue;
        }

        .packed {
            background-color: purple;
        }

        .shipped {
            background-color: teal;
        }

        .delivered {
            background-color: green;
        }

        select {
            border: none;
            font-size: 15px;
            padding: 0.5em;
            border-radius: 0.5rem;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="mt-5 text-center p-3">
        <h2 class="mb-4">T-Shirt Order Management</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Customer Email</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Size</th>
                        <th scope="col">Color</th>
                        <th scope="col">Custom Message</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Payment Screenshot</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $serial_no = 1; 
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $serial_no++ . "</th>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            echo "<td>" . $row['customer_email'] . "</td>";
                            echo "<td>" . ($row['payment'] ? 'Paid' : 'Unpaid') . "</td>";
                            echo "<td>" . $row['transaction_id'] . "</td>";
                            echo "<td>" . $row['size'] . "</td>"; // Size column
                            echo "<td>" . $row['color'] . "</td>"; // Color column
                            echo "<td>" . $row['custom_message'] . "</td>"; // Custom Message column
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['order_date'] . "</td>";
                            echo "<td>";

                            // Display status badge
                            $status_class = strtolower($row['status']);
                            echo "<form method='POST' style='display:inline;'>";
                            echo "<select name='new_status' onchange='this.form.submit()' class='" . $status_class . "'>";
                            echo "<option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Pending</option>";
                            echo "<option value='confirmed' " . ($row['status'] == 'confirmed' ? 'selected' : '') . ">Confirmed</option>";
                            echo "<option value='packed' " . ($row['status'] == 'packed' ? 'selected' : '') . ">Packed</option>";
                            echo "<option value='shipped' " . ($row['status'] == 'shipped' ? 'selected' : '') . ">Shipped</option>";
                            echo "<option value='delivered' " . ($row['status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>";
                            echo "</select>";
                            echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>";
                            echo "<input type='hidden' name='update_status' value='1'>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td>";
                            if (!empty($row['screenshot'])) {
                                echo "<img src='uploads/" . $row['screenshot'] . "' class='screenshot' alt='Payment Screenshot'>";
                            } else {
                                echo "No screenshot";
                            }
                            echo "</td>";
                            echo "<td>
                                    <a href='?delete_id=" . $row['order_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13' class='text-center'>No orders found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
