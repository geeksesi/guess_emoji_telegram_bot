<?php

namespace App\Controller\Admin;

use App\Controller\Controller as ControllerController;

class Controller extends ControllerController
{
    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
    }
}
