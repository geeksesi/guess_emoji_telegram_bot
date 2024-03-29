<?php

namespace App\Helper;

class TelegramHelper
{
    private static $url;

    /**
     * set url parameter to use
     *
     * @return  void    [return description]
     */
    private static function init(): void
    {
        self::$url = "https://api.telegram.org/bot" . $_ENV["TOKEN"] . "/";
    }

    /**
     * [execute description]
     *
     * @param   string  $_method      [$_method description]
     * @param   array   $_parameters  [$_parameters description]
     *
     * @return  array|bool                [return description]
     */
    private static function execute(string $_method, array $_parameters)
    {
        if (!isset(self::$url)) {
            self::init();
        }

        $url = self::$url . $_method;

        $curl = curl_init($url);
        if (!empty($_parameters)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($_parameters));
        }
        if (!empty($_ENV["PROXY"])) {
            curl_setopt($curl, CURLOPT_PROXY, $_ENV["PROXY"]);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:application/json"]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $error = null;
        if (curl_errno($curl)) {
            $error = curl_error($curl);
        }
        curl_close($curl);
        if (!is_null($error)) {
            //(new ExceptionHepler(new \Exception("CURL :" . curl_error($curl))))();
            return false;
        }

        $output = json_decode($result, true);
        if (is_null($output)) {
            //$exp = new \Exception("CURL : EMPTY RESPONSE");
            //(new ExceptionHepler($exp))();
            return false;
        }
        if (!isset($output["ok"]) || !$output["ok"]) {
            //$exp =  new \Exception("TG : " . json_encode($output["description"]));
            //(new ExceptionHepler($exp))();
            return false;
        }
        return $output;
    }

    /**
     * [send_message description]
     *
     * @param   string  $_text     [$_text description]
     * @param   string  $_chat_id  [$_chat_id description]
     *
     * @return  array              [return description]
     */
    public static function send_message(string $_text, string $_chat_id, array $_keyboard = [])
    {
        $parameters = [
            "text" => $_text,
            "chat_id" => $_chat_id,
        ];
        if (!empty($_keyboard)) {
            $parameters["reply_markup"] = $_keyboard;
        }
        return self::execute("sendMessage", $parameters);
    }

    /**
     * [send_message description]
     *
     * @param   string  $_text     [$_text description]
     * @param   string  $_chat_id  [$_chat_id description]
     *
     * @return  array              [return description]
     */
    public static function get_user(string $_chat_id)
    {
        $parameters = [
            "chat_id" => $_chat_id,
        ];

        return self::execute("getChat", $parameters);
    }

    /**
     * Undocumented function
     *
     * @param array $_keyboard
     * @param boolean $_resize
     * @param boolean $_one_time
     * @return array
     */
    public static function make_keyboard(array $_keyboard, bool $_resize = false, bool $_one_time = false): array
    {
        return [
            "keyboard" => $_keyboard,
            "resize_keyboard" => $_resize,
            "one_time_keyboard" => $_one_time,
        ];
    }

    /**
     * [get_update description]
     *
     * @param   int    $_offset  [$_offset description]
     *
     * @return  array            [return description]
     */
    public static function get_update(int $_offset = null)
    {
        $parameters = [];
        if (!is_null($_offset)) {
            $parameters["offset"] = $_offset;
        }
        $result = self::execute("getUpdates", $parameters);
        if (!is_array($result) || !isset($result["result"])) {
            return false;
        }
        return $result["result"];
    }

    /**
     * Undocumented function
     *
     * @param string $_chat_id
     * @param string $_from_chat_id
     * @param string $_message_id
     * @return array
     */
    public static function forward(string $_chat_id, string $_from_chat_id, string $_message_id)
    {
        $query = [
            "chat_id" => $_chat_id,
            "from_chat_id" => $_from_chat_id,
            "message_id" => $_message_id,
        ];

        return self::execute("forwardMessage", $query);
    }

    /**
     * @param  string  $_chat_id
     *
     * @return false|string
     */
    public static function get_first_name(string $_chat_id): bool|string
    {
        $user = self::get_user($_chat_id);
        if (!isset($user["result"]["first_name"])) {
            return "";
        }
        return $user["result"]["first_name"] ?? "";
    }

    /**
     * @param  string  $_chat_id
     *
     * @return bool|string
     * @throws \Exception
     */
    public static function get_user_profile_photo(string $_chat_id): bool|string
    {
        $user = self::get_user($_chat_id);
        if (!isset($user["result"]["id"])) {
            return false;
        }
        return self::execute("getUserProfilePhotos", [
            "user_id" => $user["result"]["id"],
            "limit" => 1,
        ])["result"]["photos"][0][0]["file_id"] ?? "";
    }

    /**
     * @param  string  $file_id
     * @param  string  $_chat_id
     * @param  string|null  $caption
     * @param  array  $_keyboard
     *
     * @return bool|array
     * @throws \Exception
     */
    public static function send_photo(
        string $file_id,
        string $_chat_id,
        string $caption = null,
        array $_keyboard = []
    ): bool|array {
        $parameters = [
            "photo" => $file_id,
            "chat_id" => $_chat_id,
            "caption" => $caption,
        ];
        if (!empty($_keyboard)) {
            $parameters["reply_markup"] = $_keyboard;
        }
        return self::execute("sendPhoto", $parameters);
    }
}
