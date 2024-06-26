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
            ->addArgument('storage', InputArgument::OPTIONAL, 'Local Storage or S3. Default: Local')
            ->addArgument('lines', InputArgument::OPTIONAL, 'Number of lines to read on file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->logService->populateLogsFromFileStream($input->getArguments());
        } catch (\Throwable $exception) {
            $output->writeln(
                \sprintf('<error>%s</error>', $exception->getMessage())
            );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
