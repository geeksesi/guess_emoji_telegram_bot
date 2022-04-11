<?php

namespace App\Controller\Command;

use App\Controller\Admin\Controller;
use App\Helper\OutputHelper;
use App\Model\User;

class UserCommandController extends Controller
{
    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
    }

    public function __invoke(): bool
    {
        if ($user = User::find(substr($this->update["message"]["text"], 6))) {
            OutputHelper::profile($this->chat_id, $user);
        }

        return true;
    }
}
