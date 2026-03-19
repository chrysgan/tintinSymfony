<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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
            ->hideOnIndex()
            ->hideWhenCreating();
        yield BooleanField::new('actif');
        yield TextField::new('nom');
        yield NumberField::new('taille')
            ->hideOnIndex();
        yield IntegerField::new('poids', 'Poids (grammes)')
            ->hideOnIndex();
        yield MoneyField::new('prix')
            ->setCurrency('EUR')
            ->hideOnIndex();
        yield AssociationField::new('idcategorie', 'Catégorie');
        yield TextField::new('reference')
            ->hideOnIndex();
        yield IntegerField::new('annee');
        yield IntegerField::new('mois');
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield DateTimeField::new('createdAt')
            ->setDisabled();
        yield DateTimeField::new('updatedAt')
            ->hideOnIndex()
            ->setDisabled();
        // TODO : afficher les champs dans la liste
        yield AssociationField::new('personnages')
            ->hideOnIndex()
            ->setFormTypeOption('by_reference', false)
            ->autocomplete();
        yield AssociationField::new('idserie', 'Série');
        yield AssociationField::new('idediteur', 'Editeur');
        yield TextField::new('rangement');
        yield MoneyField::new('montantAchat')
            ->setCurrency('EUR')
            ->hideOnIndex();
        yield BooleanField::new('estPossede');
        yield TextareaField::new('descriptionExemplaire');


    }

}
