<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('Admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TintinSymfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(ObjetCrudController::class, 'Objet', 'fa-duotone fa-solid fa-cube');
        yield MenuItem::linkTo(EditeurCrudController::class, 'Editeur', 'fa-duotone fa-solid fa-industry');
        yield MenuItem::linkTo(SerieCrudController::class, 'Série', 'fa-duotone fa-solid fa-layer-group');
        yield MenuItem::linkTo(CategorieCrudController::class, 'Catégorie', 'fa-duotone fa-solid fa-table-list');
        yield MenuItem::linkTo(PersonnageCrudController::class, 'Personnage', 'fa-duotone fa-solid fa-people-group');
        yield MenuItem::linkTo(PaysCrudController::class, 'Pays', 'fa-duotone fa-solid fa-earth-europe');
    }
}
