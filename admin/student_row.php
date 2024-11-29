<?php 
	include 'includes/session.php';

	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
	
		echo json_encode($row);
	}
	

?>