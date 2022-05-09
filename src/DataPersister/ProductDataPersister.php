<?php

namespace App\DataPersister;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;
    private $_slugger;

    public function __construct(EntityManagerInterface $enityManager, SluggerInterface $slugger)
    {
        $this->_entityManager = $enityManager;
        $this->_slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
       return $data instanceof Product;
    }

    /**
     * @param Product $data
     */
    public function persist($data, array $context = [])
    {
        $data->setSlug($this->_slugger->slug(mb_strtolower($data->getName())) . '-' . uniqid());
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}