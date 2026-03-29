<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use App\Form\ObjetImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPaginatorPageSize(20); // nombre d'éléments par page
}

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Principal');
        // yield FormField::addFieldset('test')->renderCollapsed();
        yield BooleanField::new('actif')->setColumns(1);
        yield IdField::new('id')
            ->setDisabled()
            ->setLabel(false)
            ->setColumns(1)
            ->hideOnIndex();
        yield FormField::addRow();
        yield DateTimeField::new('createdAt')
            ->setColumns(2)
            ->setDisabled()
            ->hideOnIndex();
        yield DateTimeField::new('updatedAt')
            ->setColumns(2)
            ->setDisabled()
            ->hideOnIndex();
        
        yield FormField::addRow();
        yield TextField::new('nom')->setColumns(4);
        yield TextField::new('reference')
            ->setColumns(3)
            ->formatValue(fn ($value) => $value ?: '-');
        yield FormField::addRow();
        yield AssociationField::new('categorie')
            ->setColumns(3);
        yield FormField::addRow();
        yield AssociationField::new('editeur')
            ->setColumns(4);
        yield AssociationField::new('serie')
            ->setColumns(4)
            ->formatValue(fn ($value) => $value ?: '-');
        yield IntegerField::new('annee')
            ->setColumns(2)
            ->formatValue(fn ($value) => $value ?: ' ');
        yield IntegerField::new('mois')
            ->setColumns(2)
            ->formatValue(fn ($value) => $value ?: ' ');
        yield MoneyField::new('prix')
            ->setColumns(2)
            ->setCurrency('EUR')
            ->formatValue(fn ($value) => $value ? : '-');
            // -2 : Ni vendu ni offert
            // -1 : Inconnu
            //  0 : Offert
        yield NumberField::new('taille')
            ->setColumns(2)
            ->hideOnIndex();
        yield NumberField::new('poids')
            ->setColumns(2)
            ->hideOnIndex();
        yield FormField::addRow();
        yield AssociationField::new('personnages')
            ->hideOnIndex();
        yield TextareaField::new('description')
            ->hideOnIndex();
        
        yield FormField::addTab('Images');
        yield CollectionField::new('objetImages')
            ->setEntryType(ObjetImageType::class)
            ->allowAdd()
            ->allowDelete()
            ->setFormTypeOption('by_reference', false)
            ->onlyOnForms()
            ->renderExpanded();
        
        yield FormField::addTab('Possession');
        yield BooleanField::new('estPossede');
        yield TextField::new('rangement')
            ->hideOnIndex();
        yield MoneyField::new('montantAchat')
            ->hideOnIndex()
            ->setCurrency('EUR');
        yield TextareaField::new('descriptionPossession')
            ->hideOnIndex();
    }
}
