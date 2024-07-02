<?php

namespace app\models;

abstract class Shape
{
    protected const NON_NUMERIC_INPUT_ID = 'text';
    protected array $coordinates;

    public function getInputFields(): array
    {
        return $this->coordinates;
    }

    abstract protected function drawShape(array $params): string;

    abstract public function validateInputForShape(array $inputData): bool;

    protected function prepareEdit($params): array
    {
        $format = $params['format'];

        if (isset($_SESSION['editedImage'])) {
            list(, $data) = explode(';', $_SESSION['editedImage']);
            list(, $data) = explode(',', $data);

            $imageData = base64_decode($data);
            $image = imagecreatefromstring($imageData);
        }

        if (!isset($image)) {
            $image = $params['folder'] . $_SESSION['tempImage'];
            $createImage = 'imagecreatefrom' . $format;
            $image = $createImage(getcwd() . $image);
        }

        $inputs = $params['inputData'];

        $hexColor = $params['hexColor'];
        $red = hexdec($hexColor[1] . $hexColor[2]);
        $green = hexdec($hexColor[3] . $hexColor[4]);
        $blue = hexdec($hexColor[5] . $hexColor[6]);

        $color = imagecolorallocate($image, $red, $green, $blue);

        $preparedData = [
            'format' => $format,
            'image' => $image,
            'inputs' => $inputs,
            'color' => $color,
        ];

        return $preparedData;
    }

    protected function createBase64(\GdImage $image, string $format): string
    {
        $showImage = 'image' . $format;
        ob_start();
        $showImage($image);
        $imageData = ob_get_clean();
        $imageBase64 = 'data:image/' . $format . ';base64,' . base64_encode($imageData);

        return $imageBase64;
    }

    protected function preparePolygonInput(array $inputs): array
    {
        $coordinateIntegers = [];

        foreach ($inputs as $input) {
            $coordinateIntegers[] = round($input);
        }

        return $coordinateIntegers;
    }

    protected function prepareIntInput(array $inputs): array
    {
        foreach ($inputs as $key => &$value) {
            if ($key !== self::NON_NUMERIC_INPUT_ID) {
                $value = round($value);
            }
        }
        return $inputs;
    }

    protected function checkInputServerSide(array $inputData): bool
    {
        $neededKeys = [];
        
        foreach ($this->coordinates as $data) {
            $neededKeys[] = $data['identifier'];
        }

        if ($neededKeys !== array_keys($inputData)) {
            return false;
        }

        foreach ($inputData as $key => $value) {

            $value = trim($value);

            if ((!isset($value) || $value < 0)
                && $key !== self::NON_NUMERIC_INPUT_ID
                && !is_numeric($value)
            ) {
                return false;
            }
        }
        return true;
    }
}
