<?php
$success = false;
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if customer email exists in session
    if (isset($_SESSION['customer_email'])) {
        $product_name = "Couple T-shirt";
        $customer_email = $_SESSION['customer_email'];
        $payment = true;
        $transaction_id = $_POST['transaction_id'];
        $screenshot = $_FILES['screenshot']['name'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $custom_message = $_POST['message'];
        $quantity = $_POST['quantity'];
        $status = "pending";
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $target_file = $target_dir . basename($screenshot);
        if (move_uploaded_file($_FILES["screenshot"]["tmp_name"], $target_file)) {

            // Prepare and bind the insert statement
            $stmt = $conn->prepare("INSERT INTO t_shirt_order_details (product_name, customer_email, payment, transaction_id, screenshot, size, color, custom_message, quantity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisssssds", $product_name, $customer_email, $payment, $transaction_id, $screenshot, $size, $color, $custom_message, $quantity, $status);

            if ($stmt->execute()) {
                $success = true;
                echo "<script>alert('Order placed successfully!');</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error uploading file. Please try again.');</script>";
        }

        $conn->close();
    } else {
        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couple T-shirt Product Page</title>
    <link rel="stylesheet" href="productpage.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .modal-content h2 {
            margin-bottom: 20px;
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }

        .payment-modal-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .payment-modal-content img {
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }

        .payment-modal-content input[type="text"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .product-options {
            margin-bottom: 15px;
        }

        .product-options label {
            font-size: 16px;
            font-weight: 600;
            margin-right: 10px;
        }

        .product-options select,
        .product-options input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: auto;
        }

        .product-options input.color-picker {
            width: 50px;
            padding: 0;
            border: none;
        }

        .custom-message textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="product-page">
        <div class="product">
            <div class="one">
                <div class="product-img"><img src="tshirt1.JPG" alt="Product Image"></div>
            </div>
            <div class="two">
                <div class="product-text1">
                    <h1>Couple T-shirt</h1>
                    <p>$10</p>
                </div>
                <div class="product-text2">
                    <div class="addtocart">
                        <button class="add" onclick="decrementCount()">-</button>
                        <p class="count">1</p>
                        <button class="sub" onclick="incrementCount()">+</button>
                        <button class="card">Add to Cart</button>
                    </div>
                </div>

                <!-- Size, Color, and Custom Message Inputs -->
                <div class="product-options">
                    <label for="size">Size:</label>
                    <select id="size" name="size">
                        <option value="S">Small</option>
                        <option value="M">Medium</option>
                        <option value="L">Large</option>
                        <option value="XL">Extra Large</option>
                    </select>
                </div>

                <div class="product-options">
                    <label for="color">Color:</label>
                    <input type="color" id="color" name="color" value="#ff0000" class="color-picker">
                </div>

                <div class="product-options custom-message">
                    <label for="message">Custom Message:</label>
                    <textarea id="message" name="message" placeholder="Add a message here..."></textarea>
                </div>

                <div class="product-text3">
                    <button onclick="showPaymentModal()">Buy Now</button>
                </div>

                <div class="product-text4">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam neque earum numquam incidunt corrupti, fugit assumenda amet officia delectus molestiae voluptates! Tenetur porro totam corporis maxime perspiciatis iste pariatur cumque aut quo iusto libero qui, quis dolorum magni ratione dolor earum consectetur, ducimus molestiae dolorem eaque, ab eos? Quod, dignissimos.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content payment-modal-content">
            <h2>Complete Payment</h2>
            <p>Scan the QR code below and enter the UPI transaction ID.</p>
            <img src="qrcode.png" alt="QR Code">
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Hidden fields to capture size, color, and custom message -->
                <input type="hidden" name="size" id="modalSize" value="">
                <input type="hidden" name="color" id="modalColor" value="">
                <input type="hidden" name="message" id="modalMessage" value="">
                <input type="hidden" name="quantity" id="quantityInput" value="1">
                <input type="text" name="transaction_id" placeholder="Enter UPI Transaction ID" required>
                <input type="file" name="screenshot" accept="image/*" required>
                <button type="submit" name="place_order">Submit Payment</button>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2>Order Placed Successfully!</h2>
            <p>Your order has been placed successfully. Thank you for your purchase!</p>
            <button onclick="closeSuccessModal()">OK</button>
        </div>
    </div>

    <script>
        let count = 1;

        // Function to increment the quantity
        function incrementCount() {
            count++;
            document.querySelector('.count').textContent = count;
            document.getElementById('quantityInput').value = count; // Set hidden input value
        }

        // Function to decrement the quantity
        function decrementCount() {
            if (count > 1) {
                count--;
                document.querySelector('.count').textContent = count;
                document.getElementById('quantityInput').value = count; // Set hidden input value
            }
        }

        function showPaymentModal() {
            // Transfer the values from the visible form to the modal hidden inputs
            document.getElementById("modalSize").value = document.getElementById("size").value;
            document.getElementById("modalColor").value = document.getElementById("color").value;
            document.getElementById("modalMessage").value = document.getElementById("message").value;
            document.getElementById("paymentModal").style.display = "flex";
        }

        function closeSuccessModal() {
            document.getElementById("successModal").style.display = "none";
            window.location.href = "productpage1.php";
        }

        <?php if ($success): ?>
            document.getElementById("successModal").style.display = "flex";
        <?php endif; ?>
    </script>
</body>

</html>