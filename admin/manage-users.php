<?php
include "partials/header.php";


$current_admin_id = $_SESSION['user-id'];
if(!isset($_SESSION['user_is_admin'])){
    header("location: " . ROOT_URL . "logout.php");
    //destroy all sessions and redirect user to login page
    session_destroy();
}
$query="SELECT id,firstname,lastname,username,is_admin FROM users WHERE NOT id='$current_admin_id'";
$users=mysqli_query($connection,$query);
?>


    <section class="dashboard">
    <?php
        if(isset($_SESSION['add-user-success'])): 
        ?> 
            <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-user-success'];
                unset($_SESSION['add-user-success']);
                ?>
            </p>
            
            </div>
            <?php
        elseif(isset($_SESSION['edit-user'])): 
        ?> 
            <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-user'];
                unset($_SESSION['edit-user']);
                ?>
            </p>
            
            </div>
        <?php
        elseif(isset($_SESSION['edit-user-success'])): 
        ?> 
            <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-user-success'];
                unset($_SESSION['edit-user-success']);
                ?>
            </p>
            
            </div>
        <?php
            elseif(isset($_SESSION['delete-user'])): 
        ?> 
            <div class="alert__message error container">
            <p>
                <?= $_SESSION['delete-user'];
                unset($_SESSION['delete-user']);
                ?>
            </p>
            
            </div>
        <?php
            elseif(isset($_SESSION['delete-user-success'])): 
        ?> 
            <div class="alert__message success container">
            <p>
                <?= $_SESSION['delete-user-success'];
                unset($_SESSION['delete-user-success']);
                ?>
            </p>
            
            </div>
        <?php endif ?>
        <div class="container dashboard__container">
    
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <button id="hide__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-left-b"></i></button>
    
            <aside>
    <ul>
        <li>
            <a href="<?= ROOT_URL ?>admin/add-post.php">
                <i class="uil uil-pen"></i>
                <h5>اضافه کردن پست</h5>
            </a>
        </li>                
            
        <li>
            <a href="<?= ROOT_URL ?>admin/index.php">
                <i class="uil uil-postcard"></i>                            
                <h5>مدیریت پست‌ها</h5>
            </a>
        </li>
        <?php  if(isset($_SESSION['user_is_admin'])) : ?>
        <li>
            <a href="<?= ROOT_URL ?>admin/add-user.php">
                <i class="uil uil-user-plus"></i> 
                <h5>اضافه کردن کاربر</h5>
            </a>
        </li>  

        <li>
            <a href="<?= ROOT_URL ?>admin/manage-users.php" class="active">
                <i class="uil uil-users-alt"></i>
                <h5>مدیریت کاربران</h5>
            </a>
        </li>                    
        <li>
            <a href="<?= ROOT_URL ?>admin/add-category.php">
                <i class="uil uil-edit"></i>
                <h5>اضافه کردن دسته‌بندی</h5>
            </a>
        </li>                    
        <li>
            <a href="<?= ROOT_URL ?>admin/manage-categories.php">
                <i class="uil uil-list-ul"></i>
                <h5>مدیریت دسته‌بندی‌ها</h5>
            </a>
        </li>
        <?php endif ?>
    </ul>
</aside>
<main>
    <h2>مدیریت کاربران</h2>
    <?php if(mysqli_num_rows($users) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>نام</th>
                <th>نام کاربری</th>
                <th>ویرایش</th>
                <th>حذف</th>
                <th>مدیر</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $user["firstname"] . " " . $user['lastname'] ?></td>
                <td><?= $user["username"] ?> </td>
                <td><a href="<?= ROOT_URL ?>admin/edit-user.php?id=<?= $user['id'] ?>" class="btn sm">ویرایش</a></td>
                <td><a href="<?= ROOT_URL ?>admin/delete-users.php?id=<?= $user['id'] ?>" class="btn sm danger">حذف</a></td>
                <td><?= $user["is_admin"] ? 'بله' : 'خیر' ?></td>
            </tr>
            <?php endwhile ?>
        </tbody>
    </table>
    <?php else : ?>
        <div class="alert__message error">هیچ کاربری پیدا نشد</div>
    <?php endif ?>
</main>
</div>
</section>

<?php
include "../partials/footer.php";
?>
