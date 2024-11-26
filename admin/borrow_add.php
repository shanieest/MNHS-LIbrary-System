<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$user = $_POST['user'];
		
		$sql = "SELECT * FROM users WHERE user_id = '$user'";
		$query = $conn->query($sql);
		if($query->num_rows < 1){
			if(!isset($_SESSION['error'])){
				$_SESSION['error'] = array();
			}
			$_SESSION['error'][] = 'User not found';
		}
		else{
			$row = $query->fetch_assoc();
			$user_id = $row['id'];

			$added = 0;
			foreach($_POST['book_id'] as $book_id){
				if(!empty($book_id)){
					$sql = "SELECT * FROM books WHERE book_id = '$book_id' AND status != 1";
					$query = $conn->query($sql);
					if($query->num_rows > 0){
						$brow = $query->fetch_assoc();
						$bid = $brow['id'];

						$sql = "INSERT INTO borrow (user_id, book_id, date_borrow) VALUES ('$user_id', '$bid', NOW())";
						if($conn->query($sql)){
							$added++;
							$sql = "UPDATE books SET status = 1 WHERE id = '$bid'";
							$conn->query($sql);
						}
						else{
							if(!isset($_SESSION['error'])){
								$_SESSION['error'] = array();
							}
							$_SESSION['error'][] = $conn->error;
						}

					}
					else{
						if(!isset($_SESSION['error'])){
							$_SESSION['error'] = array();
						}
						$_SESSION['error'][] = 'Book with ID - '.$book_id.' unavailable';
					}
		
				}
			}

			if($added > 0){
				$book = ($added == 1) ? 'Book' : 'Books';
				$_SESSION['success'] = $added.' '.$book.' successfully borrowed';
			}

		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: borrow.php');

?>