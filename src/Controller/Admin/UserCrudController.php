<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
    private $security;
    private $password;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
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
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->setFormTypeOption('empty_data', '')
                ->setRequired(false),
            BooleanField::new('verifiedAt')
                ->onlyOnIndex(),
        ];
    }
   
   /**
     *
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {
            $clearPassword = trim($this->get('request_stack')->getCurrentRequest()->request->all('User')['password']);

            // if user password not change save the old one
            if (isset($clearPassword) === true && $clearPassword === '') {
                $entityInstance->setPassword($entityInstance->getPassword());
                dump("HELLO");
            } else {
                $encodedPassword = $this->passwordEncoder->encodePassword($entityInstance, $clearPassword);
                $entityInstance->setPassword($encodedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
