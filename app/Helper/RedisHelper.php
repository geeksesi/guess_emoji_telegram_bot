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

        if (!$increment) {
            return $redis->get($count_key) ?? 0;
        }
        $pipe = $redis->pipeline();

        if ($redis->exists($count_key)) {
            $pipe->incr($count_key);
        } else {
            $pipe->set($count_key, 1);
        }
        $pipe->expire($count_key, 60 * 60 * 12);

        $pipe->execute();
    }
}
