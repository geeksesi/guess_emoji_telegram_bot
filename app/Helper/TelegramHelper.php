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
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:application/json"]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $error = null;
        if (curl_errno($curl)) {
            $error = curl_error($curl);
        }
        curl_close($curl);
        if (!is_null($error)) {
            throw new \Exception("CURL :" . curl_error($curl));
        }

        $output = json_decode($result, true);
        if (is_null($output)) {
            throw new \Exception("CURL : EMPTY RESPONSE");
        }
        if (!isset($output["ok"]) || !$output["ok"]) {
            throw new \Exception("TG : " . json_encode($output["description"]));
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
}
