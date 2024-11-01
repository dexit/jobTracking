<?php

namespace App\Controller;

use App\Model\ApiJob;
use App\Repository\UserRepository;
use App\Service\ApiService;
use App\Service\MistralAiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class ApiController extends AbstractController
{

    #[Route('/job_alert', name: 'app_job_alert')]
    public function jobAlert(Security $security, UserRepository $userRepository, CacheInterface $cache,  MistralAiService $mistralAiService): Response
    {
        $user = $security->getUser();

        // Récupération des paramètres de l'utilisateur
        $user = $userRepository->findOneBy(['email' => $user->getUserIdentifier()]);
        $userjobApiSettings = $user->getJobSearchSettings();

        $apiService = new ApiService($userjobApiSettings, $mistralAiService, $cache);
        if (empty($userjobApiSettings) || empty($userjobApiSettings->getJobApiServices())) {
            $this->addFlash("info", "Vous n'avez pas encore créé de profil de recherche.");
            return $this->redirectToRoute('app_user_show');
        }


        $userApiJobResults = $apiService->getUserApiJobResults();

        return $this->render('job/job_alert.html.twig', [
            'jobs' => $userApiJobResults


        ]);
    }
}
