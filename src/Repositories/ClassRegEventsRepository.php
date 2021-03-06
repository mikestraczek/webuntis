<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Repositories\Repository;
use Webuntis\Query\Query;
use Webuntis\Exceptions\RepositoryException;

/**
 * ClassRegEventsRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ClassRegEventsRepository extends Repository
{

        /**
         * return all ClassRegEvents that have been searched for
         * @param array $params
         * @param array $sort
         * @param int $limit
         * @return array
         * @throws RepositoryException
         */
    public function findBy(array $params, array $sort = [], int $limit = null, $startDate = null, \DateTime $endDate = null, array $element = []) : array 
    {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        $data = $this->findAll($sort, $limit, $startDate, $endDate, $element);

        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->find($data, $params);
            $data = $this->sort($data, $field, $sortingOrder);
        } else {
            $data = $this->find($data, $params);
        }
        if ($limit !== null) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }

    /**
     * return all ClassRegEvents found
     * @param array $sort
     * @param int $limit 
     * @return array
     */
    public function findAll(array $sort = [], int $limit = null, \DateTime $startDate = null, \DateTime $endDate = null, array $element = []) : array 
    {
        if ($startDate && $endDate) {
            if (!empty($element)) {
                $data = $this->executionHandler->execute($this, ['options' => 
                    [
                        'startDate' => date_format($startDate, 'Ymd'),
                        'endDate' => date_format($endDate, 'Ymd'),
                        'element' => $element
                    ]
                ]);
            } else {
                $data = $this->executionHandler->execute($this, ['options' => 
                    [
                        'startDate' => date_format($startDate, 'Ymd'),
                        'endDate' => date_format($endDate, 'Ymd')
                    ]
                ]);
            }
        } else {
            $query = new Query();
            $schoolyear = $query->get('Schoolyears')->getCurrentSchoolyear();

            if (!empty($element)) {
                $data = $this->executionHandler->execute($this, ['options' => 
                    [
                        'startDate' => date_format($schoolyear->getStartDate(), 'Ymd'),
                        'endDate' => date_format($schoolyear->getEndDate(), 'Ymd'),
                        'element' => $element
                    ]
                ]);
            } else {
                $data = $this->executionHandler->execute($this, ['options' => 
                    [
                        'startDate' => date_format($schoolyear->getStartDate(), 'Ymd'),
                        'endDate' => date_format($schoolyear->getEndDate(), 'Ymd')
                    ]
                ]);
            }
        }
        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($data, $field, $sortingOrder);
        }
        if ($limit !== null) {
            $data = array_slice($data, 0, $limit);
        }
        return $data;
    }
}
