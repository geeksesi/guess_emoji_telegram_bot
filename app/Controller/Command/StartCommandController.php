<?php

namespace App\Controller\Command;

use App\Controller\Controller;

class StartCommandController extends Controller
{
    private $update;

    public function __invoke(array $update)
    {
        $this->update = $update;
    }
}
