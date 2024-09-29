<?php
// Include database connection
include 'conn.php';

// Initialize variables
$success = false;
$error = "";
$users = [];

// Fetch all users from the database
$sql = "SELECT * FROM users_details";
$result = $conn->query($sql);
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle Delete User
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $response = ['success' => false, 'message' => ''];

    // Use prepared statement for delete
    $sql = "DELETE FROM users_details WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "User deleted successfully!";
    } else {
        $response['message'] = "Error deleting user: " . $stmt->error;
    }

    echo json_encode($response);
    exit; // Terminate the script after handling the AJAX request
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="mt-5 text-center p-2">
        <h2 class="mb-4">User Details Management</h2>

        <div id="message"></div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php foreach ($users as $user): ?>
                        <tr id="user-<?php echo $user['id']; ?>">
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                            <td>
                                <button class="btn btn-danger delete-button" data-id="<?php echo $user['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                var userId = $(this).data('id');
                var row = $('#user-' + userId);
                
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        type: "POST",
                        url: "", // Current file (admin.php)
                        data: { id: userId },
                        success: function(response) {
                            var result = JSON.parse(response);
                            $('#message').html('<p class="success-message">' + result.message + '</p>');
                            if (result.success) {
                                row.fadeOut(); 
                            } else {
                                $('#message').html('<p class="error-message">' + result.message + '</p>');
                            }
                        },
                        error: function() {
                            $('#message').html('<p class="error-message">An error occurred while deleting the user.</p>');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
