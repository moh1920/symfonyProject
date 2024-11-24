<?php
namespace App\Command;

use App\Repository\MenuRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdateMenuAvailabilityCommand  extends Command {
    private $entityManager ;
    private $menuRepository ;
    protected static $defaultName = 'app:update-menu-availability';

    public function __construct(EntityManagerInterface $entityManager, MenuRepository $menuRepository) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->menuRepository = $menuRepository;
    
    }
    protected function configure(): void
    {
        $this
            ->setDescription('Met à jour la disponibilité des menus expirés.')
            ->setHelp('Cette commande met à jour la disponibilité des menus en fonction de leur date d\'expiration.');
    }
    protected function execute(InputInterface $input, OutputInterface $output):int{
        $menus = $this->menuRepository->findAll();
        foreach ($menus as $menu) {
            $currentDate = new \DateTime ;
            if($menu->getMenuDateExpiration() < $currentDate){
                    $menu->setMenuDisponible(false);
                    $this->entityManager->persist($menu);

            }  
        }
        $this->entityManager->flush();
        $output->writeln('Les menus ont été mis à jour.');
        return Command::SUCCESS;

    }
}