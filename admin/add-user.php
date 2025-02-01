<?php
include "partials/header.php";
if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    // نابود کردن تمام سشن‌ها و هدایت کاربر به صفحه ورود
    session_destroy();
}
// دریافت داده‌های فرم در صورت بروز خطا در ثبت‌نام
$firstname=$_SESSION['add-user-data']['firstname'] ?? null;
$lastname=$_SESSION['add-user-data']['lastname'] ?? null;
$username=$_SESSION['add-user-data']['username'] ?? null;
$email=$_SESSION['add-user-data']['email'] ?? null;
$createpassword=$_SESSION['add-user-data']['createpassword'] ?? null;
$confirmpassword = $_SESSION['add-user-data']['confirmpassword'] ?? null;

// حذف داده‌های سشن افزودن کاربر
unset($_SESSION['add-user-data']);
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>افزودن کاربر</h2>
               
        <?php if(isset($_SESSION['add-user-success'])): ?>
        
        <div class="alert__message success">
            <p>
                <?=$_SESSION['add-user-success'];
                unset($_SESSION['add-user-success']); 
                ?>
            </p>
        </div>

        
        <?php elseif(isset($_SESSION['add-user'])): ?>
        
        <div class="alert__message error">
            <p>
                <?=$_SESSION['add-user'];
                unset($_SESSION['add-user']); 
                ?>
            </p>
        </div>

        <?php endif ?>


        <form action="<?=ROOT_URL?>admin/add-user-logic.php" enctype="multipart/form-data" method='POST'>
            <input type="text"     name ="firstname"       value ="<?= $firstname?>"  placeholder="نام">
            <input type="text"     name ="lastname"        value ="<?= $lastname?>"  placeholder="نام خانوادگی">
            <input type="username" name ="username"        value ="<?= $username      ?>"  placeholder="نام کاربری">
            <input type="email"    name ="email"           value ="<?= $email          ?>"  placeholder="ایمیل">
            <input type="password" name ="createpassword"  value ="<?= $createpassword ?>"  placeholder="رمز عبور">
            <input type="password" name ="confirmpassword" value ="<?= $confirmpassword?>"  placeholder="تأیید رمز عبور">
             <select name='userrole'>

                <option value="0">نویسنده</option>
                <option value="1">مدیر</option>

            </select>
            <div class="form__control">
                <label for="avatar">آواتار کاربر</label>
                <input type="file" name ='avatar' id="avatar">
            </div>
            <button type="submit" name='submit' class="btn">افزودن کاربر</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>
