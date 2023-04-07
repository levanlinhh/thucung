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

<header class="header">

    <div class="flex">

        <a href="home.php" class="logo">THÚ CƯNG</a>

        <nav class="navbar">
            <ul>
                <li><a href="home.php">Trang Chủ</a></li>
                </li>
                <li><a href="shop.php">Sản Phẩm</a></li>
                <li><a href="orders.php">Đặt Hàng</a></li>
                <li><a href="contact.php">Liên Hệ</a>
                <li><a href="login.php">Đăng Nhập</a></li>
                <li><a href="register.php">Đăng Ký</a></li>
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>

            <?php
                $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Thất bại');
                $cart_num_rows = mysqli_num_rows($select_cart_count);
            ?>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
        </div>

        <div class="account-box">
            <p>Xin chào: <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email: <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">Đăng xuất<table></table></a>
        </div>

    </div>

</header>