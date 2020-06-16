<?php

/**
 * Michels
 *
 * @filesource
 * @copyright 2020 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Michels\Listener;

use Jobs\Entity\JobSnapshot;
use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class AutoJobActivation
{
    private $snapshotRepository;
    private $jobRepository;
    private $response;
    /**
     * @var \Core\EventManager\EventManager
     */
    protected $jobEvents;

    public function __construct($jobRepository, $snapshotRepository, $response, $jobEvents)
    {
        $this->snapshotRepository = $snapshotRepository;
        $this->jobRepository = $jobRepository;
        $this->response = $response;
        $this->jobEvents = $jobEvents;
    }

    public function activateCreatedJob(JobEvent $event)
    {
        $job = $event->getJobEntity();
        $company = $job->getOrganization();
        if (!$company) {
            return;
        }

        $owner = $company->getUser();
        if (!$owner) {
            return;
        }

        if ($job instanceof JobSnapshot) {
            $job->getSnapshotMeta()->setStatus(JobSnapshotStatus::ACCEPTED);
            $job = $this->snapshotRepository->merge($job);
            $this->snapshotRepository->store($job);
            $job->setDateModified();
        } else {
            $job->setDatePublishStart();
        }
        $job->changeStatus(Status::ACTIVE, sprintf(/*@translate*/ "Job opening was activated automatically "));
        $this->jobRepository->store($job);


        /** @var \Jobs\Listener\Events\JobEvent $jobEvent */
        $jobEvent = $this->jobEvents->getEvent();
        $jobEvent->setJobEntity($job);
        $jobEvent->addPortal('XingVendorApi');
        $jobEvent->setTarget($this);
        $jobEvent->setName(JobEvent::EVENT_JOB_ACCEPTED);
        $this->jobEvents->trigger($jobEvent);

        $this->response->getHeaders()->addHeaderLine('Location', '/de/jobs');
        $this->response->setStatusCode(302);
    }

    public function activateChangedJob(JobEvent $event)
    {
        /** @var \Jobs\Entity\JobSnapshot $snapshot */
        $snapshot = $event->getJobEntity();

        if (Status::ACTIVE != $event->getParam('statusWas') || !$snapshot->getStatus()->is(Status::WAITING_FOR_APPROVAL)) {
            return;
        }

        /* @var \Jobs\Entity\Job $entity */
        $snapshot->getSnapshotMeta()->setStatus(JobSnapshotStatus::ACCEPTED);
        $entity = $this->snapshotRepository->merge($snapshot);
        $this->snapshotRepository->store($snapshot);
        $entity->setDateModified();
        $entity->changeStatus(Status::ACTIVE, 'Auto approved.');

        $this->jobRepository->store($entity);

        $this->response->getHeaders()->addHeaderLine('Location', '/de/jobs');
        $this->response->setStatusCode(302);
    }
}
