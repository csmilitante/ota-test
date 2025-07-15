<?php

namespace App\Controller;

use App\Entity\JobListing;
use App\Repository\JobListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    #[Route('job/create', name: 'job_create')]
    public function create(JobListingRepository $jobListingRepository): Response
    {
        $jobListing = $jobListingRepository->create();

        return $this->render('job/create.html.twig', [
            'id' => $jobListing->getId(),
            'title' => $jobListing->getTitle(),
            'ownerId' => $jobListing->getOwnerId(),
            'description' => $jobListing->getDescription(),
            'createdAt' => $jobListing->getCreatedAt(),
        ]);
    }

    #[Route('job/list', name: 'job_listing_list')]
    public function list(JobListingRepository $jobListingRepository): Response
    {
        $jobListings = $jobListingRepository->findAll();

        return $this->render('job/list.html.twig', [
            'jobListings' => $jobListings,
        ]);
    }
}
