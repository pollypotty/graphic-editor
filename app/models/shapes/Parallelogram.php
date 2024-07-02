<?php

namespace app\models\shapes;

use app\models\Shape;

class Parallelogram extends Shape
{
    private const PARALLELOGRAM_DATA = [
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
        [
            'label' => 'x coordinate of point D:',
            'identifier' => 'pointD_x',
        ],
        [
            'label' => 'y coordinate of point D:',
            'identifier' => 'pointD_y',
        ],
    ];

    public function __construct()
    {
        $this->coordinates = self::PARALLELOGRAM_DATA;
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

        $coordinateArray = $this->preparePolygonInput($inputData);

        for ($i = 0; $i <= count($coordinateArray) - 4; $i = $i + 2) {
            for ($j = $i + 2; $j <= count($coordinateArray) - 2; $j = $j + 2) {
                if ($coordinateArray[$i] === $coordinateArray[$j] && $coordinateArray[$i + 1] === $coordinateArray[$j + 1]) {
                    return false;
                }
            }
        }

        $vertexA_number = 0;
        $vertexB_number = 1;
        $vertexC_number = 2;
        $vertexD_number = 3;

        $sideAD_vector = $this->makeVector($coordinateArray, $vertexA_number, $vertexD_number);
        $sideBC_vector = $this->makeVector($coordinateArray, $vertexB_number, $vertexC_number);

        if ($sideAD_vector !== $sideBC_vector) {
            return false;
        }
        return true;
    }

    public function makeVector(array $coordinates, int $startPointNumber, int $endPointNumber): array
    {
        $startPointX = $coordinates[$startPointNumber * 2];
        $startPointY = $coordinates[$startPointNumber * 2 + 1];

        $endPointX = $coordinates[$endPointNumber * 2];
        $endPointY = $coordinates[$endPointNumber * 2 + 1];

        $vectorX = $endPointX - $startPointX;
        $vectorY = $endPointY - $startPointY;

        return [$vectorX, $vectorY];
    }
}
