<?php
require "config/database.php";
if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    // نابود کردن تمام سشن‌ها و هدایت کاربر به صفحه ورود
    session_destroy();
}

// دریافت داده‌های فرم افزودن کاربر اگر دکمه ارسال کلیک شده باشد
if(isset($_POST["submit"])){
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);
    $avatar = $_FILES['avatar'];
    
    if(!$firstname){
        $_SESSION['add-user'] = 'لطفاً نام خود را وارد کنید';
    }elseif(!$lastname){
        $_SESSION['add-user'] = 'لطفاً نام خانوادگی خود را وارد کنید';
    }elseif(!$username){
        $_SESSION['add-user'] = 'لطفاً نام کاربری خود را وارد کنید';
    }elseif(!$email){
        $_SESSION['add-user'] = 'لطفاً ایمیل خود را وارد کنید';
    }elseif(!($is_admin == 1 || $is_admin == 0)){
        $_SESSION['add-user'] = 'لطفاً نقش کاربر را انتخاب کنید';
    }elseif(strlen($createpassword)<8 || strlen($confirmpassword)<8){
        $_SESSION['add-user'] = 'رمز عبور باید بیش از 8 کاراکتر باشد';
    }elseif(!$avatar['name']){
        $_SESSION['add-user'] = 'لطفاً تصویر نمایه را اضافه کنید';
    }else{
        if($createpassword !== $confirmpassword){
            $_SESSION['add-user']="رمزهای عبور مطابقت ندارند";
        }else{
            $hashed_password = password_hash($createpassword,PASSWORD_DEFAULT);
            
            $user_check_query="SELECT * FROM users WHERE username='$username' OR email ='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if(mysqli_num_rows($user_check_result)>0){
                $_SESSION['add-user'] = "نام کاربری یا ایمیل قبلاً وجود دارد";
            }else{
                // کار بر روی تصویر نمایه
                // تغییر نام تصویر نمایه
                $time = time(); // برای منحصر به فرد کردن نام هر تصویر از زمان فعلی استفاده کنید 
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name=$avatar['tmp_name'];
                $avatar_destination_path='../images/' . $avatar_name;

                // اطمینان حاصل کنید که فایل یک تصویر است
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extension = explode('.', $avatar_name);
                $extension = end($extension);

                if(in_array($extension,$allowed_files)){
                    // اگر تصویر خیلی بزرگ نیست
                    if($avatar['size']<1000000){
                        // بارگذاری تصویر نمایه
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    }else{
                        $_SESSION['add-user']="حجم تصویر باید کمتر از 1 مگابایت باشد";
                    }
                }else{
                    $_SESSION['add-user']="فایل باید png، jpg یا jpeg باشد";
                }
            }
        }
    }

// هدایت مجدد به صفحه افزودن کاربر در صورت بروز خطا
if(isset($_SESSION['add-user'])){
    // ارسال داده‌ها به صفحه ثبت‌نام
    $_SESSION['add-user-data'] = $_POST;
    header('location: ' . ROOT_URL . 'admin/add-user.php');
    die();
    
}else{
    // افزودن کاربر جدید به جدول کاربران
    $inset_user_query = "INSERT INTO users SET firstname ='$firstname' ,lastname='$lastname',username='$username',email ='$email' ,password='$hashed_password',avatar='$avatar_name',is_admin='$is_admin'";
    $inset_user_result = mysqli_query($connection, $inset_user_query);
    if(!mysqli_errno($connection)){
        $_SESSION['add-user-success'] = "ثبت نام با موفقیت انجام شد";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
        die();
    }
}
}else{
    // دکمه کلیک نشده است
    header('location: ' . ROOT_URL . "admin/add-user.php");
    die();
}
