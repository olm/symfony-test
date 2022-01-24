<?php


namespace App\Command;


use App\Entity\Product;
use App\Service\MgclClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends Command
{
    protected static $defaultName = 'app:create-product';
    private EntityManagerInterface $entityManager;
    private MgclClient $dateRetriever;

    public function __construct(EntityManagerInterface $entityManager, MgclClient $dateRetriever, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->dateRetriever = $dateRetriever;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = $this->dateRetriever->getDateTime();
        if ($date === null) {
            $output->writeln('Api returned invalid date');
            return Command::FAILURE;
        }
        $product = new Product($date, null);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}