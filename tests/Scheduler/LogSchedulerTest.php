<?php
declare(strict_types=1);

namespace App\Tests\Scheduler;

use App\Scheduler\LogScheduler;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;

class LogSchedulerTest extends KernelTestCase
{
    /**
     * @throws \Exception
     */
    public function testInvoke(): void
    {
        $kernel = self::bootKernel();

        $application = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$kernel])
            ->onlyMethods(['run'])
            ->getMock();

        $application->setAutoExit(false);
        $application->method('run')
            ->with($this->callback(function (ArrayInput $input) {
                $this->assertEquals('log:read', $input->getParameterOption('command'));
                $this->assertEquals('logs.log', $input->getParameterOption('file'));
                $this->assertEquals(10, $input->getParameterOption('lines'));
                return true;
            }))
            ->willReturn(0);

        $logScheduler = new LogScheduler($kernel);
        $this->assertEquals(0, $logScheduler->__invoke());
    }
}
