<?php
include('../components/connect.php');
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
}
?>
<!--*===========================*-->
<?php
// if (isset($_POST['submit'])) {
//     $name = htmlspecialchars($_POST['name']);
   
//     $prev_pass = $_POST['prev_pass'];
//     $old_pass = sha1($_POST['old_pass']);
//     $new_pass = sha1($_POST['new_pass']);
//     $confirm_pass = sha1($_POST['confirm_pass']);
//     $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ?, password = ? WHERE id = ?");
//     $update_profile_name->execute([$name,$new_pass ,$admin_id]);
 
//     if(empty($old_pass)){
//         $message[] = 'Please enter old password!';
//     }
//     elseif (!password_verify($old_pass, $prev_pass)) {
//         $message[] = 'Old password is incorrect!';
//     }
//     elseif (empty($new_pass) || empty($confirm_pass)) {
//         $message[] = 'Please enter new password!';
//     }
//     elseif ($new_pass !== $confirm_pass) {
//         $message[] = 'Confirm password does not match!';
//     }
//     else {
//         $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
//         $update_pass->execute([$confirm_pass, $admin_id]);
//         $message[] = 'Password updated successfully!';
//     }

// }
if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $new_pass = sha1($_POST['new_pass']);
    $confirm_pass = sha1($_POST['confirm_pass']);

    if (empty($old_pass)) {
        $message[] = 'Please enter old password!';
    } elseif (empty($new_pass) || empty($confirm_pass)) {
        $message[] = 'Please enter new password!';
    } elseif ($new_pass !== $confirm_pass) {
        $message[] = 'Confirm password does not match!';
    } else {
        $admin_info = $conn->prepare("SELECT password FROM `admins` WHERE id = ?");
        $admin_info->execute([$admin_id]);
        $row = $admin_info->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($prev_pass, $row['password'])) {
            $message[] = 'Old password is incorrect!';
        } else {
            $update_profile_name_pass = $conn->prepare("UPDATE `admins` SET name = ?, password = ? WHERE id = ?");
            $update_profile_name_pass->execute([$name, $new_pass, $admin_id]);
            $message[] = 'Profile and password updated successfully!';
        }
    }
}
?>
<!--*===========================*-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <!--*==============header=============*-->
    <?php include('../components/admin_header.php'); ?>
    <!--*===========================*-->
    <section class="form-container">
        <form action="" method="post">
            <h3>Update Profile</h3>
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="old_pass" placeholder="enter old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="enter new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_pass" placeholder="confirm new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="update now" class="btn" name="submit">
        </form>
    </section>
    <!--*===========js================*-->
    <script src="../js/admin_script.js"></script>
    <!--*===========================*-->
</body>

</html>