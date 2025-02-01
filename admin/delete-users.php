<?php

require 'config/database.php';
if(!(isset($_SESSION['user_is_admin']))){
    header("location :".ROOT_URL."logout.php");

}elseif(isset($_GET["id"])){
    $id =filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
    // دریافت کاربر از پایگاه داده
    $query="SELECT * FROM users WHERE id=$id";
    $result=mysqli_query($connection,$query);
    $user = mysqli_fetch_assoc($result);
    // اطمینان از اینکه فقط یک کاربر دریافت شده است
    if(mysqli_num_rows($result)==1){
        $avatar_name = $user['avatar'];
        $avatar_path = '../images/' . $avatar_name;
        // حذف در صورت وجود تصویر
        if($avatar_path){
            unlink($avatar_path);
        }

    }

    // برای بعد
    // دریافت تمام تصاویر بندانگشتی پست‌های کاربر و حذف آن‌ها
    $thumbnails_query="SELECT thumbnail FROM posts WHERE author_id=$id";
    $thumbnails_result=mysqli_query($connection,$thumbnails_query);
    if(mysqli_num_rows($thumbnails_result)>1){
        while($thumbnail=mysqli_fetch_assoc($thumbnails_result)){
            $thumbnail_path="../images/" .  $thumbnail['thumbnail'];
            if($thumbnail_path){
                unlink($thumbnail_path);
            }
        }
    }

    // حذف کاربر از پایگاه داده
    $delete_user_query = "DELETE FROM users WHERE id  = $id";
    $delete_user_result = mysqli_query($connection, $delete_user_query);
    if(mysqli_errno($connection)){
        $_SESSION['delete-user']="نمی‌توان '{$user['firstname']}' '{$user['lastname']}' را حذف کرد";

    } else{
        $_SESSION['delete-user-success']="'{$user['firstname']} {$user['lastname']}' با موفقیت حذف شد";

    }   
}
header("location: " . ROOT_URL . "admin/manage-users.php");
die();
