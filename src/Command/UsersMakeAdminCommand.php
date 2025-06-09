<?php

namespace App\Command;

use App\Entity\Users\Admin;
use App\Validator\AdminUniqueEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:users:make-admin',
)]
class UsersMakeAdminCommand extends Command
{
    private array $arguments;

    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->arguments = [
            'email' => [
                'constraint' => [new Email(), new AdminUniqueEmail()],
                'default' => 'admin@example.com',
                'question' => 'Введите Email:'
            ],
            'password' => [
                'constraint' => new Length(min: 8),
                'default' => 'qwertyui',
                'question' => 'Введите пароль:'
            ]
        ];
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $input->setInteractive(true);
    }

    protected function configure(): void
    {
        foreach (array_keys($this->arguments) as $argument) {
            $this->addArgument($argument, InputArgument::REQUIRED);
        }

        $this
            ->setDescription('Создание пользователя с правами администратора')
            ->setHelp('Добавляет Администратора');
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);


        foreach ($input->getArguments() as $argument=>$value) {
            if ($value || $argument === 'command') {
                continue;
            }

            $input->setArgument($argument, $this->getArgumentValue($argument, $io));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input,$output);

        $user = new Admin();
        $user
            ->setEmail($input->getArgument('email'))
            ->setPassword($this->hasher->hashPassword($user, $input->getArgument('password')));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success('Пользователь успешно создан');
        return Command::SUCCESS;
    }

    private function getArgumentValue(string $argument, SymfonyStyle $io)
    {
        if (!isset($this->arguments[$argument])) {
            throw new \InvalidArgumentException(\sprintf('Аргумент "%s" не поддерживается!.', $argument));
        }

        while (true) {
            $value = $io->ask($this->arguments[$argument]['question'], $this->arguments[$argument]['default']);
            $errors = $this->validator->validate($value, $this->arguments[$argument]['constraint']);
            if ($errors->count()) {
                foreach ($errors as $error) {
                    $io->error($error->getMessage());
                }
                continue;
            }

            return $value;
        }
    }
}
