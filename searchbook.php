<?php

include('includes/header.php');

if(isset($_GET['q'])) {
  //query parameter exists
  $query = $_GET['q'];
} else {
  $query = '';
}

//type = username, name
if(isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = 'book_name';
}

 ?>

 <div class="main_column column" id="main_column">

 	<?php
 	if($query == "")
 		echo "You must enter something in the search box.";
 	else {
 		if($type == "book_name") {
      $usersReturnedQuery = mysqli_query($con, "SELECT * FROM posts WHERE book_name LIKE '$query%' AND deleted='no' LIMIT 8");
    } /*else {
 			$names = explode(" ", $query);
 			if(count($names) == 3) {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') AND user_closed='no'");
      } else if(count($names) == 2) {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no'");
      } else  {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no'");
      }
 		}
*/
 		//Check if results were found
 		if(mysqli_num_rows($usersReturnedQuery) == 0)
 			echo "No results found with a " . $type . " like: " .$query;
 		else
 			echo mysqli_num_rows($usersReturnedQuery) . " results found: <br> <br>";

 		while($row = mysqli_fetch_array($usersReturnedQuery)) {
 			$user_obj = new User($con, $user['book_name']);

 			$button = "";
 			$mutual_friends = "";

 			if($user['book_name'] != $row['book_name']) {
 				//Generate button depending on friendship status
 				if($user_obj->isFriend($row['book_name']))
 					$button = "<input type='submit' name='" . $row['book_name'] . "' class='danger' value='Remove Friend'>";
 				else if($user_obj->didReceiveRequest($row['username']))
 					$button = "<input type='submit' name='" . $row['price'] . "' class='warning' value='Respond to request'>";
 				else if($user_obj->didSendRequest($row['username']))
 					$button = "<input type='submit' class='default' value='Request Sent'>";
 				else
 					$button = "<input type='submit' name='" . $row['added_by'] . "' class='success' value='Add Friend'>";

 				$mutual_friends = $user_obj->getMutualFriends($row['username']) . " friends in common";


 				//Button forms
 				if(isset($_POST[$row['username']])) {

 					if($user_obj->isFriend($row['username'])) {
 						$user_obj->removeFriend($row['username']);
 						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
 					}
 					else if($user_obj->didReceiveRequest($row['username'])) {
 						header("Location: requests.php");
 					}
 					else if($user_obj->didSendRequest($row['username'])) {

 					}
 					else {
 						$user_obj->sendRequest($row['username']);
 						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
 					}
 				}
 			} //end if($user['username'] != $row['username'])

 			echo "<div class='search_result'>
 					<div class='searchPageFriendButtons'>
 					
 					</div>


 					<div class='result_profile_pic'>
 						<a href='" . $row['book_name'] ."'><img src='". $row['image_name'] ."' style='height: 100px;'></a>
 					</div>

 						<a href='" . $row['added_by'] ."'> Added By:" . $row['added_by'] . " 
 						<p id='grey'>Book Name:  " . $row['book_name'] ."</p>
 						</a>
            <p id='grey'>Price:  " . $row['price'] .". Deal Type:  " . $row['deal_type'] ."</p>
          
            <p id='grey'>Description:  " . $row['body'] ."</p>
 						
 				</div>
 				<hr id='search_hr'>";

 		} //End while
 	} //End else 	if($query == "")

   ?>

</div>
