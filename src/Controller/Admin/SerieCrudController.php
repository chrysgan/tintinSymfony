<?php

namespace App\Controller\Admin;

use App\Entity\Serie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
        yield IdField::new('idserie')
            ->setDisabled()
            ->hideWhenCreating();
        yield TextField::new('nom');
        yield IntegerField::new('annee');
        yield IntegerField::new('mois');
        yield TextEditorField::new('description');
        yield TextareaField::new('commentaire');
    }
}
