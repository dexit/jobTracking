<?php

namespace App\Controller;

use App\Entity\JobApiServices;
use App\Form\JobApiServicesType;
use App\Repository\JobApiServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{

    #[Route('/index', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security, SerializerInterface $serializer,JobApiServicesRepository $jobApiServicesRepository): Response
    {
        $jobApi = new JobApiServices();
        $form = $this->createForm(JobApiServicesType::class, $jobApi);
        $form->handleRequest($request);

        $allJobApiServices = $jobApiServicesRepository->findAll();
        $allJobApiServicesJson = $serializer->serialize($allJobApiServices, 'json', ['groups' => ['api_service']] );

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobApi);
            $entityManager->flush();
        }

        return $this->render('admin/index.html.twig', [
            'formApi' => $form,
            'allJobApiServicesJson'=>$allJobApiServicesJson
        ]);

    }
}
