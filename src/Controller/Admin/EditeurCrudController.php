<?php

namespace App\Controller\Admin;

use App\Entity\Editeur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditeurCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(100); // nombre d'éléments par page
    }   
    
    public static function getEntityFqcn(): string
    {
        return Editeur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nom')
            ->setColumns(4);
        yield AssociationField::new('pays','Pays')
            ->setColumns(2);
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex()
            ->setColumns(1);

        yield FormField::addRow();
        yield IntegerField::new('annee_creation','Année de Création')
            ->setColumns(1)
            ->formatValue(fn ($value) => $value ?: ' ');
        yield IntegerField::new('annee_fermeture','Année de Fermeture')
            ->formatValue(fn ($value) => $value ?: ' ')
            ->setColumns(1);
         yield ImageField::new('image')
            ->hideOnForm()
            ->setBasePath('/images/editeur');
            
        yield FormField::addRow();
        yield TextareaField::new('description','Description')
            ->hideOnIndex();
        yield TextareaField::new('commentaire')
            ->hideOnIndex();
    
        yield FormField::addRow();
        yield TextField::new('image')
            ->setColumns(2)
            ->onlyOnForms()
            ->setDisabled();
        yield Field::new('imageFile', "Logo de l'éditeur")
        ->setColumns(2)
            ->onlyOnForms()
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                ]);
        


    }
}
