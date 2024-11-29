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

			$return = 0;
			foreach($_POST['book_id'] as $book_id){
				if(!empty($book_id)){
					$sql = "SELECT * FROM books WHERE book_id = '$book_id'";
					$query = $conn->query($sql);
					if($query->num_rows > 0){
						$brow = $query->fetch_assoc();
						$bid = $brow['id'];

						$sql = "SELECT * FROM borrow WHERE user_id = '$user_id' AND book_id = '$book_id' AND status = 0";
						$query = $conn->query($sql);
						if($query->num_rows > 0){
							$borrow = $query->fetch_assoc();
							$borrow_id = $borrow['id'];
							$sql = "INSERT INTO returns (user_id, book_id, date_return) VALUES ('$user_id', '$book_id', NOW())";
							if($conn->query($sql)){
								$return++;
								$sql = "UPDATE books SET status = 0 WHERE id = '$book_id'";
								$conn->query($sql);
								$sql = "UPDATE borrow SET status = 1 WHERE id = '$borrow_id'";
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
							$_SESSION['error'][] = 'Borrow details not found: Book ID - '.$book_id.', User ID: '.$user;
						}

						

					}
					else{
						if(!isset($_SESSION['error'])){
							$_SESSION['error'] = array();
						}
						$_SESSION['error'][] = 'Book not found: Book ID - '.$book_id;
					}
		
				}
			}

			if($return > 0){
				$book = ($return == 1) ? 'Book' : 'Books';
				$_SESSION['success'] = $return.' '.$book.' successfully returned';
			}

		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: return.php');

?>