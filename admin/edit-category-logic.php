<?php
require "config/database.php";
if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    // تمام سشن‌ها را نابود کرده و کاربر را به صفحه ورود هدایت کنید
    session_destroy();
}
if(isset($_POST['submit'])){    
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$title || !$description){
        $_SESSION['edit-category'] = "ورودی فرم نامعتبر در صفحه ویرایش دسته‌بندی";
    } else {
        $query = "UPDATE categories SET title='$title', description='$description' WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);  

        if(mysqli_errno($connection)){
            $_SESSION['edit-category'] = "نمی‌توان دسته‌بندی را به‌روز کرد";

        } else {
            $_SESSION['edit-category-success'] = "دسته‌بندی '$title' با موفقیت به‌روز شد";
        }
    }
}

header('location: ' . ROOT_URL . "admin/manage-categories.php");
die();
