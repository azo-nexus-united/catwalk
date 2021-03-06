<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;

class PageGateway
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityRepository $repository, EntityManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @return Page[]
     */
    public function fetchForNode(string $nodeId): array
    {
        /*
         * `ORDER BY p.state DESC` sorts scheduled pages before the default page since 'scheduled' is after 'default' in
         * the alphabet.
         * `ORDER BY -p.nodesPagesOfTypeSortIndex DESC` sorts ascending by index and puts `NULL` last.
         */
        $query = $this->manager->createQuery(
            "SELECT 
                p,
                -p.nodesPagesOfTypeSortIndex AS HIDDEN negativeSortIndex
            FROM Frontastic\\Catwalk\\FrontendBundle\\Domain\\Page p
            WHERE
                (p.node = :node) AND
                (p.isDeleted = 0) AND
                (
                    (p.state = 'default') OR
                    (
                        (p.state = 'scheduled') AND
                        (
                            p.scheduledFromTimestamp IS NULL OR
                            p.scheduledFromTimestamp <= :currentTimestamp
                        ) AND
                        (
                            p.scheduledToTimestamp IS NULL OR
                            p.scheduledToTimestamp > :currentTimestamp
                        )
                    )
                )
            ORDER BY 
                p.state DESC,
                negativeSortIndex DESC,
                p.pageId ASC,
                p.sequence DESC"
        );
        $query->setParameter('node', $nodeId);
        $query->setParameter('currentTimestamp', time());

        return $query->getResult();
    }

    public function get(string $pageId): Page
    {
        if (($page = $this->repository->findOneByPageId($pageId)) === null) {
            throw new \OutOfBoundsException("Page with ID $pageId could not be found.");
        }

        return $page;
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT MAX(p.sequence)
                    FROM Frontastic\\Catwalk\\FrontendBundle\\Domain\\Page p"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(Page $page): Page
    {
        $this->manager->persist($page);
        $this->manager->flush();

        return $page;
    }

    public function remove(Page $page)
    {
        $page->isDeleted = true;
        $this->store($page);
    }

    public function getEvenIfDeleted(string $pageId): Page
    {
        return $this->withoutUndeletedFilter(function () use ($pageId) {
            return $this->get($pageId);
        });
    }

    private function withoutUndeletedFilter(callable $callback)
    {
        $this->manager->getFilters()->disable('undeleted');
        try {
            return $callback();
        } finally {
            $this->manager->getFilters()->enable('undeleted');
        }
    }
}
