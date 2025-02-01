<?php
require 'config/database.php';

if(isset($_POST['submit'])){
    $id=filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
    $is_featured=filter_var($_POST['is_featured'],FILTER_SANITIZE_NUMBER_INT);
    $title=filter_var($_POST['title'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body=filter_var($_POST['body'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $previous_thumbnail_name=filter_var($_POST['previous_thumbnail_name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id=filter_var($_POST['category_id'],FILTER_SANITIZE_NUMBER_INT);
    $thumbnail=$_FILES['thumbnail'];

    // اگر تیک انتخاب نشده باشد، featured را صفر تنظیم کنید
    $is_featured=$is_featured == 1  ?: 0;

    // بررسی و اعتبارسنجی مقادیر ورودی
    if(!$title){
        $_SESSION['edit-post']="نتوانستیم پست را به‌روز کنیم. داده‌های فرم در صفحه ویرایش نامعتبر است.";
    }elseif(!$category_id){
        $_SESSION['edit-post']="نتوانستیم پست را به‌روز کنیم. داده‌های فرم در صفحه ویرایش نامعتبر است.";
    }elseif(!$body){
        $_SESSION['edit-post']="نتوانستیم پست را به‌روز کنیم. داده‌های فرم در صفحه ویرایش نامعتبر است.";
    }else{
        if($thumbnail['name']){
            $previous_thumbnail_destination='../images/' . $previous_thumbnail_name;
            if($previous_thumbnail_destination){
                unlink($previous_thumbnail_destination);
            }
        
        // کار بر روی تصویر بندانگشتی جدید
        // تغییر نام تصویر
        
        $time=time();
        $thumbnail_name=$time . $thumbnail['name'];
        $thumbnail_tmp_name=$thumbnail['tmp_name'];
        $thumbnail_destination_path="../images/" . $thumbnail_name;

        // اطمینان از اینکه فایل یک تصویر است
        $allowed_files=['jpg','png','jpeg'];
        $extension=explode('.',$thumbnail_name);
        $extension=end($extension);
        if(in_array($extension,$allowed_files)){
            // اطمینان از اینکه تصویر خیلی بزرگ نیست (بیش از ۲ مگابایت)
            if($thumbnail['size']<2000000){
                // بارگذاری تصویر بندانگشتی
                move_uploaded_file($thumbnail_tmp_name,$thumbnail_destination_path);

            }else{
                $_SESSION['edit-post']="حجم فایل خیلی بزرگ است. باید کمتر از ۲ مگابایت باشد.";

            }
        }else{
            $_SESSION['edit-post']="فایل باید png، jpg یا jpeg باشد.";
    
        }
    }
        

    }

    // اگر خطایی در داده‌های فرم وجود دارد، به مدیریت پست هدایت کنید
    if(isset($_SESSION['edit-post'])){

        header('location: ' . ROOT_URL . 'admin/');
        die();
    }else{
        // اگر is_featured برای این پست برابر با ۱ باشد، is_featured تمام پست‌ها را برابر با ۰ تنظیم کنید
        if($is_featured==1){
            $zero_all_is_featured_query="UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result=mysqli_query($connection,$zero_all_is_featured_query);
        }        
        $thumbnail_to_insert= $thumbnail_name ?? $previous_thumbnail_name;

        // درج پست در پایگاه داده
        $query="UPDATE posts SET title='$title', body='$body' ,thumbnail='$thumbnail_to_insert' ,category_id='$category_id',is_featured=$is_featured WHERE id=$id LIMIT 1";
        $result=mysqli_query($connection,$query);   
    }

    if(!mysqli_errno($connection)){
        $_SESSION['edit-post-success']="پست با موفقیت به‌روز شد.";
    }
}
header('location: ' . ROOT_URL . 'admin/');
die();
