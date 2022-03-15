<?php

namespace App\Helper;

use App\Controller\Admin\AddLevelController;
use App\Controller\Command\StartCommandController;
use App\Controller\Keyboard\GameStartKeyboardController;

class InputHelper
{
    private $update;

    public function __construct(array $update)
    {
        // sanitize frequent access variable
        if (isset($update["message"]["text"])) {
            $update["message"]["text"] = htmlspecialchars($update["message"]["text"]);
        }
        $this->update = $update;
    }

    public function __invoke()
    {
        switch ($this->update["message"]["chat"]["type"]) {
            case "private":
                return $this->private();
                break;
            case "group":
            case "supergroup":
                return $this->group();
                break;
        }
    }

    private function private()
    {
        if (isset($this->update["message"]["text"])) {
            return $this->text();
        }
        return null;
    }

    // has not yet any plan to work on groups
    private function group()
    {
        return null;
    }

    private function text()
    {
        if (isset($this->update["message"]["entities"])) {
            foreach ($this->update["message"]["entities"] ?? [] as $entity) {
                if ($entity["type"] === "bot_command") {
                    return $this->native_commands();
                }
            }
        }
        if ($reply_keyboard = $this->reply_keyboard()) {
            return $reply_keyboard;
        }
        if ($admin = $this->admin()) {
            return $admin;
        }
    }

    private function native_commands()
    {
        switch ($this->update["message"]["text"]) {
            case "/start":
                return (new StartCommandController())($this->update);
                break;

            default:
                return null;
                break;
        }
    }

    private function reply_keyboard()
    {
        switch ($this->update["message"]["text"]) {
            case "شروع بازی":
                return (new GameStartKeyboardController())($this->update);
                break;

            default:
                return false;
                break;
        }
    }

    private function admin()
    {
        if ($this->update["message"]["text"][0] != "!") {
            return false;
        }
        // check admin here.
        $command = substr($this->update["message"]["text"], 0, 10);
        switch ($command) {
            case "!aNewLevel":
                return (new AddLevelController())($this->update);
                break;

            default:
                return false;
                break;
        }
    }

    private function game()
    {
        # code...
    }
}
