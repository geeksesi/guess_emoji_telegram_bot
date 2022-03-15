<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

class AddLevelController extends Controller
{
    private $update;

    public function __invoke(array $update)
    {
        $this->update = $update;
    }
}
