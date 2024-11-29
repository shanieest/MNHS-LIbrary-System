<?php
include 'includes/session.php';

if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    if ($id <= 0) { 
        $_SESSION['error'] = 'Invalid user ID.';
        header('location: student.php');
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    if (!$stmt) {
        $_SESSION['error'] = 'Failed to prepare statement: ' . $conn->error;
        header('location: student.php');
        exit();
    }

    $stmt->bind_param("i", $id); 
    if ($stmt->execute()) {
        $_SESSION['success'] = 'User deleted successfully';
    } else {
        $_SESSION['error'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Select item to delete first';
}

$conn->close(); 
header('location: student.php');
exit();
?>
