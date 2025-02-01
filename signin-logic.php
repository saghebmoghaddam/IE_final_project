<?php
require "config/database.php";

if(isset($_POST['submit'])){
    // دریافت ورودی
    $username_email = filter_var($_POST['username_email'] , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var(($_POST['password']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$username_email){
        $_SESSION['signin'] = 'نام کاربری یا ایمیل نادرست است';

    }
    elseif(!$password){
        $_SESSION['signin'] = 'رمز عبور الزامی است';
 
    }else{  
        // دریافت کاربر از پایگاه داده
        $fetch_user_query = "SELECT * FROM users WHERE username = '$username_email' OR email = '$username_email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if(mysqli_num_rows($fetch_user_result) == 1){
            // تبدیل رکورد به آرایه‌ای مرتبط
            $user_record=mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];

            // مقایسه رمز عبور فرم با رمز عبور پایگاه داده
            if(password_verify($password,$db_password)){

                // تنظیم جلسه برای کنترل دسترسی
                $_SESSION['user-id'] = $user_record['id'];
                $_SESSION['signin-success'] = "کاربر با موفقیت وارد شد";

                // تنظیم جلسه اگر کاربر مدیر باشد
                if($user_record['is_admin']==1){
                    $_SESSION['user_is_admin'] = true;

                }
                // ورود کاربر
                header('location: ' . ROOT_URL . 'admin/index.php');
                
            }else{
                $_SESSION['signin'] = "لطفاً ورودی خود را بررسی کنید";
            }
        }else{
            $a = mysqli_num_rows($fetch_user_result);
            echo mysqli_num_rows($fetch_user_result);
            $_SESSION['signin'] = "کاربر پیدا نشد";
        }
    }

    // اگر مشکلی وجود داشت، به صفحه ورود برگردید
    if(isset($_SESSION['signin'])){
        $_SESSION['signin-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signin.php');
        die();
    }

}else{
    header('location: ' . ROOT_URL . "signin.php");
    die();
}
// error_log();
