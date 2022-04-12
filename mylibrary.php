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
     $post->submitMyPost($_POST['post_text'], $_POST['book_name'],$_POST['category'],$_POST['deal_type'],$_POST['price'],'none', $imageName,$_POST['number_of_books'],); //SubmitPost from Post.php, post_text from form
      // refresh to show changes
      header("Location: mylibrary.php"); 
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
     <?php echo "Posts: " . $user['num_posts']. "<br>";
     echo "Likes: " . $user['num_likes']; ?>
   </div>
 </div>
 
 <div class="main_column column">
   <form class="post_form" action="mylibrary.php" method="POST" enctype="multipart/form-data">

    
      <span>Book Name</span>
     <input type="post_text" name="book_name" id="post_button" size="50" class="form-control" placeholder="Book Name"><br>
       <span>Catagory</span>
     <select class="form-control" name="category">
       <option>History</option>
         <option>Religion</option>
           <option>Psychology</option>
     </select>
     <br>
      <input type="hidden" name="deal_type" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>
     
     <br>
      <span>Number of Books</span>
      <input type="post_text" name="number_of_books" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>
     <span></span>

      <input type="hidden" name="price" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>
        <span>Description</span>
     <textarea class="form-control" name="post_text" id="post_text" placeholder="Description"></textarea>
<br>
  <input type="file" name="fileToUpload" id="fileToUpload">
<br>
      <input type="submit" name="post" id="post_button" value="POST">
     <br>
     <hr>
   </form>
   <br>
 <h2>My Library</h2>
    <div class="posts_area">

    </div>
    <img src="assets/images/icons/loading.gif" id="loading" alt="loading">

 </div>

<!-- INFINITE LOADING -->
 <script>
 //Loading Icon
    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    $(document).ready(function() {
      $('#loading').show();

      //Ajax Request
      $.ajax({
        url: 'includes/handlers/ajax_my_library_posts.php',
        type: 'POST',
        data: 'page=1&userLoggedIn=' + userLoggedIn,
        cache: false,
        success: function(data) {
          $('#loading').hide();
          $('.posts_area').html(data); //Returned data from AJAX
        }
      });

      $(window).scroll(function() {
        var height = $('.posts_area').height; //Height of posts container div
        var scroll_top = $(this).scrollTop(); //Top of page at any time
        var page = $('.posts_area').find('.nextPage').val();
        var noMorePosts = $('.posts_area').find('.noMorePosts').val();

          if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
            //If height scrolled is top of window plus the height of the window and more posts available
            $('#loading').show();
            //Ajax Request
            var ajaxReq = $.ajax({
              url: 'includes/handlers/ajax_my_library_posts.php',
              type: 'POST',
              data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
              cache: false,
              success: function(data) {
                $('.posts_area').find('.nextPage').remove(); //Removes current next page
                $('.posts_area').find('.noMorePosts').remove();
                $('#loading').hide();
                $('.posts_area').append(data); //Returned data from AJAX
              }
            });
          } //End if statement
          return false;
      }); //End $(window).scroll(function()
    }); //Document Ready Close


 </script>

 <!-- WRAPPER BELOW CLOSE FROM header.php -->
  </div>
  </body>
</html>
