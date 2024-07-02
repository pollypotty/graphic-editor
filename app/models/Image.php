<?php

namespace app\models;

use app\helpers\Helper;
use core\DatabaseHandler;
use JetBrains\PhpStorm\NoReturn;

class Image
{
    private const TEMP_FOLDER = __DIR__ . '/../../public/images/temp/';
    private const GALLERY_FOLDER = '/public/images/gallery/';
    private const GALLERY_FOLDER_ABSOLUTE = __DIR__ . '/../../public/images/gallery/';
    private const SUCCESS_MESSAGE = "Picture uploaded successfully.";
    private const WRONG_FORMAT_MESSAGE = "The file you provided in not .jpeg or .png.";
    private const ALLOWED_FORMATS = [
        "image/jpeg",
        "image/png",
    ];


    #[NoReturn] public function saveTempImage(array $pictureInfo, string $username): void
    {
        $realFormat = $this->getFormat($pictureInfo['tmp_name']);

        if (!in_array($realFormat, self::ALLOWED_FORMATS)) {
            Helper::redirectWithMessage(self::WRONG_FORMAT_MESSAGE, 'home');
        }

        $formatType = $pictureInfo['type'];
        $format = explode('/', $formatType)[1];

        $timestamp = time();
        $fileName = $timestamp . '.' . $format;
        $targetPath = self::TEMP_FOLDER . $fileName;

        if (!move_uploaded_file($pictureInfo["tmp_name"], $targetPath)) {
            Helper::redirectWithMessage(ERROR_MESSAGE, 'home');
        }

        $_SESSION['username'] = $username;
        $_SESSION['tempImage'] = $fileName;
        $_SESSION['format'] = $format;
        Helper::redirectWithMessage('', 'editor');
    }

    #[NoReturn] public function savePicture(string $base64Img): string
    {
        list(, $imageData) = explode(';', $base64Img);
        list(, $encodedImageData) = explode(',', $imageData);
        $decodedImage = base64_decode($encodedImageData);

        $timestamp = time();
        $fileName = $timestamp . '.' . $_SESSION['format'];
        $targetPath = self::GALLERY_FOLDER_ABSOLUTE . $fileName;

        file_put_contents($targetPath, $decodedImage);

        if (!file_exists($targetPath)) {
            return ERROR_MESSAGE;
        }

        $imagePath = self::GALLERY_FOLDER . $fileName;

        $dbConn = new DatabaseHandler();

        $dbConn->query("INSERT INTO uploads(username, save_date,  image_path)
                                VALUES(:username, NOW(), :image_path)");

        $dbConn->bind(':username', $_SESSION['username']);
        $dbConn->bind(':image_path', $imagePath);

        if (!$dbConn->execute()) {
            return ERROR_MESSAGE;
        }

        return self::SUCCESS_MESSAGE;
    }

    public static function deleteTempImage(): void
    {
        if (isset($_SESSION['tempImage'])) {
            unlink(self::TEMP_FOLDER . $_SESSION['tempImage']);
            unset($_SESSION['tempImage']);
        }
    }

    public function loadGallery(): array
    {
        $dbConn = new DatabaseHandler();

        $dbConn->query("SELECT * FROM uploads
                                ORDER BY save_date DESC");

        return $dbConn->resultSet();
    }

    public static function getImageSize(string $fileName, string $sizeOf): string
    {
        switch ($sizeOf) {
            case 'width':
                return getimagesize(self::TEMP_FOLDER . $fileName)[0];
            case 'height':
                return getimagesize(self::TEMP_FOLDER . $fileName)[1];
        }

        return false;
    }

    private function getFormat(string $pictureTempName): bool|string
    {
        $f_info = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($f_info, $pictureTempName);
        finfo_close($f_info);

        return $fileMimeType;
    }
}
