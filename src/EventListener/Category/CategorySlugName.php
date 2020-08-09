<?php
namespace App\EventListener\Category;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugName
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function preUpdate(Category $category, LifecycleEventArgs $event)
    {
        $category->setSlug(
            $this->slugger->slug($category->getName())
        );
    }

    public function prePersist(Category $category, LifecycleEventArgs $event)
    {
        $category->setSlug(
            $this->slugger->slug($category->getName())
        );
    }
}