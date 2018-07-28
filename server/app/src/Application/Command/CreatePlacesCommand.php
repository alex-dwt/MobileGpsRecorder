<?php

namespace App\Application\Command;

use App\Domain\Place\Place;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class CreatePlacesCommand extends Command
{
    private const PLACES_COUNT = 20;

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
        EntityManagerInterface $em
    ) {
        $this->placeRepository = $placeRepository;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-places')
            ->setDescription('Clear and create fake places.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressBar = new ProgressBar($output, self::PLACES_COUNT);
        $progressBar->start();

        $this
            ->em
            ->createQueryBuilder()
            ->delete(Place::class)
            ->getQuery()
            ->execute();

        $lat = 50.1;
        $lon = 70.1;

        for ($i = 0; $i < self::PLACES_COUNT; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $place = new Place($lat, $lon);
                $place->setCreatedAt(
                    (new \DateTimeImmutable())->setDate(2018, rand(2, 10), rand(2, 25))
                );
                $this->placeRepository->add($place);
                $lat -= 0.3;
                $lon += 1.5;
            }
            $progressBar->advance();
        }

        $this->em->flush();

        $progressBar->finish();
    }
}