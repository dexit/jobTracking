<?php

namespace App\Controller;

use App\Entity\JobApiServices;
use App\Form\JobApiServicesType;
use App\Repository\JobApiServicesRepository;
use App\Service\JobService;
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


    #[Route('/job_service/{id}', name: 'job_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobApiServices $jobApiServices, EntityManagerInterface $entityManager, Security $security): Response
    {


        $form = $this->createForm(JobApiServicesType::class, $jobApiServices);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/job_service/edit.html.twig', [
            'job_service' => $jobApiServices,
            'formApi' => $form,
        ]);
    }

    #[Route('/job_service/{id}/delete', name: 'job_service_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, JobApiServices $jobApiServices, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jobApiServices->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jobApiServices);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
