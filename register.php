<?php
@include './config.php';

if(isset($_POST['submit'])){

   //filter_input là một hàm trong PHP được sử dụng để kiểm tra và xử lý các giá trị dữ liệu được gửi từ người dùng thông qua các biến POST, GET, COOKIE, và ENV.
   $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
   //FILTER_SANITIZE_STRING là một loại filter được sử dụng trong hàm filter_input() của PHP. Nó được sử dụng để loại bỏ các ký tự không hợp lệ (như HTML, Javascript code,..) từ một chuỗi.
   $name = mysqli_real_escape_string($conn, $name);
   //mysqli_real_escape_string là một hàm trong PHP được sử dụng để tránh các lỗ hổng bảo mật liên quan đến SQL Injection khi thực hiện các truy vấn MySQL. SQL Injection là một kỹ thuật tấn công bảo mật cho phép kẻ tấn công thực thi các câu lệnh SQL độc hại thông qua việc chèn các ký tự đặc biệt hoặc mã độc vào các truy vấn SQL.

   $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
   $email = mysqli_real_escape_string($conn, $email);

   $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
   $pass = mysqli_real_escape_string($conn, md5($pass));

   $cpass = filter_input(INPUT_POST, 'cpass', FILTER_SANITIZE_STRING);
   $cpass = mysqli_real_escape_string($conn, md5($cpass));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Thất bại');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
         $message[] = 'registered successfully!';
         header('location:login.php');
      }
   }

}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <title>ĐĂNG KÝ</title>
</head>

<body>
    <?php
    if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
    <section class="form-container">

        <form action="" method="post">
            <h3>ĐĂNG KÝ</h3>
            <input type="text" name="name" class="box" placeholder="Nhập tên..." required>
            <input type="email" name="email" class="box" placeholder="Nhập email..." required>
            <input type="password" name="pass" class="box" placeholder="Nhập mật khẩu..." required>
            <input type="password" name="cpass" class="box" placeholder="Nhập lại mật khẩu..." required>
            <input type="submit" class="btn" name="submit" value="Đăng Ký">
            <p>Bạn đã có tài khoản? <a href="login.php">Đăng Nhập</a></p>
        </form>

    </section>
</body>

</html>