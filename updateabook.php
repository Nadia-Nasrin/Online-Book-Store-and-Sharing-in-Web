<?php

include('includes/header.php');

if (isset($_GET['id'])) {
    $proId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['id']);
}
if(isset($_POST['post'])) {

 

      // new post class
  
       $post = new Post($con, $userLoggedIn); //New instance of Post class
     $post->updateabook($_POST['id'], $_POST['price'],$_POST['deal_type'],$_POST['discount'],$_POST['start_d_date'],$_POST['last_d_date']); //SubmitPost from Post.php, post_text from form
      // refresh to show changes
    header('Location: index.php');
    }
    else {
      // else provide error image
      echo "<div style='text-align: center;' class='alert alert-danger'>
            
            </div>";
    }

  //if post button has been pressed ...
 

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
   <form class="post_form" action="updateabook.php" method="POST" enctype="multipart/form-data">

 <?php 
                      //  $db->sql("SET NAMES 'utf8'");
     $data_query = mysqli_query($con, "SELECT * FROM posts WHERE id='$proId'");?>
      <?php while($row = mysqli_fetch_array($data_query)){?>
         <input type="hidden" name="id" value="<?=$row['id']?>"id="post_button" size="50" class="form-control" placeholder="Book Name"><br>
           <span>Book Name</span>
     <input type="post_text" name="book_name" value="<?=$row['book_name']?>"id="post_button" size="50" class="form-control" placeholder="Book Name"><br>
       
       <?php }?>
         <span>Price</span>
    <input type="text" name="price" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>

    <span>Discount (not required)</span>
    <input type="text" name="discount" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>

    <span>Start Discount Date (not required)</span>
    <input type="date" name="start_d_date" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>

    <span>End Discount Date (not required)</span>
    <input type="date" name="last_d_date" id="post_button" size="50" class="form-control" placeholder="Price" value="0"><br>

       <span>Deal type</span>
     <select class="form-control" name="deal_type">
       <option>Rent</option>
         <option>Sale</option>
           <option>Free</option>
     </select>
     
     <br>
     
      
     <span></span>
      
    

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
