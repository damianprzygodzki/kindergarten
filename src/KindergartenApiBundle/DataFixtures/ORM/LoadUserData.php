<?php

namespace KindergartenApiBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KindergartenApiBundle\Entity\Classroom;
use KindergartenApiBundle\Entity\Group;
use KindergartenApiBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * Set service container for fixture
     *
     * @param ContainerInterface $container Container for services
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $loads = array(
            $group = new Group('Teacher'),
            $group2 = new Group('Parent')
        );

        foreach ($loads as $load) {
            $manager->persist($load);
        }

        $teachersData = array(
            array('asd@asd.as', 'asd', 'Jan Kowalski', 'A'),
            array('asd1@asd.as', 'asd1', 'Andrzej Nowak', 'B'),
            array('asd2@asd.as', 'asd2', 'Jan Poniedziałek', 'C')
        );

        /* @var $um \FOS\UserBundle\Doctrine\UserManager */
        $um = $this->container->get('fos_user.user_manager');

        foreach ($teachersData as $teacher) {
            $newTeacher = $um->createUser();

            $newTeacher
                ->setEmail($teacher[0])
                ->setUsername($teacher[1]);
            $um->updateCanonicalFields($newTeacher);

            $newTeacher->setPlainPassword($teacher[1]);
            $um->updatePassword($newTeacher);

            $newTeacher->setGroup(
                $manager->getRepository('KindergartenApiBundle:Group')->findOneBy(array('name' => 'Teacher'))
            );

            $newTeacher->setFullname($teacher[2]);

            $manager->persist($newTeacher);
            $manager->flush();
        }



        foreach ($teachersData as $t) {
            $classroom = new Classroom();
            $classroom->setName($t[3]);
            $classroom->setTeacher($um->findUserByUsername($t[1]));
            $manager->persist($classroom);
            $manager->flush();
        }


    }
}