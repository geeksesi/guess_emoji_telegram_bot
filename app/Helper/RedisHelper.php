<?php

namespace App\Helper;

use App\Model\User;

class RedisHelper
{
    private static \Predis\Client $connection;
    public static function connection()
    {
        if (isset(self::$connection)) {
            return self::$connection;
        }
        return new \Predis\Client($_ENV["REDIS_URL"], ["prefix" => "emojibot-"]);
    }

    public static function user_message_count(User $user, bool $increment = false)
    {
        $redis = self::connection();
        $count_key = "user-message-count-" . $user->chat_id;
        $last_message = "user-last-message-" . $user->chat_id;

        if (!$increment) {
            return $redis->get($count_key) ?? 0;
        }
        $pipe = $redis->pipeline();

        $count_period = time() - 60 * 60 * 10;
        $last_message_time = (int) $redis->get($last_message) ?? time();
        if ($last_message_time < $count_period) {
            $pipe->set($count_key, 0);
        }
        if ($redis->exists($count_key)) {
            $pipe->incr($count_key);
        } else {
            $pipe->set($count_key, 1);
        }
        $pipe->set($last_message, time());

        $pipe->execute();
    }
}
