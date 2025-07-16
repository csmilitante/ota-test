<?php

namespace App\Controller;

use App\Entity\JobListing;
use App\Entity\User;
use App\Repository\JobListingRepository;
use App\Service\ExternalJobListingService;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class JobController extends AbstractController
{
    private JobListingRepository $jobListingRepository;
    private NotificationService $notificationService;

    public function __construct(JobListingRepository $jobListingRepository, NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->jobListingRepository = $jobListingRepository;
    }

    #[Route('job/create', name: 'job_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $jobListing = $this->jobListingRepository->create($request->toArray());

        // send email if needed
        $this->notificationService->sendToModerator($jobListing);

        return $this->render('job/create.html.twig', [
            'id' => $jobListing->getId(),
            'title' => $jobListing->getTitle(),
            'owner' => $jobListing->getOwner(),
            'description' => $jobListing->getDescription(),
            'createdAt' => $jobListing->getCreatedAt(),
        ]);
    }

    #[Route('job/list/internal', name: 'job_listing_list')]
    public function list(): Response
    {
        $jobListings = $this->jobListingRepository->findAll();

        return $this->render('job/list.html.twig', [
            'jobListings' => $jobListings,
        ]);
    }

    #[Route('job/list/external', name: 'job_external_list')]
    public function showExternalJobListings(ExternalJobListingService $externalJobListingService): Response
    {
        $externalJobListings = $externalJobListingService->getDataFromExternal();

        return $this->render('job/list_external.html.twig', [
            'externalJobListings' => $externalJobListings,
        ]);
    }

    #[Route('job/list/all', name: 'job_all_list')]
    public function showAllJobListings(ExternalJobListingService $externalJobListingService): Response
    {
        $externalJobListings = $externalJobListingService->getDataFromExternal();
        $jobListings = $this->jobListingRepository->findAll();

        return $this->render('job/list_all.html.twig', [
            'externalJobListings' => $externalJobListings,
            'jobListings' => $jobListings
        ]);
    }

    #[Route('job/status/update/pending/{id}', name: 'job_status_set_pending', methods: ['GET', 'POST'])]
    public function setJobListingStatusToPending(Request $request): Response
    {
        $id = $request->get('id');
        $jobListing = $this->jobListingRepository->updateStatus($id);

        return $this->json($jobListing);
    }

    #[Route('job/status/update/approve/{id}', name: 'job_status_set_approve', methods: ['GET', 'POST'])]
    public function setJobListingStatusToApprove(Request $request): Response
    {
        $id = $request->get('id');
        $jobListing = $this->jobListingRepository->updateStatus($id, 'approved');

        return $this->json($jobListing);
    }

    #[Route('job/status/update/spam/{id}', name: 'job_status_set_spam', methods: ['GET', 'POST'])]
    public function setJobListingStatusToSpam(Request $request): Response
    {
        $id = $request->get('id');
        $jobListing = $this->jobListingRepository->updateStatus($id, 'spam');

        return $this->json($jobListing);
    }
}
