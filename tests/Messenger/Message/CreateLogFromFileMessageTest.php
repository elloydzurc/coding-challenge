<?php
declare(strict_types=1);

namespace App\Tests\Messenger\Message;

use App\Messenger\Message\CreateLogFromFileMessage;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateLogFromFileMessageTest extends KernelTestCase
{
    public function testSetContentUsingConstructor(): void
    {
        $content = 'Set message content from message class constructor';
        $message = new CreateLogFromFileMessage($content);

        $reflectionClass = new ReflectionClass($message);
        $reflectionProperty = $reflectionClass->getProperty('content');

        $this->assertEquals($content, $reflectionProperty->getValue($message));
    }

    public function testGetContent(): void
    {
        $content = 'Get message content from message class method';
        $message = new CreateLogFromFileMessage($content);

        $this->assertEquals($content, $message->getContent());
    }
}
