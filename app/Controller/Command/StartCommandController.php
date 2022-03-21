<?php

namespace App\Controller\Command;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\KeyboardMakerHepler;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;
use App\Model\User;
use PDO;

class StartCommandController extends Controller
{
    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
    }

    public function __invoke(): bool
    {
        // check user existing
        $user = User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $this->chat_id]);

        if ($user) {
            // if exist OutputMessageEnum::START_COMMAND_USER
            OutputHelper::by_type($this->chat_id, OutputMessageEnum::START_COMMAND_USER);
            return true;
        }
        return true;
    }
}
