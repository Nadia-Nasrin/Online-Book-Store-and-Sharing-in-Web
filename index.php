<?php
// Turn off error reporting
error_reporting(E_ALL ^ E_NOTICE);


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
     $post->submitPost($_POST['post_text'], $_POST['book_name'],$_POST['category'],$_POST['deal_type'],$_POST['price'],'none', $imageName); //SubmitPost from Post.php, post_text from form
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
 <?php
$host="localhost";
$user="root";
$password="";
$database_name="online_book_store";

$connection=mysqli_connect($host,$user,$password,$database_name);

$sql2 = "SELECT   * FROM posts where added_by= '$userLoggedIn'";
$sql3 = "SELECT   * FROM  rent_history where owner= '$userLoggedIn'";

$sql4 = "SELECT   * FROM  sell_history where owner= '$userLoggedIn'";

$result3 = $connection->query($sql3);
$result = $connection->query($sql2);

$result4 = $connection->query($sql4);
$num_of_books=0;
$num_of_books3=0;
$num_of_books4=0;

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {    
$value=$result->num_rows;
$num_of_books= $num_of_books+$row["number_of_books"];

}}

if ($result3->num_rows > 0) {
  // output data of each row
  while($row3 = $result3->fetch_assoc()) {    
$value3=$result3->num_rows;
$num_of_books3= $num_of_books3+$row3["number_of_books"];
    ?>
<?php
}
}
if ($result4->num_rows > 0) {
  // output data of each row
  while($row4 = $result4->fetch_assoc()) {    
$value4=$result4->num_rows;
$num_of_books4= $num_of_books4+$row4["number_of_books"];
    ?>
<?php
}
}

?>

<p>Total Books</p>
<div class="containerp">
 <div class="skills html" style="width: <?php echo $num_of_books/100?>%; background-color: #04AA6D;"><p><?php echo $num_of_books?></p></div>
 </div>
 <p>Total Sale</p>
  <div class="containerp">

 <div class="skills html" style="width: <?php echo $num_of_books4/100?>%; background-color: #04AA6D;"><p><?php echo $num_of_books4?></p></div>
 </div>
<p>Total Rent</p>
 <div class="containerp">
 <div class="skills html" style="width: <?php echo $num_of_books3/100?>%; background-color: #04AA6D;"><p><?php echo $num_of_books3?></p></div>
 </div>

 </div>
 
 <div class="main_column column">
  
   <br>
 <h2>User Posts</h2>
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
        url: 'includes/handlers/ajax_load_posts.php',
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
              url: 'includes/handlers/ajax_load_posts.php',
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
