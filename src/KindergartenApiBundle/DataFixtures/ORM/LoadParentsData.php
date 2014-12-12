<?php

namespace KindergartenApiBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KindergartenApiBundle\Entity\Classroom;
use KindergartenApiBundle\Entity\Group;
use KindergartenApiBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadParentsData implements FixtureInterface, ContainerAwareInterface
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

        $parentsData = array(
            array('qwe@asd.as', 'qwe', 'Jan Kowalski'),
            array('qwe1@asd.as', 'qwe1', 'Andrzej Nowak'),
            array('qwe2@asd.as', 'qwe2', 'Jan Poniedziałek')
        );

        /* @var $um \FOS\UserBundle\Doctrine\UserManager */
        $um = $this->container->get('fos_user.user_manager');

        foreach ($parentsData as $parent) {
            $newParent = $um->createUser();

            $newParent
                ->setEmail($parent[0])
                ->setUsername($parent[1])
                ->setRoles(array("ROLE_USER"))
                ->setEnabled(true);
            $um->updateCanonicalFields($newParent);

            $newParent->setPlainPassword($parent[1]);
            $um->updatePassword($newParent);

            $newParent->setFullname($parent[2]);

            $manager->persist($newParent);
            $manager->flush();
        }


    }
}