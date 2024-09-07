<?php

namespace App\Message;

class SendNewsletterMessage
{
    private $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
