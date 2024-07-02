<?php include "inc/header.php"; ?>

<div class="container text-center my-3">
    <p><?php echo $message; ?></p>
</div>

<div class="container-fluid my-5">
    <form action="/home/saveTempImage" method="POST" enctype="multipart/form-data">

        <div class="container row">
            <div class="container col-lg-6 text-center">
                <label class="form-label" for="name">Username:</label>
                <input class="form-control form-control-lg form-control-plaintext border border-5 w-100" type="text"
                       id="name" name="name" required>
            </div>
            <div class="container col-lg-6 text-center mb-5">
                <label class="form-label" for="picture">Picture:</label>
                <input class="form-control form-control-lg border border-5 w-100" type="file" id="picture"
                       name="picture"
                       accept="image/png, image/jpeg" required>
                <small>.jpeg or .png</small>
            </div>
        </div>

        <div class="container text-center my-3">
            <button type="submit" id="picture-save" class="btn btn-lg btn-outline-secondary">Get started!</button>
        </div>

    </form>

</div>

<?php include "inc/footer.php"; ?>
