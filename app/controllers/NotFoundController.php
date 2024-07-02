<?php

namespace app\controllers;

use core\Template;

class NotFoundController
{
    const NOT_FOUND_VIEW = "not_found_view";
    public function index() : void
    {
        $template = new Template(self::NOT_FOUND_VIEW . '.php');
        $template->loadView();
    }
}
