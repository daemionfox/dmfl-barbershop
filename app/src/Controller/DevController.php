<?php

namespace App\Controller;

use App\Entity\Barber;
use App\Entity\Cadet;
use App\Entity\Haircut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DevController extends AbstractController
{


    #[Route('/data/init', name: 'app_installdata')]
    public function insertBaseData(EntityManagerInterface $entityManager): RedirectResponse
    {

        $cadets = $entityManager->getRepository(Cadet::class)->findAll();
        $haircuts = $entityManager->getRepository(Haircut::class)->findAll();
        $barbers = $entityManager->getRepository(Barber::class)->findAll();
        $items = array_merge($cadets, $haircuts, $barbers);
        foreach ($items as $item) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        $cadetnames = $this->generateName(100);
        $barbernames = $this->generateName(12);
        $rngs = $this->generateRNG(100);
        foreach ($cadetnames as $cn) {
            $cadet = new Cadet();
            $rng = array_pop($rngs);
            $cadet->setVmibadgeid($rng)->setName($cn);
            $entityManager->persist($cadet);
            $entityManager->flush();
        }

        foreach ($barbernames as $bn) {
            $barber = new Barber();
            $barber->setName($bn);
            $entityManager->persist($barber);
            $entityManager->flush();
        }
        return new RedirectResponse($this->generateUrl('app_index'));
    }

    protected function generateRNG($qty): array
    {
        $rngs = [];

        while (count($rngs) < $qty) {
            $rng = rand(0,999999);
            $rng = str_pad((string)$rng, 6, '0', STR_PAD_BOTH );
            if (!in_array($rng, $rngs)) {
                $rngs[] = $rng;
            }
        }
        return $rngs;
    }

    protected function generateName($qty) {
        $firstnames = [
            "Devyn", "Gerardo", "Cohen", "Finnegan", "Mario", "Aedan", "Mekhi", "Jimmy", "Dangelo", "Rigoberto",
            "Kamron", "Gunner", "Hamza", "Salvatore", "Alex", "Bruno", "Tyshawn", "Milton", "Dashawn", "Corey",
            "Will", "Lucian", "Abraham", "Simeon", "Benjamin", "Jay", "Bo", "Boston", "Brent", "Alonzo",
            "Collin", "Nico", "Ulises", "Cason", "Jesse", "Eden", "Agustin", "Joel", "Jaquan", "Santiago",
            "Drake", "Ian", "Bryant", "Trevin", "Stanley", "Aaron", "Roger", "Jadon", "Finn", "Jovani",
        ];

        $lastnames = [
            "Cortez", "Cox", "Finley", "Romero", "Carlson", "Goodwin", "Randall", "Hernandez", "Boyd", "Odonnell",
            "Soto", "Oneal", "Harrell", "Powell", "Mcguire", "Hebert", "Duncan", "Chung", "Ramirez", "Flowers",
            "Mcconnell", "Mccormick", "Walters", "Ponce", "Williams", "Hendrix", "Stevenson", "Ford", "Duke", "Winters",
            "Fowler", "Figueroa", "Villegas", "Stafford", "Mathews", "Huff", "Hamilton", "Salazar", "Mullen", "Eaton",
            "Mccann", "Schultz", "Carney", "Anderson", "Vincent", "Rowe", "Collins", "Doyle", "Mcbride", "Leonard",
            "Santiago", "Roberts", "Coleman", "Tanner", "Fitzpatrick", "Esparza", "Hale", "Larson", "Dennis",
            "Alvarez", "Noble", "Lambert", "Gill", "Haas", "Smith", "Wells", "Russo", "Brown", "Dickerson", "Robles",
            "Warner", "Rivers", "Wiley", "Guerrero", "Montoya", "Turner", "Archer", "Strickland", "Nash", "Ibarra",
        ];

        $names = [];

        while (count($names) < $qty) {
            $fname = $firstnames[array_rand($firstnames)];
            $lname = $lastnames[array_rand($lastnames)];

            $name = "{$fname} {$lname}";
            if (!in_array($name, $names)) {
                $names[] = $name;
            }

        }
        return $names;



    }

}