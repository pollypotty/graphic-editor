<?php

namespace app\models\shapes;

use app\models\Shape;

class Oval extends Shape
{
    private const RECTANGLE_DATA = [
        [
            'label' => 'x coordinate of center point',
            'identifier' => 'center_x',
        ],
        [
            'label' => 'y coordinate of center point',
            'identifier' => 'center_y',
        ],
        [
            'label' => 'width of the oval',
            'identifier' => 'width',
        ],
        [
            'label' => 'height of the oval',
            'identifier' => 'height',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::RECTANGLE_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        imageellipse(
            $data['image'],
            $dataArr['center_x'],
            $dataArr['center_y'],
            $dataArr['width'],
            $dataArr['height'],
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }

        if ($inputData['width'] === '0' || $inputData['height'] === '0') {
            return false;
        }
        return true;
    }
}
