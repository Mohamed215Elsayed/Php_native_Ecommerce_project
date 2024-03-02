<?php
include('../components/connect.php');
?>
<!--**************  -->
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};
?>
<!-- ************** -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <?php
    if (isset($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
        $update_profile->execute([$name, $email, $user_id]);
        $prev_pass = $_POST['prev_pass'];
        $old_pass = $_POST['old_pass'];
        $new_pass = $_POST['new_pass'];
        $cpass = $_POST['cpass'];
        if (empty($old_pass)) {
            $message[] = 'Please enter old password!';
        }
        elseif (!password_verify($old_pass, $prev_pass)) {
            // Password verification failed
            $message[] = 'Old password is incorrect!';
        } else {
            // Proceed with password update using prepared statements and parameter binding
            $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_password->execute([$new_pass_hash, $user_id]);
        }
    }
    ?>
    <!--******header********  -->
    <?php include('../components/user_header.php'); ?>
    <!--************************-->
    <section>
        <div class="login-box">
            <form action="" method="post">
                <h2>Update now</h2>
                <input type="hidden" name="prev_pass">
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" ">
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" ">
                <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <label>Old Password</label>
                </div>

                <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <label>New Password</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <label>Confirm Password</label>
                </div>

                <input type="submit" value="update now" class="btn" name="submit">
            </form>
        </div>
    </section>
    <!-- ************************* -->
    <!--******footer********  -->
    <?php include('../components/footer.php'); ?>
    <!-- *****js********* -->
    <script src="../js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!--*************************************-->
</body>

</html