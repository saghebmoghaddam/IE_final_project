<?php
require "config/database.php";
session_start();

//دریافت داده‌های فرم ثبت نام

if(isset($_POST["submit"])){
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];
    if(!$firstname){
        $_SESSION['signup'] = 'لطفاً نام خود را وارد کنید';
    }elseif(!$lastname){
        $_SESSION['signup'] = 'لطفاً نام خانوادگی خود را وارد کنید';
    }elseif(!$username){
        $_SESSION['signup'] = 'لطفاً نام کاربری خود را وارد کنید';
    }elseif(!$email){
        $_SESSION['signup'] = 'لطفاً ایمیل خود را وارد کنید';
    }elseif(strlen($createpassword)<8 || strlen($confirmpassword)<8){
        $_SESSION['signup'] = 'رمز عبور باید بیشتر از 8 کاراکتر باشد';
    }elseif(!$avatar['name']){
        $_SESSION['signup'] = 'لطفاً آواتار را اضافه کنید';
    }else{
        if($createpassword !== $confirmpassword){
            $_SESSION['signup']="رمزهای عبور مطابقت ندارند";

        }else{

            $hashed_password = password_hash($createpassword,PASSWORD_DEFAULT);
            
            $user_check_query="SELECT * FROM users WHERE username='$username' OR email ='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if(mysqli_num_rows($user_check_result)>0){
                $_SESSION['signup'] = "نام کاربری یا ایمیل قبلاً وجود دارد";
            }else{
                //کار بر روی آواتار
                //تغییر نام آواتار
                $time = time(); // برای ایجاد نام منحصر به فرد با استفاده از زمان فعلی 
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name=$avatar['tmp_name'];
                $avatar_destination_path='images/' . $avatar_name;

                //اطمینان از اینکه فایل یک تصویر است
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extension = explode('.', $avatar_name);
                $extension = end($extension);
                if(in_array($extension,$allowed_files)){
                    //اگر تصویر خیلی بزرگ نیست

                    if($avatar['size']<1000000){

                        //بارگذاری آواتار
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    }else{
                        $_SESSION['signup']="حجم پوشه خیلی بزرگ است. باید کمتر از 1 مگابایت باشد";
                    }
                }else{
                    $_SESSION['signup']="فایل باید png، jpg یا jpeg باشد";
                }
            }

        }
    }
    // هدایت به صفحه ثبت نام در صورت خطا
    if(isset($_SESSION['signup'])){
        // بازگرداندن داده‌ها به صفحه ثبت نام
        $_SESSION['signup-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
        
    }else{
        //درج کاربر جدید در جدول کاربران
        $inset_user_query = "INSERT INTO users SET firstname ='$firstname' ,lastname='$lastname',username='$username',email ='$email' ,password='$hashed_password',avatar='$avatar_name',is_admin=0";
        $inset_user_result = mysqli_query($connection, $inset_user_query);
        if(!mysqli_errno($connection)){
            $_SESSION['signup-success'] = "ثبت نام موفقیت آمیز بود. لطفاً وارد شوید";
            header('location: ' . ROOT_URL . 'signin.php');

        }
    }
}else{
    //دکمه کلیک نشده است
    header('location: ' . ROOT_URL . "signup.php");
    die();
}
