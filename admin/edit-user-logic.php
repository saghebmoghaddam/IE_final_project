<?php
require "config/database.php";

if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    // از بین بردن تمام سشن‌ها و هدایت کاربر به صفحه ورود
    session_destroy();
}
if(isset($_POST['submit'])){
    // دریافت داده‌های فرم به‌روز شده
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);

    // بررسی ورودی معتبر
    if(!$firstname || !$lastname ){
        $_SESSION['edit-user'] = "ورودی فرم نامعتبر در صفحه ویرایش";

    }else{
        // به‌روزرسانی کاربر
        $query = "UPDATE users SET firstname='$firstname', lastname='$lastname',is_admin=$is_admin WHERE id= $id LIMIT 1";
        $result = mysqli_query($connection, $query);

        if(mysqli_errno($connection)){
            $_SESSION['edit-user'] = 'به‌روزرسانی کاربر با شکست مواجه شد';

        }else{
            $_SESSION['edit-user-success'] = "کاربر $firstname $lastname با موفقیت به‌روزرسانی شد";

        }

    }

}
header("location: " . ROOT_URL . "admin/manage-users.php");
die();
?>
