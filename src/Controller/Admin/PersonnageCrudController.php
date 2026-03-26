<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonnageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Personnage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex();
        yield TextField::new('prenom');
        yield TextField::new('nom');
        yield TextField::new('alias');
        yield TextField::new('sexe');
        yield ImageField::new('image')
            ->setUploadDir('images/personnage')
            ->setBasePath('images/personnage');
        yield TextareaField::new('description');

    }
}
