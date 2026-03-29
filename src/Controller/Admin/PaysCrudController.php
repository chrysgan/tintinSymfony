<?php

namespace App\Controller\Admin;

use App\Entity\Pays;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaysCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pays::class;
    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(4);

        yield FormField::addRow();
        yield NumberField::new('code')
            ->hideOnIndex()
            ->setColumns(4);

        yield FormField::addRow();
        yield TextField::new('alpha2')->setColumns(4);
        yield TextField::new('alpha3')->setColumns(4);

        yield FormField::addRow();
        yield TextField::new('nomFr')->setColumns(4);
        yield TextField::new('nomEn')->setColumns(4);
    }
}
