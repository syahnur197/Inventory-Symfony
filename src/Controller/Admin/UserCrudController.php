<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCrudController extends AbstractCrudController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('email'),
            TextField::new('username'),
            TextField::new('plainPassword')
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'empty_data' => '',
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ])
                ->setRequired(false),
            BooleanField::new('isVerified', 'Verified')
                ->onlyOnIndex(),
        ];
    }
   
   /**
     *
     * @param EntityManagerInterface $entityManager
     * @param User $user
     */
    public function updateEntity(EntityManagerInterface $entityManager, $user): void
    {
        if($user instanceof User) {
            $this->setUserPlainPassword($user);
        }

        parent::updateEntity($entityManager, $user);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $user
     */
    public function persistEntity(EntityManagerInterface $entityManager, $user): void
    {
        if($user instanceof User && $user->getPlainPassword() !== null) {
            $this->setUserPlainPassword($user);
        }
        parent::persistEntity($entityManager, $user);
    }

    private function setUserPlainPassword(User $user): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }
    }
}
