<?php

namespace App\Controller;

use App\Model\User;
use App\TelegramHelper;

class Controller
{
    protected array $update;
    protected string $chat_id;
    protected User $user;

    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
        $this->user = User::get_or_create($this->chat_id);
    }
}
