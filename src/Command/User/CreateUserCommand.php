<?php

namespace App\Command\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:user:create', description: 'Create a new user')]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addOption('is-admin', null, InputOption::VALUE_NONE, 'Is admin')
            ->addOption('is-enabled', null, InputOption::VALUE_NONE, 'Is enabled');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');
        $password = $questionHelper->ask($input, $output, new Question('Password: ')->setHidden(true));

        $user = new User();
        $user
            ->setEmail($input->getArgument('email'))
            ->setUsername($input->getArgument('username'))
            ->setPlainPassword($password)
            ->setRoles([$input->getOption('is-admin') ? 'ROLE_ADMIN' : 'ROLE_USER'])
            ->setIsEnabled($input->getOption('is-enabled'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
