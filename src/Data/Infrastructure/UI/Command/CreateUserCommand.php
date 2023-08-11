<?php

namespace App\Data\Infrastructure\UI\Command;

use App\Event\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Exception\NotValidEmailAddressException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-user',
)]
class CreateUserCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the User')
            ->setDescription('Create an user')
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
        $email = $input->getArgument('email');

        try {
            $this->commandBus->dispatch(new \App\Data\Application\User\CreateUser\CreateUserCommand($email));
        } catch (NotValidEmailAddressException $exception) {
            $this->io->error("An error happened: " . $exception->getMessage());

            return Command::FAILURE;
        } catch (\Exception $exception) {
            $this->io->error("An error happened: " . $exception->getMessage());

            return Command::FAILURE;
        }

        $this->io->success("The user was created");

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> create a product
HELP;
    }
}
