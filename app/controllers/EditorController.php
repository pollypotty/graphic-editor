<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Image;
use core\Template;
use JetBrains\PhpStorm\NoReturn;

class EditorController
{
    const EDITOR_VIEW = "editor_view";
    const IMAGE_FOLDER = "/public/images/temp/";
    const SHAPE_NAMESPACE = 'app\models\shapes\\';
    const SHAPES = [
        'Square',
        'Oval',
        'Triangle',
        'Parallelogram',
        'Round',
        'Rectangle',
        'Dot',
        'Text',
        'Line',
    ];

    public function index(): void
    {
        if (!isset($_SESSION['username']) && !isset($_SESSION['tempImage'])) {
            Helper::redirectWithMessage('', 'home');
        }

        $template = new Template(self::EDITOR_VIEW . '.php');
        $imagePath = self::IMAGE_FOLDER . $_SESSION['tempImage'];
        $template->loadView([
            'imagePath' => $imagePath,
            'shapes' => self::SHAPES,
            'width' => Image::getImageSize($_SESSION['tempImage'], 'width'),
            'height' => Image::getImageSize($_SESSION['tempImage'], 'height'),
        ]);
    }

    #[NoReturn] public function getShapeCoordinates(): void
    {
        $shapeNumber = $_POST['shape'];
        $shapeName = self::SHAPES[$shapeNumber];

        $_SESSION['shape'] = $shapeName;

        $className = self::SHAPE_NAMESPACE . $shapeName;
        $shape = new $className();

        $inputs = $shape->getInputFields();
        exit(json_encode($inputs));
    }

    public function setColor(): void
    {
        $_SESSION['color'] = $_POST['color'];
    }

    #[NoReturn] public function editImage(): void
    {
        $params = [
            'image' => self::IMAGE_FOLDER . $_SESSION['tempImage'],
            'inputData' => $_SESSION['inputData'],
            'format' => $_SESSION['format'],
            'hexColor' => $_SESSION['color'] ?? '#000000',
            'folder' => self::IMAGE_FOLDER,
        ];

        $shapeName = $_SESSION['shape'];
        $className = self::SHAPE_NAMESPACE . $shapeName;

        $shape = new $className();

        $editedImage = $shape->drawShape($params);
        $_SESSION['editedImage'] = $editedImage;
        exit($editedImage);
    }

    #[NoReturn] public function saveImage(): void
    {
        if (!isset($_POST['img'])) {
            http_response_code(400);
            exit;
        }

        $image = new Image();
        exit($image->savePicture($_POST['img']));
    }

    #[NoReturn] public function validateInput(): void
    {
        $_SESSION['inputData'] = $_POST['inputData'];

        $shapeName = $_SESSION['shape'];
        $className = self::SHAPE_NAMESPACE . $shapeName;

        $shape = new $className();

        $response = $shape->validateInputForShape($_SESSION['inputData']);
        header('Content-Type: application/json');
        exit(json_encode(['status' => $response]));
    }
}
