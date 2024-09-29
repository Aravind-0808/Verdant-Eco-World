<?php
$success = false; 
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if customer email exists in session
    if (isset($_SESSION['customer_email'])) {
        $product_name = "Campus Pen2";
        $customer_email = $_SESSION['customer_email']; 
        $payment = true;

        // Prepare and bind the insert statement
        $stmt = $conn->prepare("INSERT INTO order_details (product_name, customer_email, payment) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $product_name, $customer_email, $payment);

        if ($stmt->execute()) {
            $success = true; 
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        header("Location: login.php");
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="productpage.css">
    <style>
        /* Style for success modal */
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
    </style>
</head>

<body>
    <div class="product-page">
        <div class="product">
            <div class="one">
                <div class="product-img"><img src="product2.2.JPG" alt="Product Image"></div>
            </div>
            <div class="two">
                <div class="product-text1">
                    <h1>Campus Pen2</h1>
                    <p>$10</p>
                </div>
                <div class="product-text2">
                    <div class="addtocart">
                        <button class="add">-</button>
                        <p>1</p>
                        <button class="sub">+</button>
                        <button class="card">Add to Cart</button>
                    </div>
                </div>

                <div class="product-text3">
                    <form action="" method="POST">
                        <button type="submit">Buy Now</button>
                    </form>
                </div>

                <div class="product-text4">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam ab doloribus praesentium
                        voluptatibus totam quae inventore! Adipisci suscipit laudantium enim deserunt magni
                        voluptatibus!
                        Deserunt molestias architecto ducimus vero consequuntur aliquam est, modi magni nulla eius aut
                        vitae eum delectus odio, nisi at odit necessitatibus libero. Vero saepe quisquam animi
                        accusantium!</p>
                </div>
            </div>
        </div>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2>Order Placed Successfully!</h2>
            <p>Your order has been placed successfully. Thank you for your purchase!</p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById("successModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("successModal").style.display = "none";
            window.location.href = "productpage1.php"; 
        }

        <?php if ($success) : ?>
            showModal();
        <?php endif; ?>
    </script>
</body>
</html>
