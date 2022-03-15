<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;

class GameStartKeyboardController extends Controller
{
    private $update;

    public function __invoke(array $update)
    {
        $this->update = $update;
    }
}
