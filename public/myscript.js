const errorMessage = "Something went wrong."
const wrongInputMessage = "Your input is not correct for the chosen shape."

$(document).ready(function () {
    $('#shape').change(function () {
        $.ajax({
            type: "post",
            url: "/editor/getShapeCoordinates",
            data: {shape: $("#shape").val()},
            cache: false,
            success: function (response) {
                inputs = JSON.parse(response);

                let inputHtml = '<br>';

                identifiers = [];

                for (let input of inputs) {
                    const label = input.label;
                    const identifier = input.identifier;

                    identifiers.push(identifier);

                    inputHtml += '<label for="' + identifier + '" class="form-label">' + label + '</label>';
                    inputHtml += '<input class="form-control" id="' + identifier + '" name="' + input + '" type="text" required><br><br>';
                }

                inputHtml += '<input id="ids" value="' + identifiers + '" hidden>';

                $('.coordinate-input').html(inputHtml);
                $('.draw-btn').css('display', 'block');
            },
            error: function () {
                window.alert(errorMessage);
            }
        });
    });
});

function setColor() {
    $.ajax({
        type: "post",
        url: "/editor/setColor",
        data: {color: $('#color').val()},
        cache: false,
        error: function () {
            window.alert(errorMessage);
        }
    });
}

function drawOnImage() {
    let identifiers = $('#ids').val().split(",");
    let inputData = {};

    $('.coordinate-input input').css('border-color', '');

    for (let identifier of identifiers) {
        let inputField = $('#' + identifier);

        if (inputField.val().length === 0) {
            inputField.css('border-color', 'red');
            window.alert('All fields must be filled.');
            return false;
        }

        const numericRegex = /^-?\d*\.?\d+$/g;

        if (identifier !== 'text' && !numericRegex.test(inputField.val())) {
            inputField.css('border-color', 'red');
            window.alert('Please enter a valid number.');
            return false;
        }

        if (inputField.val() < 0) {
            inputField.css('border-color', 'red');
            window.alert('Negative numbers are not allowed.');
            return false;
        }

        inputData[identifier] = inputField.val();
    }

    $.ajax({
        type: "post",
        url: "/editor/validateInput",
        data: {inputData: inputData},
        cache: false,
        success: function (response) {

            if (response.status === false) {
                $('.coordinate-input input').css('border-color', 'red');
                window.alert(wrongInputMessage);
            }

            if (response.status === true) {
                $.ajax({
                    type: "post",
                    url: "/editor/editImage",
                    cache: false,
                    success: function (response) {
                        $('.draw-btn').css('display', 'none');
                        $('#upload-btn').css('display', 'block');
                        $('#add-btn').css('display', 'block');
                        $('#shape').attr('disabled', true);
                        $('.uploaded-img').attr('src', response);
                        $('.coordinate-input input').attr('disabled', true);
                    },
                    error: function () {
                        window.alert(errorMessage);
                    }
                });
            }
        },
        error: function () {
            window.alert(errorMessage);
        }
    });
    return false;
}

function saveImage() {
    let image = $('.uploaded-img').attr('src');

    $.ajax({
        type: "post",
        url: "/editor/saveImage",
        data: {img: image},
        cache: false,
        success: function (response) {
            $('.edit-message').html(response);
        },
        error: function () {
            window.alert(errorMessage);
        }
    });
}

function openPic(imgPath) {
    $('.big-pic').attr('src', imgPath);
    $('.my-modal').css('display', 'block');
}

function closePic() {
    $('.my-modal').css('display', 'none');
}

function addNewFigure() {
    $('.coordinate-input').html('');
    $('#shape').val('none').attr('disabled', false);
    $('#add-btn').css('display', 'none');
    $('.edit-message').html('');
}
