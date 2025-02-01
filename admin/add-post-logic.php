<?php
require "config/database.php";

if(isset($_POST['submit'])){
    $author_id=$_SESSION['user-id'];
    $title =filter_var($_POST['title'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body =filter_var($_POST['body'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id =filter_var($_POST['category_id'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_featured =filter_var($_POST['is_featured'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $thumbnail =$_FILES['thumbnail'];

    // اگر تیک گزینه برجسته‌سازی زده نشده باشد، مقدار آن را صفر قرار دهید
    $is_featured=$is_featured==1 ?:0;

    // اعتبارسنجی داده‌های فرم
    if(!$title){
        $_SESSION['add-post']="عنوان پست را وارد کنید";
    }elseif(!$category_id){
        $_SESSION['add-post']="دسته‌بندی پست را انتخاب کنید";
    
    }elseif(!$body){
        $_SESSION['add-post']="متن پست را وارد کنید";
    
    }elseif(!$thumbnail['name']){
        $_SESSION['add-post']="تصویر بندانگشتی پست را انتخاب کنید";
    
    }else{
        // کار بر روی تصویر بندانگشتی
        // تغییر نام تصویر
        $time=time(); // برای منحصر به فرد کردن هر نام
        $thumbnail_name=$time . $thumbnail['name'];
        $thumbnail_tmp_name=$thumbnail['tmp_name'];
        $thumbnail_destination_path="../images/" . $thumbnail_name;

        // اطمینان از اینکه فایل یک تصویر است
        $allowed_files=['jpg','png','jpeg'];
        $extension=explode('.',$thumbnail_name);
        $extension=end($extension);
        if(in_array($extension,$allowed_files)){
            // اطمینان از اینکه تصویر خیلی بزرگ نیست (بیش از 2 مگابایت)
            if($thumbnail['size']<2000000){
                // بارگذاری تصویر بندانگشتی
                move_uploaded_file($thumbnail_tmp_name,$thumbnail_destination_path);

            }else{
                $_SESSION['add-post']="حجم فایل خیلی بزرگ است. باید کمتر از 2 مگابایت باشد";

            }
        }else{
            $_SESSION['add-post']="فایل باید png، jpg یا jpeg باشد";
    
        }
    }

    // هدایت با داده‌های فرم
    if(isset($_SESSION['add-post'])){
        $_SESSION['add-post-data']=$_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    }else{
        // اگر گزینه برجسته‌سازی برای این پست فعال باشد، مقدار همه پست‌ها را به 0 قرار دهید
        if($is_featured==1){
            $zero_all_is_featured_query="UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result=mysqli_query($connection,$zero_all_is_featured_query);
        }        
        // درج پست در پایگاه داده
        $query="INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured) VALUES ('$title', '$body', '$thumbnail_name', $category_id , $author_id, $is_featured)";
        $result=mysqli_query($connection,$query);
        if(mysqli_errno($connection)){
            $_SESSION['add-post']="افزودن پست با شکست مواجه شد";
            header("location: " . ROOT_URL . 'admin/index.php');
            die();
        }else{
            $_SESSION['add-post-success']="پست جدید با موفقیت اضافه شد";
            header("location: " . ROOT_URL . 'admin/index.php');
            die();

        }
    }
}

header("location: " . ROOT_URL . 'admin/index.php');
die();
?>
