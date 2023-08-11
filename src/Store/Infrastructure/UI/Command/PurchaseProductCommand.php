<?php

namespace App\Store\Infrastructure\UI\Command;

use App\Event\Domain\Bus\Command\CommandBus;
use App\Event\Domain\Bus\Event\EventBus;
use App\Store\Application\Product\SaleProduct\SaleProductCommand;
use App\Store\Domain\Event\Product\ProductNeededInStore;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'store:purchase-product',
)]
class PurchaseProductCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly EventBus $eventBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'Product Code')
            ->addArgument('quantity', InputArgument::REQUIRED, 'Quantity to sell')
            ->setDescription('Sell of products')
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

        try {
            $this->commandBus->dispatch(
                new SaleProductCommand($code, $quantity)
            );

            $this->io->success('The purchase was correct');

            return Command::SUCCESS;
        } catch (\DomainException $e) {
            $this->eventBus->notify(
                new ProductNeededInStore(
                    $code,
                    $quantity
                )
            );

            $this->io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> sell a quantity of a product
HELP;
    }
}
