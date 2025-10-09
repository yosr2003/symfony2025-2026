<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $student = new Student();
        $student->setName("Yosr");

        $club = new Club();
        $club->setNameClub("Coding Club");

        // Associe le club à l'étudiant
        $student->addClub($club);

        // Persiste les deux entités
        $manager->persist($club);
        $manager->persist($student);
        $manager->flush();
    }
}
