<?php
session_start();
include 'includes/conn.php';

// Check if form is submitted
if (isset($_POST['login'])) {
    // Get username and password inputs
    $username = trim($_POST['user']);
    $password = trim($_POST['pass']);

    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in both username and password.';
        header('Location: index.php');
        exit();
    }

    // Prepare SQL statement to fetch user based on username
    $sql = "SELECT * FROM admin WHERE username = ?";

    // Initialize MySQLi connection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the username parameter
        $stmt->bind_param('s', $username);

        // Execute the query
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($password === $row['password']) {
                // Set session variable for logged-in admin
                $_SESSION['admin'] = $row['id'];
                header('Location: home.php');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid username or password.';
            }
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Database error. Please try again later.';
    }

    // Redirect back to login page
    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = 'Please log in to continue.';
    header('Location: index.php');
    exit();
}
?>
