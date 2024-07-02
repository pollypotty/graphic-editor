<?php include "inc/header.php"; ?>

<div class="container text-center my-3">
    <h4 class="edit-message"></h4>
</div>

<div class="image-div container d-flex justify-content-center align-items-center my-5">
    <img class="uploaded-img" src="<?= $imagePath ?? ''; ?>" alt="uploaded image">
</div>

<div class="container text-center my-5">
    <p><small>Your image is <?php echo $width; ?> pixels wide and <?php echo $height; ?> pixels high.</p>
    <p><small>Consider this when providing your input for editing.</small></p>
    <hr>
</div>

<div class="container-fluid m-5 p-5 text-center">

    <div class="row text-center">
        <div class="col-2">
            <select id="shape" name="shape" class="form-select" required>
                <option value="none" selected disabled>Select shape</option>
                <?php foreach ($shapes as $key => $value): ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-2 color-picker">
            <label for="color">Color:</label>
            <input id="color" type="color" name="color" onchange="setColor();">
        </div>

        <div class="col-2">
            <button id="add-btn" class="btn btn-lg btn-outline-secondary" type="button" onclick="addNewFigure();">Add
                new figure
            </button>
        </div>

        <div class="col-2"></div>

        <div class="col-2">
            <button id="upload-btn" class="btn btn-lg btn-outline-secondary" type="button" onclick="saveImage();">Upload
                to gallery
            </button>
        </div>

    </div>

    <form>
        <div class="coordinate-input col-2">

        </div>

        <div class="draw-btn container ml-0 my-5 w-25 text-start">
            <button class="btn btn-lg btn-outline-secondary" type="submit" onclick="return drawOnImage();">Let's draw!
            </button>
        </div>
    </form>

</div>

<?php include "inc/footer.php"; ?>
