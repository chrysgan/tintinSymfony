<?php

namespace App\Controller\Admin;

use App\Entity\Editeur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EditeurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Editeur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('idediteur')
            ->setDisabled()
            ->hideWhenCreating();
        yield TextField::new('nom');
        yield TextEditorField::new('description');
        // TODO : Revoir pour supprimer l'ancienne image en cas de mise à jour de l'image de l'editeur ou de suppression de l'editeur
        yield ImageField::new('image')
            ->setBasePath('images/editors')
            ->setUploadDir('public/images/editors')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
        yield IntegerField::new('anneeCreation');
        yield IntegerField::new('anneeFermeture');
        yield TextareaField::new('commentaire');
        yield AssociationField::new('idpays','Pays');
    }
}
