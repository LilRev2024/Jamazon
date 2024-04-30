<?php

include 'components/connect.php';

if(isset($_POST['submit'])){

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
      $success_msg[] = 'Username updated!';
   }

   if(!empty($email)){
      $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);
      if($verify_email->rowCount() > 0){
         $warning_msg[] = 'Email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $success_msg[] = 'Email updated!';
      }
   }



  $prev_pass = $fetch_user['password'];

  $old_pass = password_hash($_POST['old_pass'], PASSWORD_DEFAULT);
  $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

  $empty_old = password_verify('', $old_pass);

  $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
  $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

  $empty_new = password_verify('', $new_pass);

  $c_pass = password_verify($_POST['c_pass'], $new_pass);
  $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

  if($empty_old != 1){
      $verify_old_pass = password_verify($_POST['old_pass'], $prev_pass);
      if($verify_old_pass == 1){
         if($c_pass == 1){
            if($empty_new != 1){
               $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
               $update_pass->execute([$new_pass, $user_id]);
               $success_msg[] = 'Password updated!';
            }else{
               $warning_msg[] = 'Please enter new password!';
            }
         }else{
            $warning_msg[] = 'Confirm password not matched!';
         }
      }else{
         $warning_msg[] = 'Old password not matched!';
      }
  }
   
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome/css/all.min.css">
  <link rel="stylesheet" href="css/templatemo-style.css">
  <link rel="stylesheet" type="text/css" href="style.css">
   

</head>
<body>


    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-film mr-2"></i>
                Jamazon
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-1" href="view_products.php">View Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-2" href="view_order.php">My Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-3 active" href="update.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-4" href="shopping_cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-5" href="index.php?logout='1'">Log Out</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
<body>
   


<!-- update section starts  -->

<section class="account-form">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>update your profile!</h3>
      <p class="placeholder">your name</p>
      <input type="text" name="name" maxlength="50" placeholder="<?= $fetch_profile['name']; ?>" class="box">
      <p class="placeholder">your email</p>
      <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_profile['email']; ?>" class="box">
      <p class="placeholder">old password</p>
      <input type="password" name="old_pass" maxlength="50" placeholder="enter your old password" class="box">
      <p class="placeholder">new password</p>
      <input type="password" name="new_pass" maxlength="50" placeholder="enter your new password" class="box">
      <p class="placeholder">confirm password</p>
      <input type="password" name="c_pass" maxlength="50" placeholder="confirm your new password" class="box">
      
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>

<!-- update section ends -->















<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/alers.php'; ?>


</html>