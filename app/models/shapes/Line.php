<?php

namespace app\models\shapes;

use app\models\Shape;

class Line extends Shape
{
    private const LINE_DATA = [
        [
            'label' => 'x coordinate of point A:',
            'identifier' => 'pointA_x',
        ],
        [
            'label' => 'y coordinate of point A:',
            'identifier' => 'pointA_y',
        ],
        [
            'label' => 'x coordinate of point B:',
            'identifier' => 'pointB_x',
        ],
        [
            'label' => 'y coordinate of point B:',
            'identifier' => 'pointB_y',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::LINE_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        imageline(
            $data['image'],
            $dataArr['pointA_x'],
            $dataArr['pointA_y'],
            $dataArr['pointB_x'],
            $dataArr['pointB_y'],
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }

        if ($inputData['pointA_x'] === $inputData['pointB_x'] && $inputData['pointA_y'] === $inputData['pointB_y']) {
            return false;
        }
        return true;
    }
}
