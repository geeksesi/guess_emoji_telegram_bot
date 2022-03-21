<?php

namespace App\Controller;

use App\Controller\Command\StartCommandController;
use App\Model\User;
use App\TelegramHelper;

abstract class Controller
{
    protected array $update;
    protected string $chat_id;
    protected User $user;

    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
        $this->user = User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $this->chat_id]);
    }
}
