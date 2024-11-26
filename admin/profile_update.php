<?php
    include 'includes/session.php';

    if (isset($_GET['return'])) {
        $return = $_GET['return'];
    } else {
        $return = 'home.php';
    }

    if (isset($_POST['save'])) {
        $curr_password = $_POST['curr_password']; // Current password entered by the user
        $username = $_POST['username'];
        $password = $_POST['password']; // New password entered by the user
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];

        // Directly compare the current password without using password_verify
        if ($curr_password == $user['password']) { // Match the entered password directly
            // If password is not changed, retain the current password
            if ($password == $user['password']) {
                $password = $user['password'];
            } else {
                // Otherwise, use the new password as entered (no hashing)
                $password = $password;
            }

            // SQL query to update the user profile
            $sql = "UPDATE admin SET username = '$username', password = '$password', firstname = '$firstname', lastname = '$lastname' WHERE id = '".$user['id']."'";

            if ($conn->query($sql)) {
                $_SESSION['success'] = 'Admin profile updated successfully';
            } else {
                // Handle database error
                if ($return == 'borrow.php' OR $return == 'return.php') {
                    if (!isset($_SESSION['error'])) {
                        $_SESSION['error'] = array();
                    }
                    $_SESSION['error'][] = $conn->error;
                } else {
                    $_SESSION['error'] = $conn->error;
                }
            }
        } else {
            // Handle incorrect current password
            if ($return == 'borrow.php' OR $return == 'return.php') {
                if (!isset($_SESSION['error'])) {
                    $_SESSION['error'] = array();
                }
                $_SESSION['error'][] = 'Incorrect password';
            } else {
                $_SESSION['error'] = 'Incorrect password';
            }
        }
    } else {
        // Handle case when the form is not filled out
        if ($return == 'borrow.php' OR $return == 'return.php') {
            if (!isset($_SESSION['error'])) {
                $_SESSION['error'] = array();
            }
            $_SESSION['error'][] = 'Fill up required details first';
        } else {
            $_SESSION['error'] = 'Fill up required details first';
        }
    }

    header('location:' . $return);
?>
