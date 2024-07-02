<?php

namespace app\models\shapes;

use app\models\Shape;

class Text extends Shape
{
    private const TEXT_DATA = [
        [
            'label' => 'x coordinate of starting point:',
            'identifier' => 'point_x',
        ],
        [
            'label' => 'y coordinate of starting point:',
            'identifier' => 'point_y',
        ],
        [
            'label' => 'text to write on the picture',
            'identifier' => 'text',
        ],
    ];

    private const FONT_PATH = __DIR__ . '/../../../public/fonts/LinLibertine_RB.otf';

    public function __construct()
    {
        $this->coordinates = self::TEXT_DATA;
    }

    public function drawShape($params): string
    {
        $data = $this->prepareEdit($params);
        $dataArr = $this->prepareIntInput($data['inputs']);

        $defaultTextSize = 20;
        $defaultTextOrientation = 0;

        imagettftext(
            $data['image'],
            $defaultTextSize,
            $defaultTextOrientation,
            $dataArr['point_x'],
            $dataArr['point_y'],
            $data['color'],
            self::FONT_PATH,
            $dataArr['text']
        );

        return $this->createBase64($data['image'], $data['format']);
    }

    public function validateInputForShape(array $inputData): bool
    {
        if (!$this->checkInputServerSide($inputData)) {
            return false;
        }
        return true;
    }
}
