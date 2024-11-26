<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$book_id = $_POST['book_id'];
		$title = $_POST['title'];
		$category = $_POST['category'];
		$author = $_POST['author'];
		$donate = $_POST['donate'];

		$sql = "INSERT INTO books (book_id, category_id, title, author, donate) VALUES ('$book_id', '$category', '$title', '$author', '$donate')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Book added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: book.php');

?>