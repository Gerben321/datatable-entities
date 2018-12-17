<?php
namespace Artoroz\Datatable\Types;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Artoroz\Datatable\DatatableCriteriaInterface;
use Artoroz\Datatable\Response\DatatableResponse;

abstract class DatatableResult
{

    /**
     * @var DatatableResponse
     */
    protected $response;

    /**
     * @var DatatableCriteriaInterface $criteriaClass
     */
    protected $criteriaClass;
    /**
     * @var Request
     */
    private $request;

    public function __construct(DatatableResponse $response, Request $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    protected function getMatches() : Collection
    {
        $criteria = $this->criteriaClass->createCriteria();
        $this->response->recordsTotal = $this->repository->count($criteria);

        $this->attachFilters($criteria);
        $this->response->recordsFiltered = $this->repository->count($criteria);

        return $this->repository->matching($criteria);
    }

    public function getResultSet()
    {
        $matches = $this->getMatches();
        $this->response->setData($matches);
        $this->response->setFields($this->fields);

        return $this->response->getResponse();
    }

    public function attachFilters(Criteria $criteria): void
    {
        $this->criteriaClass
            ->filter($criteria)
            ->search($criteria)
            ->order($criteria)
            ->pagination($criteria)
        ;
    }
}