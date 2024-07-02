<?php

namespace app\models\shapes;

use app\models\Shape;

class Round extends Shape
{
    private const ROUND_DATA = [
        [
            'label' => 'x coordinate of center point',
            'identifier' => 'center_x',
        ],
        [
            'label' => 'y coordinate of center point',
            'identifier' => 'center_y',
        ],
        [
            'label' => 'ray of the circle',
            'identifier' => 'ray',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::ROUND_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        imageellipse(
            $data['image'],
            $dataArr['center_x'],
            $dataArr['center_y'],
            $dataArr['ray'] * 2,
            $dataArr['ray'] * 2,
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }

        if ($inputData['ray'] === '0') {
            return false;
        }
        return true;
    }
}
