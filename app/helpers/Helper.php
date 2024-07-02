<?php

namespace app\helpers;

use JetBrains\PhpStorm\NoReturn;

class Helper
{
    #[NoReturn] public static function redirectWithMessage(string $message, string $location): void
    {
        $_SESSION['message'] = $message;
        header("Location: /" . $location);
        exit();
    }

    public static function setFlashMessage(): string
    {
        if (!isset($_SESSION['message'])) {
            return '';
        }

        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
}
