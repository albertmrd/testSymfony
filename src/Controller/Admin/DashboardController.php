<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Entity\Comment;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('main');
        }

        // redirect to some CRUD controller
         $routeBuilder = $this->get(AdminUrlGenerator::class);

         return $this->redirect($routeBuilder->setController(MovieCrudController::class)->generateUrl());

        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TestSymfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        yield MenuItem::section('Entidades');
        yield MenuItem::linkToCrud('Movies', 'fa fa-film', Movie::class);
        yield MenuItem::linkToCrud('Comments', 'fa fa-comments', Comment::class);
        
        
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
