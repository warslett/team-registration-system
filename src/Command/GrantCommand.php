<?php

namespace App\Command;

use App\Exception\RoleAlreadyGrantedException;
use App\Exception\UserNotFoundException;
use App\Service\GrantService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GrantCommand extends Command
{

    /**
     * @var GrantService
     */
    private $grantService;

    public function __construct(GrantService $grantService)
    {
        parent::__construct(null);
        $this->grantService = $grantService;
    }


    protected function configure()
    {
        $this
            ->setName('app:user:grant')
            ->setDescription('Grants a role to a user')
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('role', InputArgument::REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->grantService->grant(
                $input->getArgument('email'),
                $input->getArgument('role')
            );
        } catch (UserNotFoundException $exception) {
            $output->writeln($exception->getMessage());
            return 1;
        } catch (RoleAlreadyGrantedException $exception) {
            $output->writeln($exception->getMessage());
            return 1;
        }

        return 0;
    }
}
