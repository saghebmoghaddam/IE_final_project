<?php

require 'config/database.php';
if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    // تخریب تمام سشن‌ها و هدایت کاربر به صفحه ورود
    session_destroy();
}
if(isset($_POST['submit'])){
    // دریافت داده‌های فرم
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$title){
        $_SESSION['add-category'] = "عنوان را وارد کنید";

    }elseif(!$description){
        $_SESSION['add-category'] = "توضیحات را وارد کنید";
    
    }
    // اگر ورودی نامعتبر بود، کاربر را به صفحه افزودن دسته‌بندی هدایت کنید
    if(isset($_SESSION['add-category'])){
        $_SESSION['add-category-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-category.php');
        die();
    }else{
        // درج دسته‌بندی در پایگاه داده
        $query = "INSERT INTO categories (title, description) VALUES ('$title', '$description')";
        $result = mysqli_query($connection, $query);
        if(mysqli_errno($connection)){
            $_SESSION['add-category'] = "نمی‌توان دسته‌بندی را افزود";
            header('location: ' . ROOT_URL . 'admin/add-category.php');
            die();
        }else{
            $_SESSION['add-category-success'] = "دسته‌بندی $title با موفقیت اضافه شد";
            header('location: ' . ROOT_URL . 'admin/manage-categories.php');
            
        }
    }
}
