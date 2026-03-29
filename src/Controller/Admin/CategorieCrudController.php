<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategorieCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(50); // nombre d'éléments par page
    }    
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setDisabled()
            ->hideOnIndex();
        yield TextField::new('code');
        yield TextField::new('nom');
        yield ImageField::new('image')
            ->hideOnForm()
            ->setBasePath('/images/categorie');
        yield TextField::new('image')
            ->hideOnIndex()
            ->setDisabled();
        yield Field::new('imageFile', 'Fichier')
            ->setFormType(VichImageType::class)
            ->onlyOnForms()
            ->setFormTypeOptions([
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true]
        );
    }
}
