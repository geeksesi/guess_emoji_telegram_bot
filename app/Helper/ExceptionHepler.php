<?php

namespace App\Helper;

use Throwable;

class ExceptionHepler
{
    private Throwable $err;

    public function __construct(Throwable $err)
    {
        $this->err = $err;
    }

    public function __invoke(bool $dump = true)
    {
        if ($dump) {
            var_dump($this->err);
        }
        TelegramHelper::send_message("WE HAVE AN ERROR : " . $this->err->getMessage(), $_ENV["ADMIN"]);
    }
}
