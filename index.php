<?php
session_start();
include 'conn.php'; // Include your database connection

// Check if the user is logged in by checking the session for an email
if (isset($_SESSION['customer_email'])) {
    $email = $_SESSION['customer_email'];

    // Query to get order details based on the email (from both tables)
    $sql_orders = "
        SELECT product_name, quantity, transaction_id, status FROM order_details WHERE customer_email = ?
        UNION ALL
        SELECT product_name, quantity, transaction_id, status FROM t_shirt_order_details WHERE customer_email = ?
    ";

    // Query to get user details based on the email
    $sql_user = "SELECT name, email, phone, address FROM users_details WHERE email = ?";

    // Prepare and execute the order details query
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("ss", $email, $email); // Bind email twice for both SELECTs in UNION
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    // Prepare and execute the user details query
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    // Fetch user details
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc(); // User details

        // Fetch order details
        $user_orders = [];
        if ($result_orders->num_rows > 0) {
            while ($row = $result_orders->fetch_assoc()) {
                $user_orders[] = $row; // Add each order to the user_orders array
            }
        }
    } else {
        $error = "User not found.";
    }
} else {
    $guest = true; // User is a guest
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verdant Eco World</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* User Details Modal */
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .modal-content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .order-item p {
            font-size: 16px;
            color: #555;
        }

        .close-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .order-item {
            padding: 10px;
            text-align: center;
        }

        .order-item p {
            margin: 10px 5px;
        }

        .notfound {
            text-align: start;
            margin: 30px;
        }
    </style>
    <!-- ========== Start Section ========== -->
    <div class="home">
        <div class="logo">
            <img src="logo.PNG" alt="Verdant Eco World Logo">
            <div class="login">
                <button> <a href="login.php" style="text-decoration: none;color: black; opacity: 0.6;">Log in</a>
                </button>
            </div>
            <div class="heading">
                <h1><b>V</b>ERDAN<b>T</b> ECO WORLD</h1>
            </div>
            <div class="bar">
                <button id="navbutton"><i class="fa-solid fa-bars"></i></button>
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" id="search" placeholder="Search">
                <button id="cardbutton"><i class="fa-solid fa-cart-shopping"></i></button>
                <button id="favbutton"><i class="fa-regular fa-user"></i></button>
            </div>
            <div class="category">
                <ul>
                    <li><a href="#">Paper Pencil</a></li>
                    <li><a href="#">Paper Pen</a></li>
                    <li><a href="#">Paper Color Pencil</a></li>
                    <li><a href="#">Customise T-Shirt</a></li>
                    <li><a href="#">Customise Couple T-Shirt</a></li>
                </ul>
            </div>
            <div class="homeimg">
                <div class="hometext">
                    <div class="text1">
                        <p>ECO-SAVVY STATIONERY</p>
                    </div>
                    <div class="text2">
                        <p>Sustainable pens and pencils for conscious consumers</p>
                    </div>
                    <div class="text3">
                        <p>Make a green choice for your writing needs with our environmentally conscious stationery
                            options. Embrace a greener lifestyle with our thoughtfully designed, environmentally
                            responsible writing tools.</p>
                    </div>
                    <div class="text4"><button>Shop Now</button></div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== End Section ========== -->

    <!-- ========== Start Product Section ========== -->
    <div class="productpage">
        <h1>Our Products</h1>
        <div class="product">
            <div class="productcard">
                <div class="card">
                    <img src="product1.JPG" alt="Paper Pen" class="productimg">
                    <button class="productbtn"><a href="productpage1.php">Quick View</a></button>
                </div>
                <div class="productcost">
                    <p>Paper Pen</p>
                    <p>$10</p>
                </div>
            </div>

            <div class="productcard">
                <div class="card">
                    <img src="product2.PNG" alt="Paper Pen" class="productimg">
                    <button class="productbtn"><a href="productpage2.php">Quick View</a></button>
                </div>
                <div class="productcost">
                    <p>Paper Pen</p>
                    <p>$10</p>
                </div>
            </div>

            <div class="productcard">
                <div class="card">
                    <img src="product3.PNG" alt="Paper Pen" class="productimg">
                    <button class="productbtn"><a href="productpage3.php">Quick View</a></button>
                </div>
                <div class="productcost">
                    <p>Paper Pen</p>
                    <p>$10</p>
                </div>
            </div>
            <div class="productcard">
                <div class="card">
                    <img src="product3.PNG" alt="Paper Pen" class="productimg">
                    <button class="productbtn"><a href="productpage3.php">Quick View</a></button>
                </div>
                <div class="productcost">
                    <p>Paper Pen</p>
                    <p>$10</p>
                </div>
            </div>

        </div>

        <!-- ========== End Product Section ========== -->
        `
        <!-- ========== Start Section ========== -->
        <div class="productpage2">
            <div class="productpage2-text">
                <div class="productpage2-text1">
                    <h1>Pick The Pen & Pencil</h1>
                </div>
                <div class="productpage2-text1">
                    <h1>Campus Favorits</h1>
                </div>
            </div>
            <div class="product2">
                <div class="productcard2">
                    <div class="card2">
                        <img src="product2.1.JPG" alt="">
                        <button class="productbtn"><a href="productpage4.php">Quick View</a></button>
                    </div>
                    <div class="card2">
                        <img src="product2.2.JPG" alt="">
                        <button class="productbtn"><a href="productpage5.php">Quick View</a></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== End Section ========== -->

        <!-- ========== Start Section ========== -->
        <div class="productpage2">
            <div class="productpage2-text">
                <div class="productpage2-text1">
                    <h1>T - Shirts</h1>
                </div>
                <div class="productpage2-text1">
                    <h1>Making Memories</h1>
                </div>
            </div>
            <div class="product2">
                <div class="productcard2">
                    <div class="card2">
                        <img src="tshirt1.JPG" alt="">
                        <button class="productbtn"><a href="productpage6.php">Quick View</a></button>
                    </div>
                    <div class="card2">
                        <img src="tshirt2.JPG" alt="">
                        <button class="productbtn"><a href="productpage7.php">Quick View</a></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== End Section ========== -->

        <!-- ========== Start Section ========== -->
        <div class="youtubevideo">
            <h1>FOLLOW US</h1>
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="videocard">
                            <iframe width="400" height="300"
                                src="https://www.youtube.com/embed/_e4bkXkeJEI?si=M6OqjSFS9OiRm7r6"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="videocard">
                            <iframe width="400" height="300"
                                src="https://www.youtube.com/embed/X0Uu8wg31hM?si=N3LeT9buq5x3wduU"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="videocard">
                            <iframe width="400" height="300"
                                src="https://www.youtube.com/embed/I18L-CB5-pQ?si=bptjs3g-iBCLjmfg"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <!-- Add Navigation Buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <!-- ========== End Section ========== -->

        <!-- ========== Start Section ========== -->
        <div class="footer">
            <div class="footercon">
                <div class="text">
                    <p><strong>About Us</strong></p>
                    <p><a href="#">Our Story</a></p>
                    <p><a href="#">Careers</a></p>
                    <p><a href="#">Partnerships</a></p>
                </div>
                <div class="text">
                    <p><strong>For Help</strong></p>
                    <p><a href="#">Contact Us</a></p>
                    <p><a href="#">FAQ</a></p>
                    <p><a href="#">Support</a></p>
                </div>
                <div class="text">
                    <p><strong>Services</strong></p>
                    <p><a href="#">Consulting</a></p>
                    <p><a href="#">Training</a></p>
                    <p><a href="#">Development</a></p>
                </div>
            </div>
            <p class="copy">&copy;Aravind</p>
        </div>

        <!-- ========== Order Details Modal ========== -->
        <div id="orderModal" class="modal">
            <div class="modal-content">
                <h2>Order Details</h2>
                <?php if (isset($user_orders) && !empty($user_orders)): ?>
                    <?php $serial_no = 1; // Initialize serial number ?>
                    <?php foreach ($user_orders as $order): ?>
                        <div class="order-item">
                            <p><strong>Order No:</strong> <?php echo $serial_no++; // Increment serial number ?></p>
                            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($order['transaction_id']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="notfound">No orders found.</p>
                <?php endif; ?>
                <button class="close-btn"
                    onclick="document.getElementById('orderModal').style.display='none'">Close</button>
            </div>
        </div>



        <!-- ========== User Details Modal ========== -->
        <div id="userModal" class="modal">
            <div class="modal-content">
                <h2>User Details</h2>
                <?php if (isset($_SESSION['customer_email'])): ?>
                    <!-- If the user is logged in, show their details -->
                    <div class="user-info">
                        <label>Name:</label>
                        <p><?php echo htmlspecialchars($user['name']); ?></p>
                    </div>
                    <div class="user-info">
                        <label>Email:</label>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="user-info">
                        <label>Phone:</label>
                        <p><?php echo htmlspecialchars($user['phone']); ?></p>
                    </div>
                    <div class="user-info">
                        <label>Address:</label>
                        <p><?php echo htmlspecialchars($user['address']); ?></p>
                    </div>
                <?php else: ?>
                    <!-- If the user is not logged in, show Guest details and a login button -->
                    <div class="user-info">
                        <label>Name:</label>
                        <p>Guest</p>
                    </div>
                    <div class="user-info">
                        <label>Email:</label>
                        <p>Not Available</p>
                    </div>
                    <div class="user-info">
                        <label>Phone:</label>
                        <p>Not Available</p>
                    </div>
                    <div class="user-info">
                        <label>Address:</label>
                        <p>Not Available</p>
                    </div>
                <?php endif; ?>
                <button class="close-btn"
                    onclick="document.getElementById('userModal').style.display='none'">Close</button>
            </div>
        </div>


        <!-- ========== End Section ========== -->
        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                centeredSlides: true,
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    type: "fraction",
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
            // Show user details modal
            document.getElementById('favbutton').onclick = function () {
                var modal = document.getElementById('userModal');
                modal.style.display = 'flex';
            };

            // Show order details modal
            document.getElementById('cardbutton').onclick = function () {
                var modal = document.getElementById('orderModal');
                modal.style.display = 'flex';
            };

            // Close modals when clicking outside of them
            window.onclick = function (event) {
                var orderModal = document.getElementById('orderModal');
                var userModal = document.getElementById('userModal');
                if (event.target == orderModal || event.target == userModal) {
                    orderModal.style.display = 'none';
                    userModal.style.display = 'none';
                }
            };

        </script>


</body>

</html>