<?php

namespace Fregata\Command;

use Fregata\Helper\CommandHelper;
use Fregata\Migration\MigrationRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationShowCommand extends Command
{
    public function __construct(
        private readonly MigrationRegistry $migrationRegistry,
        private readonly CommandHelper $commandHelper
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setName('fregata:migration:show')
            ->setDescription('List all registered migrators sorted for a given migrations.')
            ->setHelp('List migrators of a migration.')
            ->addArgument(
                'migration',
                InputArgument::REQUIRED,
                'The name of the migration.'
            )
            ->addOption(
                'with-tasks',
                't',
                InputOption::VALUE_NONE,
                'Lists the before and after tasks associated with each migration.'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $migrationName = $input->getArgument('migration');
        $migration = $this->migrationRegistry->get($migrationName);

        if (null === $migration) {
            $io->error(sprintf('No migration registered with the name "%s".', $migrationName));
            return 1;
        }

        $migrators = $migration->getMigrators();
        $io->title(sprintf('%s : %d migrators', $migrationName, count($migrators)));

        if ($input->getOption('with-tasks')) {
            $this->commandHelper->printObjectTable($io, $migration->getBeforeTasks(), 'Before Task');
        }

        $this->commandHelper->printObjectTable($io, $migration->getMigrators(), 'Migrator Name');

        if ($input->getOption('with-tasks')) {
            $this->commandHelper->printObjectTable($io, $migration->getAfterTasks(), 'After Task');
        }

        $io->newLine();
        return 0;
    }
}
