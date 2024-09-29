<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users_details WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['customer_email'] = $user['email']; // Add customer email to session

            // Redirect or show success
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Invalid email or password.</p>";
        }
    } else {
        echo "<p style='color: red;'>No account found with that email.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 15px;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        max-width: 600px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    input,
    button {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
        border: none;
    }

    button:hover {
        background-color: #218838;
    }

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
        width: 600px;
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

<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <!-- Login Button -->
            <button type="submit">Login</button>
        </form>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2>Login Successful!</h2>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById("successModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("successModal").style.display = "none";
            window.location.href = "index.php"; 
        }

        <?php if ($success): ?>
            showModal();
        <?php endif; ?>
    </script>
</body>

</html>
