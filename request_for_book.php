<?php

include('includes/header.php');

if(isset($_POST['post'])) {

  $uploadOK = 1;
  // create image name variable with file to name
  $imageName = $_FILES['fileToUpload']['name'];
  // leave error message empty string
  $errorMessage = "";

  if($imageName != "") {
    // if imagename is not empty set directory for image
    $targetDir = "assets/users/" ;
    // add a unique id to each image to avoid conflicts with duplicate names
    $imageName = $targetDir . uniqid() . basename($imageName);
    // get filetype extension
    $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);
    // set size limit for file to upload
    if($_FILES['fileToUpload']['size'] > 100000000) {
      // give error message if above
      $errorMessage = "Your file is too large.";

      $uploadOK = 0;
    }
    // check if (change file name to lower case) file is not an image type, send error message
    if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg" && strtolower($imageFileType)) {
      $errorMessage = "Only images allowed.";
      $uploadOK = 0;
    }

    if($uploadOK) {
      if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)) {
        // image uploaded
      }
      else {
        // image did not upload
        $uploadOK = 0;
      }
    }
  }

   if($uploadOK) {
      // new post class
  
       $post = new Post($con, $userLoggedIn); //New instance of Post class
     $post->requestbook($_POST['post_text'], $_POST['book_name'],$_POST['deal_type'],$_POST['price'],'none', $imageName); //SubmitPost from Post.php, post_text from form
      // refresh to show changes
      header("Location: index.php"); 
    }
    else {
      // else provide error image
      echo "<div style='text-align: center;' class='alert alert-danger'>
            $errorMessage
            </div>";
    }

  //if post button has been pressed ...
 
}
 ?>

 <div class="user_details column">
   <a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>
   <div class="user_details_left_right">
     <a href="<?php echo $userLoggedIn; ?>">
     <?php echo $user['first_name'] . " " . $user['last_name'];?>
     </a>

     <br>
     <a href="make_post.php">Make Post</a><br>
      <a href="request_for_book.php">Request for Book</a><br>
     <?php echo "Posts: " . $user['num_posts']. "<br>";
     echo "Likes: " . $user['num_likes']; ?>
   </div>
 </div>
 
 <div class="main_column column">
  <h3>Request for Books</h3>
   <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
     <input type="post_text" name="book_name" id="post_button" size="50" class="form-control" placeholder="Book Name"><br>
 
     <br>
      <select class="form-control" name="deal_type">
       <option>Request for Rent</option>
         <option>Request for buy</option>
          
     </select>
     <br>
   
      <input type="hidden" name="price" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>
     <textarea class="form-control" name="post_text" id="post_text" placeholder="Description"></textarea>
<br>
  <input type="file" name="fileToUpload" id="fileToUpload">
<br>
      <input type="submit" name="post" id="post_button" value="POST">
     <br>
     <hr>
   </form>
   <br>


<!-- INFINITE LOADING -->
 

 <!-- WRAPPER BELOW CLOSE FROM header.php -->
  </div>
  </body>
</html>
