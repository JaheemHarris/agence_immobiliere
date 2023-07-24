<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Property;

class PropertyController extends AbstractController
{
    #[Route('/property', name: 'app_property')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $propertyRepository = $doctrine->getRepository(Property::class);
        $propertyList = $propertyRepository->findAll();
        return $this->render('property/index.html.twig', [
            'propertyList' => $propertyList,
        ]);
    }

    #[Route('/property/list', name: 'app_property_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $propertyRepository = $doctrine->getRepository(Property::class);
        $propertyList = $propertyRepository->findAll();
        return $this->render('property/property-list.html.twig', [
            'propertyList' => $propertyList,
        ]);
    }

    #[Route('/property/delete/{id}', name: 'app_property_delete')]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $property = $entityManager->getRepository(Property::class)->find($id);

        if (!$property) {
            throw $this->createNotFoundException('Property not found.');
        }

        $entityManager->remove($property);
        $entityManager->flush();

        return $this->redirectToRoute('app_property_list');
    }

    #[Route('/property/edit/{id}', name: 'app_property_edit')]
    public function edit(int $id, ManagerRegistry $doctrine): Response
    {
        // $property = new Property();
        // $property->setId($id);
        // $entityManager = $doctrine->getManager();
        // $entityManager->remove($property);
        // $entityManager->flush();
        // return $this->redirectToRoute('app_property_list');
    }

    #[Route('/property/add', name: 'app_property_add', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $location = $request->request->get('location');
            $surface = $request->request->get('surface');
            $price = $request->request->get('price');
            $rooms = $request->request->get('rooms');

            $entityManager = $doctrine->getManager();
            
            $property = new Property();
            $property->setTitle($title);
            $property->setDescription($description);
            $property->setLocation($location);
            $property->setSurface($surface);
            $property->setPrice($price);
            $property->setRooms($rooms);

            $entityManager->persist($property);
            $entityManager->flush();

            return $this->redirectToRoute('app_property_add');
        }
        return $this->render('property/property-new.html.twig');
    }
}
