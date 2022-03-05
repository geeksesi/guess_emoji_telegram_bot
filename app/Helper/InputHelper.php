<?php

namespace App\Helper;

use App\Controller\Command\StartCommandController;

class InputHelper
{
    public static function handle(array $_update)
    {
        switch ($_update["message"]["chat"]["type"]) {
            case "private":
                return self::private($_update);
                break;
            case "group":
            case "supergroup":
                return self::group($_update);
                break;
        }
    }

    private static function private(array $_update)
    {
        if (isset($_update["message"]["text"])) {
            return self::text($_update);
        }
        return null;
    }

    // has not yet any plan to work on groups
    private static function group(array $_update)
    {
        return null;
    }

    private static function text(array $_update)
    {
        if (isset($_update["message"]["entities"])) {
            foreach ($_update["message"]["entities"] as $entity) {
                if ($entity["type"] === "bot_command") {
                    return self::native_commands($_update);
                }
            }
        }
    }

    private static function native_commands(array $_update)
    {
        TelegramHelper::send_message("YOU SEND  : " . $_update["message"]["text"], $_update["message"]["chat"]["id"]);

        switch ($_update["message"]["text"]) {
            case '/start':
                return (new StartCommandController())($_update);
                break;

            default:
                return null;
                break;
        }
    }
}
