<?php

namespace app\models\shapes;

use app\models\Shape;

class Square extends Shape
{
    private const SQUARE_DATA = [
        [
            'label' => 'x coordinate of point A:',
            'identifier' => 'pointA_x',
        ],
        [
            'label' => 'y coordinate of point A:',
            'identifier' => 'pointA_y',
        ],
        [
            'label' => 'x coordinate of point C:',
            'identifier' => 'pointC_x',
        ],
        [
            'label' => 'y coordinate of point C:',
            'identifier' => 'pointC_y',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::SQUARE_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        imagerectangle(
            $data['image'],
            $dataArr['pointA_x'],
            $dataArr['pointA_y'],
            $dataArr['pointC_x'],
            $dataArr['pointC_y'],
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }

        if ($inputData['pointA_x'] === $inputData['pointC_x'] && $inputData['pointA_y'] === $inputData['pointC_y']) {
            return false;
        }

        $width = abs($inputData['pointA_x'] - $inputData['pointC_x']);
        $height = abs($inputData['pointA_y'] - $inputData['pointC_y']);

        if ($width !== $height) {
            return false;
        }
        return true;
    }
}
