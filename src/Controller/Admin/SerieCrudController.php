<?php

namespace App\Controller\Admin;

use App\Entity\Serie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SerieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Serie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex();
        yield TextField::new('nom');
        // TODO: Mettre en forme la date
        yield NumberField::new('annee');
        // TODO: Empecher l'affichage null
        yield NumberField::new('mois');
        yield TextareaField::new('description');
        yield TextareaField::new('commentaire');

    }
}
