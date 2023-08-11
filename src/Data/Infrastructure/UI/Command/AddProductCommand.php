<?php

namespace App\Data\Infrastructure\UI\Command;

use App\Event\Domain\Bus\Command\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-product',
)]
class CreateProductCommand extends Command
{
    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the Product')
            ->setDescription('Create a product')
            ->setHelp($this->getCommandHelp());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $this->commandBus->dispatch(new \App\Data\Application\Product\CreateProduct\CreateProductCommand($name));

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> create a product
HELP;
    }
}
