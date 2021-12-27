<?php

class TelegraLib
{
    private static string $url;

    /**
     * set url parameter to use
     *
     * @return  void    [return description]
     */
    private static function init(): void
    {
        self::$url = 'https://api.telegram.org/bot' . TOKEN . '/';
    }

    /**
     * [execute description]
     *
     * @param   string  $_method      [$_method description]
     * @param   array   $_parameters  [$_parameters description]
     *
     * @return  array|bool                [return description]
     */
    private static function execute(string $_method, array $_parameters): array|bool
    {
        if (!isset(self::$url)) {
            self::init();
        }

        $url = self::$url . $_method;

        $curl = curl_init($url);
        if (!empty($_parameters)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($_parameters));
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $error = null;
        if (curl_errno($curl)) {
            $error = curl_error($curl);
        }
        curl_close($curl);
        if (!is_null($error)) {
            return false;
        }

        $output = json_decode($result, true);
        if (is_null($output)) {
            return false;
        }
        return  $output;
    }

    /**
     * [send_message description]
     *
     * @param   string  $_text     [$_text description]
     * @param   string  $_chat_id  [$_chat_id description]
     *
     * @return  array              [return description]
     */
    public static function send_message(string $_text, string $_chat_id): array|bool
    {
        $parameters = [
            "text" => $_text,
            "chat_id" => $_chat_id
        ];
        return self::execute('sendMessage', $parameters);
    }

    /**
     * [get_update description]
     *
     * @param   int    $_offset  [$_offset description]
     *
     * @return  array            [return description]
     */
    public static function get_update(int $_offset = null): array|null
    {
        $parameters = [];
        if (!is_null($_offset)) {
            $parameters["offset"] = $_offset;
        }
        $result = self::execute('getUpdates', $parameters);
        if (!is_array($result)) {
            return false;
        }
        return $result["result"];
    }
}
