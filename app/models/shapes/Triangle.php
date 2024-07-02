<?php

namespace app\models\shapes;

use app\models\Shape;

class Triangle extends Shape
{
    private const TRIANGLE_DATA = [
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
        $this->coordinates = self::TRIANGLE_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $coordinateIntegers = $this->preparePolygonInput($data['inputs']);

        imagepolygon(
            $data['image'],
            $coordinateIntegers,
            $data['color']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }

        $area = $inputData['pointA_x'] * ($inputData['pointB_y'] - $inputData['pointC_y'])
            + $inputData['pointB_x'] * ($inputData['pointC_y'] - $inputData['pointA_y'])
            + $inputData['pointC_x'] * ($inputData['pointA_y'] - $inputData['pointB_y']);

        if ($area === 0) {
            return false;
        }
        return true;
    }
}
