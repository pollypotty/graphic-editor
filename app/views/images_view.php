<?php include "inc/header.php"; ?>

<div class="row d-flex justify-content-center">

    <?php foreach ($data as $image): ?>

        <div class="img-card col-lg-3 col-md-4 col-sm-6 col-xs-12 m-3 mb-5">
            <div class="card square-card mb-2">
                <div class="card-content d-flex align-items-center justify-content-center p-2">
                    <img class="card-img-top img-fluid w-100 m-1 p-3" src='<?= $image['image_path']; ?>'
                         alt="edited image"
                         onclick="return openPic('<?php echo $image['image_path']; ?>');">
                </div>
            </div>
            <div>
                <div class="text-center mb-1">
                    <small>Username: <?php echo htmlentities($image['username']); ?></small><br>
                    <small><?php echo $image['save_date']; ?></small>
                    <hr>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>

<div class="modal my-modal">
    <div class="modal-content">
        <h3 class="close d-flex justify-content-end my-2 mx-5" onclick="closePic();">&times;</h3>
        <img class="big-pic mb-5 p-5 rounded" src="" alt="">
    </div>
</div>

<?php include "inc/footer.php"; ?>
