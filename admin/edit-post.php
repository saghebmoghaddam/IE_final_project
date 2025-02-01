<?php
include "partials/header.php";

$category_query = "SELECT * FROM categories";
$categories=mysqli_query($connection,$category_query);

// دریافت داده‌های پست از پایگاه داده اگر شناسه تعیین شده باشد
if(isset($_GET['id'])){
    $id=filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
    $query="SELECT * FROM posts WHERE id= $id";
    $result=mysqli_query($connection,$query);
    $post=mysqli_fetch_assoc($result);
}
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>ویرایش پست</h2>
        <form action="<?= ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" value="<?=$post['title']?>" name ="title" placeholder="عنوان">
            <input type="hidden" value="<?=$post['id']?>" name="id">
            <input type="hidden" value="<?=$post['thumbnail']?>" name="previous_thumbnail_name">
            <select name="category_id">
                <?php while ($category=mysqli_fetch_assoc($categories)) :?>
                <option value='<?= $category['id']?>'><?= $category['title'] ?></option>
                <?php endwhile ?>
            </select>
            <?php if(isset($_SESSION['user_is_admin'])) : ?>
            <div class="form__control inline">
                <input type="checkbox" id="is_featured" name="is_featured" value="1" checked>
                <label for="is_featured">ویژه</label>
            </div>
            <?php endif?>
            <textarea rows="8" name="body" placeholder="متن"><?=$post['body']?></textarea>

            <div class="form__control">
                <label for="thumbnail" value="">تغییر بندانگشتی</label>
                <input type="file" name ="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn">به‌روزرسانی پست</button>
        </form>
    </div>
</section>

<?php
include "../partials/footer.php";
?>
