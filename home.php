<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_wishlist'])){

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   
   $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('truy vấn không thành công');

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('truy vấn không thành công');

   if(mysqli_num_rows($check_wishlist_numbers) > 0){
       $message[] = 'already added to wishlist';
   }elseif(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'already added to cart';
   }else{
       mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('truy vấn không thành công');
       $message[] = 'product added to wishlist';
   }

}

if(isset($_POST['add_to_cart'])){

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('truy vấn không thành công');

   if(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'Đã thêm vào giỏ hàng!';
   }else{

       $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('truy vấn không thành công');

       if(mysqli_num_rows($check_wishlist_numbers) > 0){
           mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('truy vấn không thành công');
       }

       mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('truy vấn không thành công');
       $message[] = 'Sản phẩm được thêm vào giỏ hàng!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="home">



    </section>

    <section class="products">

        <h1 class="title">SẢN PHẨM CỦA CHÚNG TÔI</h1>

        <div class="box-container">

            <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('truy vấn không thành công');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
            <form action="" method="POST" class="box">
                <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="name" style="color: red; font-weight: 500;">Giá: <?php echo $fetch_products['price']; ?> VNĐ
                </div>
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <input type="submit" value="Thêm Giỏ Hàng" name="add_to_cart" class="btn">
                <button class="btn"><a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>">Thêm chi
                        tiết</a></button>
            </form>
            <?php
         }
      }else{
         echo '<p class="empty">SẢN PHẨM TRỐNG!</p>';
      }
      ?>

        </div>


    </section>
    <section class="home_tt">



    </section>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>