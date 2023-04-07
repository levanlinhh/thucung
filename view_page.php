<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    
    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'already added to wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'product added to wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Thất bại');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'Sản phẩm được thêm vào giỏ hàng';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Thất bại');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Thất bại');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Thất bại');
        $message[] = 'Thêm vào giỏ hàng thành công!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHI TIẾT SẢN PHẨM</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="quick-view">

        <h1 class="title">THÔNG TIN CHI TIẾT SẢN PHẨM</h1>

        <?php  
        if(isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'") or die('Thất bại');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
    ?>
        <form action="" method="POST">
            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">Giá: <?php echo $fetch_products['price']; ?> VNĐ</div>
            <div class="details">Mô tả: <?php echo $fetch_products['details']; ?></div>
            <input type="text" name="product_quantity" value="1" min="0" class="qty">
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <input type="submit" value="Mua" name="add_to_cart" class="btn">
        </form>
        <?php
            }
        }else{
        echo '<p class="empty">Chi tiết sản phẩm không có sẵn!</p>';
        }
    }
    ?>

    </section>
    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>