<?php
declare(strict_types=1);

namespace App\Messenger\Message;

final class CreateLogFromFileMessage
{
    public function __construct(private string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
