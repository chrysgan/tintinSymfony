<?php

namespace App\Controller\Admin;

use App\Entity\Serie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SerieCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPaginatorPageSize(200); // nombre d'éléments par page
}
public static function getEntityFqcn(): string
    {
        return Serie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addRow();
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex()
            ->setColumns(1);
        yield IntegerField::new('annee')
            ->formatValue(fn ($value) => $value ?: ' ')
            ->setColumns(1);
        yield IntegerField::new('mois')
            ->formatValue(fn ($value) => $value ?: ' ')
            ->setColumns(1);
        
        yield FormField::addRow();
        yield TextField::new('nom');
        yield TextareaField::new('description');
        yield TextareaField::new('commentaire')
            ->hideOnIndex();

    }
}
