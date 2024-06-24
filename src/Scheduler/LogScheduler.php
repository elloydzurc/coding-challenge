<?php
declare(strict_types=1);

namespace App\Scheduler;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('* * * * *')]
final class LogScheduler
{
    /**
     * @throws \Exception
     */
    public function __invoke(KernelInterface $kernel): void
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'log:read',
            'file' => 'logs.log',
            'lines' => 1,
        ]);

        $application->run($input);
    }
}
