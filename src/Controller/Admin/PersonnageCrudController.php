<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PersonnageCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPaginatorPageSize(200); // nombre d'éléments par page
}
    public static function getEntityFqcn(): string
    {
        return Personnage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex()
            ->setColumns(1);
        yield FormField::addRow();
        yield TextField::new('alias','Nom courant')->setColumns(3);
        yield TextField::new('sexe')
            ->setColumns(1)
            ->formatValue(fn ($value) => $value ?: ' ');
        
        
        yield FormField::addRow();
        yield TextField::new('prenom','Prénom')->setColumns(3);
        yield TextField::new('nom','Nom')->setColumns(3);
         yield ImageField::new('image')
            ->hideOnForm()
            ->setBasePath('/images/personnage');

        yield TextareaField::new('description');

        yield FormField::addRow();
        yield Field::new('imageFile', "Image")
            ->setColumns(2)
            ->onlyOnForms()
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                ]);
        yield TextField::new('image')
            ->setColumns(2)
            ->onlyOnForms()
            ->setDisabled();

    }
}
