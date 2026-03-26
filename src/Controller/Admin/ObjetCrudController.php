<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex();
        yield BooleanField::new('actif');
        yield TextField::new('nom');
        yield AssociationField::new('serie');
        yield AssociationField::new('editeur');
        yield AssociationField::new('categorie');
        yield NumberField::new('taille');
        yield TextareaField::new('description');
        yield TextField::new('reference');
        yield MoneyField::new('prix')
            ->setCurrency('EUR');
        yield IntegerField::new('annee');
        yield IntegerField::new('mois');
        yield DateTimeField::new('createdAt')
            ->setDisabled()
            ->hideOnIndex();
        yield DateTimeField::new('updatedAt')
            ->setDisabled()
            ->hideOnIndex();
        yield NumberField::new('poids');
        yield TextField::new('rangement');
        yield MoneyField::new('montantAchat')
            ->setCurrency('EUR');
        yield BooleanField::new('estPossede');
        yield TextareaField::new('descriptionPossession');


    }

}
