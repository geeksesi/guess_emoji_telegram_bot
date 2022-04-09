<?php

namespace App\Helper;

use App\Controller\Admin\AddHintController;
use App\Controller\Admin\AddLevelController;
use App\Controller\Admin\AddNewAdvertiseController;
use App\Controller\Admin\AddOutputMessageController;
use App\Controller\Admin\GetUserByChatIdController;
use App\Controller\Admin\HelpController;
use App\Controller\Admin\ListHintsController;
use App\Controller\Admin\ListLevelsController;
use App\Controller\Admin\ListOutputMessagesController;
use App\Controller\Command\ChatIdCommandController;
use App\Controller\Command\StartCommandController;
use App\Controller\Game\GameController;
use App\Model\User;

class InputHelper
{
    private $update;

    private array $replays = [
        'سکه رایگان'      => 'FreeCreditKeyboardController',
        'برترین ها'       => 'LeaderBoardKeyboardController',
        'ادامه بازی'      => 'GameContinueKeyboardController',
        'خرید سکه'        => 'BuyCreditKeyboardController',
        'آموزش ساخت بازی' => 'YoutubeKeyboardController',
        'شروع بازی'       => 'GameStartKeyboardController',
        'درباره ما'       => 'AboutKeyboardController',
        'پروفایل'         => 'ProfileKeyboardController',
        'تماس با ما'      => 'ContactKeyboardController',
        'کمک می‌خوای'      => 'HintKeyboardController',
        'حمایت از ما'     => 'SupportKeyboardController',
        'سکه‌های شما'      => 'YourCreditKeyboardController',
        'بازگشت'          => 'BackKeyboardController',
    ];

    private string $controllersNs = 'App\Controller\Keyboard';

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
        try {
            $this->run();
        } catch (\Throwable $th) {
            return (new ExceptionHepler($th))();
        }
    }

    public function run()
    {
        return match ($this->update["message"]["chat"]["type"]) {
            "private" => $this->private(),
            "group", "supergroup" => $this->group(),
            default => false,
        };
    }

    private function private()
    {
        if ( ! User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $this->update["message"]["chat"]["id"]])) {
            return (new StartCommandController($this->update))();
        }

        if (isset($this->update["message"]["text"])) {
            return $this->text();
        }

        return null;
    }

    // has not yet any plan to work on groups
    private function group()
    {
        if ($admin = $this->admin()) {
            return $admin;
        }
        if (isset($this->update["message"]["text"])) {
            if ($this->update["message"]["text"] == "/chat_id") {
                return (new ChatIdCommandController($this->update))();
            }
        }
        if ($this->update["message"]["chat"]["id"] != $_ENV["ADMIN_GP"]) {
            return null;
        }
        TelegramHelper::send_message(
            json_encode(
                $this->update,
                JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_INVALID_UTF8_IGNORE + JSON_UNESCAPED_SLASHES
            ),
            $this->update["message"]["chat"]["id"]
        );
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

        return $this->game();
    }

    private function native_commands()
    {
        return match ($this->update["message"]["text"]) {
            "/start" => (new StartCommandController($this->update))(),
            "/chat_id" => (new ChatIdCommandController($this->update))(),
            default => false,
        };
    }

    private function reply_keyboard()
    {
        foreach ($this->replays as $text => $class) {
            if (str_contains($this->update["message"]["text"], $text)) {
                $class = $this->controllersNs.'\\'.$class;

                return (new $class($this->update))();
            }
        }

        return false;
    }

    private function admin()
    {
        if ($this->update["message"]["text"][0] != "!") {
            return false;
        }
        // check admin here.
        if (
            $this->update["message"]["chat"]["id"] != $_ENV["ADMIN"] &&
            $this->update["message"]["chat"]["id"] != $_ENV["ADMIN_GP"]
        ) {
            return false;
        }
        $command = substr($this->update["message"]["text"], 0, 10);

        return match ($command) {
            "!help" => (new HelpController($this->update))(),
            "!aNewLevel" => (new AddLevelController($this->update))(),
            "!aNewHints" => (new AddHintController($this->update))(),
            "!aOuttexts" => (new AddOutputMessageController($this->update))(),
            "!listLevel" => (new ListLevelsController($this->update))(),
            "!listHints" => (new ListHintsController($this->update))(),
            "!listOMesg" => (new ListOutputMessagesController($this->update))(),
            "!getUserId" => (new GetUserByChatIdController($this->update))(),
            "!newAds" => (new AddNewAdvertiseController($this->update))(),
            default => false,
        };
    }

    private function game()
    {
        return (new GameController($this->update))();
    }
}
