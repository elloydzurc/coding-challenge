<?php
declare(strict_types=1);

namespace App\Messenger\Message;

class CreateLogFromFileMessage
{
    public function __construct(private readonly string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
