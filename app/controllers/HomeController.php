<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Image;
use core\Template;
use JetBrains\PhpStorm\NoReturn;

class HomeController
{
    const HOME_VIEW = "home_view";

    public function index(): void
    {
        $message = Helper::setFlashMessage();
        Image::deleteTempImage();

        session_unset();

        $template = new Template(self::HOME_VIEW . '.php');
        $template->loadView(['message' => $message]);
    }

    #[NoReturn] public function saveTempImage(): void
    {
        if (!isset($_FILES['picture']) || !isset($_POST['name'])) {
            Helper::redirectWithMessage(ERROR_MESSAGE, 'home');
        }

        $image = new Image();
        $image->saveTempImage($_FILES['picture'], $_POST['name']);
    }
}
