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
        // TODO : Faire la gestion du chargement de l'image + le stockage
        yield TextField::new('image');
        yield IntegerField::new('anneeCreation');
        yield IntegerField::new('anneeFermeture');
        yield TextareaField::new('commentaire');
        yield AssociationField::new('idpays','Pays');
    }
}
