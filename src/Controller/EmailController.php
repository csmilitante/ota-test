<?php

namespace App\Controller;

use App\Repository\JobListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmailController extends AbstractController
{
    #[Route('/email/view-template', name: 'email_view_template')]
    public function checkEmailTemplate(JobListingRepository $jobListingRepository): Response
    {
        $jobListing = $jobListingRepository->findOneBy(['status' => 'pending']);

        return $this->render('email/new_job_notification.html.twig', [
            'jobListing' => $jobListing,
        ]);
    }
}
