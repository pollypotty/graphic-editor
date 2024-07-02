<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Image;
use core\Template;

class ImagesController
{
    const IMAGES_VIEW = "images_view";

    public function index(): void
    {
        Image::deleteTempImage();

        $image = new Image();
        $imagesData = $image->loadGallery();

        $template = new Template(self::IMAGES_VIEW . '.php');
        $template->loadView(['data' => $imagesData]);
    }
}
