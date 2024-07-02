<?php

namespace app\models\shapes;

use app\models\Shape;

class Dot extends Shape
{
    private const DOT_DATA = [
        [
            'label' => 'x coordinate of the dot:',
            'identifier' => 'point_x',
        ],
        [
            'label' => 'x coordinate of the dot:',
            'identifier' => 'point_y',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::DOT_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        imagesetpixel(
            $data['image'],
            $dataArr['point_x'],
            $dataArr['point_y'],
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        return $this->checkInputServerSide($inputData);
    }
}
