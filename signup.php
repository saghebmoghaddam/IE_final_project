<?php

include "config/constants.php";

//دریافت داده‌های فرم در صورت وجود خطای ثبت نام
$firstname=$_SESSION['signup-data']['firstname'] ?? null;
$lastname=$_SESSION['signup-data']['lastname'] ?? null;
$username=$_SESSION['signup-data']['username'] ?? null;
$email=$_SESSION['signup-data']['email'] ?? null;
$createpassword=$_SESSION['signup-data']['createpassword'] ?? null;
$confirmpassword = $_SESSION['signup-data']['confirmpassword'] ?? null;

//حذف داده‌های ثبت نام از جلسه
unset($_SESSION['signup-data']);

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>زیر اشتغال</title>
    <!-- استایل‌های سفارشی -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- آیکن‌سکوت CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- فونت گوگل (مونتسرات) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,800;1,700&display=swap" rel="stylesheet"> 
</head>
<body>

<section class="form__section">

    <div class="container form__section-container">
        <h2>ثبت نام</h2>
        <?php
        if(isset($_SESSION['signup'])): ?> 
            <div class="alert__message error">
            <p>
                <?= $_SESSION['signup'];
                unset($_SESSION['signup']);
                ?>
            </p>
            
            </div>
        
        <?php endif ?>
        <form action="<?=ROOT_URL?>signup-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text"     name ="firstname"       value ="<?= $firstname?>"  placeholder="نام">
            <input type="text"     name ="lastname"        value ="<?= $lastname?>"  placeholder="نام خانوادگی">
            <input type="username"     name ="username"        value ="<?= $username      ?>"  placeholder="نام کاربری">
            <input type="email"    name ="email"           value ="<?= $email          ?>"  placeholder="ایمیل">
            <input type="password" name ="createpassword"  value ="<?= $createpassword ?>"  placeholder="رمز عبور">
            <input type="password" name ="confirmpassword" value ="<?= $confirmpassword?>"  placeholder="تأیید رمز عبور">
            <div class="form__control">
                <label for="avatar">آواتار کاربر</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name ="submit"class="btn">ثبت نام</button>
            <small>قبلاً حساب دارید؟ <a href="signin.php">وارد شوید</a></small>
        </form>
    </div>

</section>

</body>
</html>
