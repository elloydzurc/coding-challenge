<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\Log\Interface\LogServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'log:read')]
final class LogFileReaderCommand extends Command
{
    public function __construct(private readonly LogServiceInterface $logService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File to read')
            ->addArgument('storage', InputArgument::OPTIONAL, 'Local Storage or S3. Default: Local');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $storage = $input->getArgument('storage');
        $file = $input->getArgument('file');

        try {
            $this->logService->populateLogsFromFileStream($file, $storage);
        } catch (\Throwable $exception) {
            $output->writeln(\sprintf('<error>%s</error>', $exception->getMessage()));
        }

        return Command::SUCCESS;
    }
}
