<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$book_id = $_POST['book_id'];
		$title = $_POST['title'];
		$category = $_POST['category'];
		$author = $_POST['author'];
		$donate = $_POST['donate'];

		$sql = "UPDATE books SET book_id = '$book_id', title = '$title', category_id = '$category', author = '$author', donate = '$donate' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Book updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location:book.php');

?>