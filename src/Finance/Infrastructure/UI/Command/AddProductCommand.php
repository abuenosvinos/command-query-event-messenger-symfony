<?php

namespace App\Finance\Infrastructure\UI\Command;

use App\Event\Domain\Bus\Command\CommandBus;
use App\Finance\Application\Product\CreateProduct\CreateProductCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'finance:add-product',
)]
class AddProductCommand extends Command
{
    private ?SymfonyStyle $io = null;

    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'Product Code')
            ->addArgument('name', InputArgument::REQUIRED, 'Product Name')
            ->addArgument('purchasePrice', InputArgument::REQUIRED, 'Product Purchase Price')
            ->addArgument('salePrice', InputArgument::REQUIRED, 'Product Sale Price')
            ->setDescription('Add a product to the catalog')
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
        $name = $input->getArgument('name');
        /** @var int $purchasePrice */
        $purchasePrice = $input->getArgument('purchasePrice');
        /** @var int $salePrice */
        $salePrice = $input->getArgument('salePrice');

        if ($purchasePrice >= $salePrice) {
            $this->io?->error('The purchase price can\'t be greater than the sale price');
            return Command::FAILURE;
        }

        $this->commandBus->dispatch(
            new CreateProductCommand($code, $name, $purchasePrice, $salePrice)
        );

        $this->io?->success('The product has been added');

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> add a product to the catalog
HELP;
    }
}
