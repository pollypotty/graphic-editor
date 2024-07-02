<?php

namespace core;

class Template
{
    private string $templatePath;

    public function __construct($fileName)
    {
        $this->templatePath = __DIR__ . '/../app/views/' . $fileName;
    }

    public function render(array $data = []) : string|false
    {
        ob_start();
        extract($data);
        include $this->templatePath;
        return ob_get_clean();
    }

    public function loadView(array $data = []) : void
    {
        echo $this->render($data);
    }
}
