<?php
include 'components/connect.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
};
?>
<!--=========================-->
<?php
if (isset($_POST['order'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);
    if ($check_cart->rowCount() > 0) {
        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);
        $message[] = 'order placed successfully!';
    } else {
        $message[] = 'your cart is empty';
    }
}
?>
<!--=========================-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!--======================================-->
    <?php include 'components/user_header.php'; ?>
    <!--======================================-->
    <section class="checkout-orders">
        <form action="" method="POST">
            <h3>Your Orders</h3>
            <!--===========display orders======== -->
            <div class="display-orders">
                <?php
                $grand_total = 0;
                $cart_items[] = '';
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                ?>
                        <p> <?= $fetch_cart['name']; ?> <span>(<?= '$' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span> </p>
                <?php
                    }
                } else {
                    echo '<p class="empty">your cart is empty!</p>';
                }
                ?>
                <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
                <div class="grand-total">Grand Total : <span>EGP.<?= $grand_total; ?>/-</span></div>
            </div>
            <!-- ===========end display orders======== -->
            <!-- ==========checkout orders======== -->
            <h3>place your orders</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>Your Name :</span>
                    <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
                </div>
                <div class="inputBox">
                    <span>Your Number :</span>
                    <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
                </div>

                <div class="inputBox">
                    <span>Your Email :</span>
                    <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
                </div>

                <div class="inputBox">
                    <span>Payment Method? :</span>
                    <select name="method" class="box" required>
                        <option value="cash on delivery">Cash On Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="Aman">Aman</option>
                        <option value="paypal">Paypal</option>
                    </select>
                </div>

                <div class="inputBox">
                    <span>Address line 01 :</span>
                    <input type="text" name="flat" placeholder="e.g. Flat number" class="box" maxlength="50" required>
                </div>

                <div class="inputBox">
                    <span>Address line 02 :</span>
                    <input type="text" name="street" placeholder="Street name" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <input type="text" name="city" placeholder="Tanta" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Governorate:</span>
                    <input type="text" name="state" placeholder="Garbia" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Country :</span>
                    <input type="text" name="country" placeholder="Egypt" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>ZIP CODE :</span>
                    <input type="number" min="0" name="pin_code" placeholder="e.g. 040257" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
                </div>
            </div>
            <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="place order">
        </form>
    </section>
    <!--==============footer==============-->
    <?php include 'components/footer.php'; ?>
    <!--============js=============-->
    <script src="js/script.js"></script>
    <!--=========================-->
</body>
<!--=============-->

</html>