<?php
include 'conn.php';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    if ($password !== $confirm_password) {
        echo "<p style='color: red;'>Passwords do not match!</p>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users_details (name, phone, email, address, password) VALUES ('$name', '$phone', '$email', '$address', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $success = true;
        } else {
            echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>
<style>
    .modal {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 50%;
        height: 50%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        width: 30% !important;
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 15px;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        width: 600px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin: 5px 0 5px;
    }

    input,
    textarea,
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

    .error {
        color: red;
    }
</style>
</head>

<body>

    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">

            <!-- Name -->
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <!-- Phone Number -->
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" pattern="[0-9]{10}"
                required>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <!-- Address -->
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" placeholder="Enter your address" required></textarea>

            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <!-- Confirm Password -->
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password"
                required>

            <!-- Register Button -->
            <button type="submit">Register</button>
        </form>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2>Register Successfully!</h2>
            <p>Your Account has been  successfully Created.  you will redirect to Login page</p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>
    <script>
        function showModal() {
            document.getElementById("successModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("successModal").style.display = "none";
            window.location.href = "login.php";
        }

        <?php if ($success): ?>
            showModal();
        <?php endif; ?>
    </script>

</body>

</html>