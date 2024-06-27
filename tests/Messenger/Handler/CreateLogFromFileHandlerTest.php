<?php
declare(strict_types=1);

namespace App\Tests\Messenger\Handler;

use App\Entity\Log;
use App\Messenger\Handler\CreateLogFromFileHandler;
use App\Messenger\Message\CreateLogFromFileMessage;
use App\Repository\Interface\LogRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateLogFromFileHandlerTest extends KernelTestCase
{
    private LogRepositoryInterface $logRepository;

    private CreateLogFromFileHandler $handler;

    protected function setUp(): void
    {
        $this->logRepository = $this->createMock(LogRepositoryInterface::class);
        $this->handler = new CreateLogFromFileHandler($this->logRepository);
    }

    public function testInvokeLogAlreadyExists(): void
    {
        $content = 'SERVICE-NAME - - [01/Jan/2023:12:34:56 +0000] "GET /service-url HTTP/1.1" 200';
        $hash = \md5($content);

        $message = $this->createMock(CreateLogFromFileMessage::class);
        $message->method('getContent')
            ->willReturn($content);

        $this->logRepository->method('findByHash')
            ->with($hash)
            ->willReturn(new Log());
        $this->logRepository->expects($this->never())
            ->method('createFromEntity');

        $this->handler->__invoke($message);
    }

    public function testInvokeLogDoesNotExist(): void
    {
        $content = 'SERVICE-NAME - - [01/Jan/2023:12:34:56 +0000] "GET /service-url HTTP/1.1" 200';
        $hash = \md5($content);

        $message = $this->createMock(CreateLogFromFileMessage::class);
        $message->method('getContent')
            ->willReturn($content);

        $this->logRepository->method('findByHash')
            ->with($hash)
            ->willReturn(null);
        $this->logRepository->expects($this->once())
            ->method('createFromEntity')
            ->with($this->isInstanceOf(Log::class));

        $this->handler->__invoke($message);
    }
}
