<?php

namespace App\Service;

use App\Entity\JobListing;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class NotificationService
{
    private LoggerInterface $logger;
    private UserRepository $userRepository;
    private MailerInterface $mailer;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function sendToModerator(JobListing $jobListing): bool
    {
        try {
            $user = $this->userRepository->find($jobListing->getOwner());

            if ($user->getJobListings()->count() === 1) {
                // Get all moderators
                $moderators = $this->userRepository->findBy(['isModerator' => true]);

                $email = (new TemplatedEmail())
                    ->from('notification@ota-test.com');

                foreach ($moderators as $moderator) {
                    $email->addTo($moderator->getEmail());
                }

                $email->subject('New Job Listing: ' . $jobListing->getTitle())
                    ->htmlTemplate('email/new_job_notification.html.twig')
                    ->context([
                        'jobListing' => $jobListing,
                    ]);

                $this->mailer->send($email);

                return true;
            } else {
                $this->logger->notice('Will not send mail');
            }

            return true;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }
    }
}
