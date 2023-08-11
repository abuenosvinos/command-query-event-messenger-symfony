<?php

namespace App\Finance\Infrastructure\UI\Command;

use App\Event\Domain\Bus\Command\CommandBus;
use App\Finance\Application\Product\BuyProduct\BuyProductCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'finance:purchase-product',
)]
class PurchaseProductCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'Product Code')
            ->addArgument('quantity', InputArgument::REQUIRED, 'Quantity to buy')
            ->setDescription('Purchase of products')
            ->setHelp($this->getCommandHelp());
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $code = $input->getArgument('code');
        $quantity = $input->getArgument('quantity');

        $this->commandBus->dispatch(
            new BuyProductCommand($code, $quantity)
        );

        $this->io->success('The product has been bought');

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> buy a quantity of a product
HELP;
    }
}
