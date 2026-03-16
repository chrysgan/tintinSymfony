<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->hideWhenCreating();
        yield TextField::new('prenom');
        yield TextField::new('nom');
        yield TextField::new('alias');
        yield TextField::new('sexe');
        yield TextEditorField::new('description');
        // TODO : Revoir pour supprimer l'ancienne image en cas de mise à jour de l'image de l'editeur ou de suppression de l'editeur
        yield ImageField::new('image')
            ->setBasePath('images/personnage')
            ->setUploadDir('public/images/personnage')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
    }
}
