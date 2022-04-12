<?php

include('includes/header.php');

if(isset($_POST['post'])) {

 $post = new Post($con, $userLoggedIn); //New instance of Post class
     $post->submitRentHistory($_POST['post_text'], $_POST['book_name'],$_POST['return_date'],$_POST['customer_name'],$_POST['owner']); //SubmitPost from Post.php, post_text from form
      // refresh to show changes
   header("Location: renthistory.php"); 
 

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

       <?php
$host="localhost";
$user="root";
$password="";
$database_name="online_book_store";

$connection=mysqli_connect($host,$user,$password,$database_name);

$sql2 = "SELECT   * FROM posts where added_by= '$userLoggedIn'";
$sql3 = "SELECT   * FROM  rent_history where owner= '$userLoggedIn'";


$result3 = $connection->query($sql3);
$result = $connection->query($sql2);
$num_of_books=0;
$num_of_books3=0;

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


?>

<p>Total Books</p>
<div class="containerp">
 <div class="skills html" style="width: <?php echo $num_of_books/100?>%; background-color: #04AA6D;"><p><?php echo $num_of_books?></p></div>
 </div>
<p>Total Rent</p>
 <div class="containerp">
 <div class="skills html" style="width: <?php echo $num_of_books3/100?>%; background-color: #04AA6D;"><p><?php echo $num_of_books3?></p></div>
 </div>
 </div>
 
 <div class="main_column column">
  <h3>Add to Rent History</h3>
   <form class="post_form" action="renthistory.php" method="POST" >
<!--      <input type="post_text" name="book_name" id="post_button" size="50" class="form-control" placeholder="Book Name"><br> -->

     <?php 
                      //  $db->sql("SET NAMES 'utf8'");
     $data_query = mysqli_query($con, "SELECT * FROM posts WHERE added_by='$userLoggedIn' ORDER BY id DESC");
                         
                        //  $row = mysqli_fetch_array($data_query);
                         // echo $row['book_name'];
                        ?>
   <span>Book name</span>
<select name='book_name' id='id' class='form-control' >
                            <option value=''>Select a Book</option>
                            <?php while($row = mysqli_fetch_array($data_query)){?>
                            <option value='<?=$row['book_name']?>'><?=$row['book_name']?></option>
                            <?php }?>

                          </select><br>

                           <?php 
                      //  $db->sql("SET NAMES 'utf8'");
     $data_query = mysqli_query($con, "SELECT * FROM users ORDER BY id DESC");
                         
                        //  $row = mysqli_fetch_array($data_query);
                         // echo $row['book_name'];
                        ?>
                          <span>Customer name</span>
<select name='customer_name' id='id' class='form-control' >
                            <option value=''>Select a User</option>
                            <?php while($row = mysqli_fetch_array($data_query)){?>
                            <option value='<?=$row['username']?>'><?=$row['username']?></option>
                            <?php }?>

                          </select><br>
                        
     <br>
     
     
     <span>Return date</span>
      <input type="date" name="return_date" id="post_button" size="50" class="form-control" placeholder="Return Date"><br>
      <span>Description</span>
     <textarea class="form-control" name="post_text" id="post_text" placeholder="Description"></textarea>
<br>
<!--   <input type="file" name="fileToUpload" id="fileToUpload">
<br> -->
  <input type="hidden" name="owner" value="<?=$userLoggedIn?>">
 <input type="hidden" name="deal_type" id="post_button" size="50" class="form-control" value="rented"><br>
      <input type="submit" name="post" id="post_button" value="Add">
     <br>
     <hr>
   </form>
   <br>
 <h2>My Rent History</h2>
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
        url: 'includes/handlers/ajax_my_rent_history.php',
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
              url: 'includes/handlers/ajax_my_rent_history.php',
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
