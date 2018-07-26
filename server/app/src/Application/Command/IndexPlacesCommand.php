<?php

namespace App\Application\Command;

use App\Domain\Place\Place;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class IndexPlacesCommand extends Command
{
    /**
     * @var DoctrinePlaceRepository
     */
    private $placeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        DoctrinePlaceRepository $placeRepository,
        EntityManagerInterface $em//,
//        $placeElasticIndex
    ) {
        $this->placeRepository = $placeRepository;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:index-places')
            ->setDescription('Fill elasticsearch index by places.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressBar = new ProgressBar(
            $output,
            $this->placeRepository->getCount()
        );
        $progressBar->start();

        foreach ($this
                    ->em
                    ->createQueryBuilder()
                    ->select('p')
                    ->from(Place::class, 'p')
                    ->getQuery()
                    ->iterate() as [$place]
        ) {


            $this->em->detach($place);

            $progressBar->advance();
        }

        $progressBar->finish();
    }
}