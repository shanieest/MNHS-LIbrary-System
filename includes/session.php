<?php
include 'includes/conn.php';
session_start();

if (isset($_SESSION['user'])) {
    try {
        // Prepare the query using mysqli
        $sql = "SELECT * FROM students WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameter (i for integer)
            $stmt->bind_param('i', $_SESSION['user']);
            
            // Execute the query
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Fetch data
            $user = $result->fetch_assoc(); // Fetch as an associative array

            // Close the statement
            $stmt->close();
        } else {
            throw new Exception("Error preparing the query: " . $conn->error);
        }
    } catch (Exception $e) {
        error_log("Query failed: " . $e->getMessage());
        die("An error occurred. Please try again later.");
    }
}
?>
