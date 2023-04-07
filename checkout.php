<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

    //mysqli_real_escape_string là một hàm trong PHP được sử dụng để bảo vệ các truy vấn SQL khỏi các cuộc tấn công SQL injection. Hàm này thực hiện việc trích xuất các ký tự đặc biệt (như các ký tự nháy đơn, các ký tự backslash và các ký tự NULL) từ chuỗi và thay thế chúng bằng các chuỗi tương ứng được mã hóa. 
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat']  );
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'Giỏ của bạn trống!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'Đơn đặt hàng đã được đặt!';
    }else{
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'Đặt hàng thành công!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THANH TOÁN</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>
    <section class="display-order">
        <h1>Thông tin đơn hàng</h1>
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>
        <p> <?php echo $fetch_cart['name'] ?>
            <span>(<?php echo $fetch_cart['price']. ' VNĐ'.' x '.$fetch_cart['quantity']  ?>)</span>
        </p>
        <?php
        }
        }else{
            echo '<p class="empty">Giỏ của bạn trống!</p>';
        }
    ?>
        <div class="grand-total">Tổng cộng: <span><?php echo $grand_total; ?> VNĐ</span></div>
    </section>

    <section class="checkout">

        <form action="" method="POST">

            <h3>Thanh Toán Đơn Hàng</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>Tên:</span>
                    <input type="text" name="name" placeholder="Nhập tên...">
                </div>
                <div class="inputBox">
                    <span>SDT</span>
                    <input type="number" name="number" min="0" placeholder="Nhập sdt...">
                </div>
                <div class="inputBox">
                    <span>Email:</span>
                    <input type="email" name="email" placeholder="Nhập email...">
                </div>
                <div class="inputBox">
                    <span>Phương thức thanh toán:</span>
                    <select name="method">
                        <option value="cash on delivery">Thanh toán khi nhận hàng</option>
                        <option value="credit card">Thẻ tín dụng</option>
                        <option value="paypal">Momo</option>
                        <option value="paytm">Zalopay</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Địa chỉ:</span>
                    <input type="text" name="flat" placeholder="Nhập địa chỉ nhà...">
                </div>
            </div>

            <input type="submit" name="order" value="Đặt Hàng" class="btn">

        </form>

    </section>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>