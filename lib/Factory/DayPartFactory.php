<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Factory;

use Xibo\Entity\DayPart;
use Xibo\Entity\User;
use Xibo\Exception\NotFoundException;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;
use Xibo\Storage\StorageServiceInterface;

/**
 * Class DayPartFactory
 * @package Xibo\Factory
 */
class DayPartFactory extends BaseFactory
{
    /**
     * Construct a factory
     * @param StorageServiceInterface $store
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     * @param User $user
     * @param UserFactory $userFactory
     */
    public function __construct($store, $log, $sanitizerService, $user, $userFactory)
    {
        $this->setCommonDependencies($store, $log, $sanitizerService);
        $this->setAclDependencies($user, $userFactory);
    }

    /**
     * Create Empty
     * @return DayPart
     */
    public function createEmpty()
    {
        return new DayPart(
            $this->getStore(),
            $this->getLog()
        );
    }

    /**
     * Get DayPart by Id
     * @param $dayPartId
     * @return DayPart
     * @throws NotFoundException
     */
    public function getById($dayPartId)
    {
        $dayParts = $this->query(null, ['dayPartId' => $dayPartId, 'disableUserCheck' => 1]);

        if (count($dayParts) <= 0)
            throw new NotFoundException();

        return $dayParts[0];
    }

    /**
     * Get the Always DayPart
     * @return DayPart
     * @throws NotFoundException
     */
    public function getAlwaysDayPart()
    {
        $dayParts = $this->query(null, ['disableUserCheck' => 1, 'isAlways' => 1]);

        if (count($dayParts) <= 0)
            throw new NotFoundException();

        return $dayParts[0];
    }

    /**
     * Get the Custom DayPart
     * @return DayPart
     * @throws NotFoundException
     */
    public function getCustomDayPart()
    {
        $dayParts = $this->query(null, ['disableUserCheck' => 1, 'isCustom' => 1]);

        if (count($dayParts) <= 0)
            throw new NotFoundException();

        return $dayParts[0];
    }

    /**
     * Get all dayparts with the system entries (always and custom)
     * @return DayPart[]
     */
    public function allWithSystem()
    {
        $dayParts = $this->query(['isAlways DESC', 'isCustom DESC', 'name']);

        return $dayParts;
    }


    /**
     * Get by OwnerId
     * @param int $ownerId
     * @return DayPart[]
     */
    public function getByOwnerId($ownerId)
    {
        return $this->query(null, ['userId' => $ownerId]);
    }

    /**
     * @param array $sortOrder
     * @param array $filterBy
     * @return array[Schedule]
     */
    public function query($sortOrder = null, $filterBy = [])
    {
        $entries = array();

        if ($sortOrder == null)
            $sortOrder = ['name'];

        $params = array();
        $select = 'SELECT `daypart`.dayPartId, `name`, `description`, `isRetired`, `userId`, `startTime`, `endTime`, `exceptions`, `isCustom`, `isAlways` ';

        $body = ' FROM `daypart` ';

        $body .= ' WHERE 1 = 1 ';

        // View Permissions
        $this->viewPermissionSql('Xibo\Entity\DayPart', $body, $params, '`daypart`.dayPartId', '`daypart`.userId', $filterBy);

        // Always include Custom and Always Daypart for DOOH user.
        if ($this->getSanitizer()->getCheckbox('disableUserCheck', $filterBy) == 0 && ($this->getUser()->userTypeId == 4 || ($this->getUser()->isSuperAdmin() && $this->getUser()->showContentFrom == 2))) {
            $body .= ' OR `daypart`.isCustom = 1 OR `daypart`.isAlways = 1 ';
        }

        if ($this->getSanitizer()->getInt('dayPartId', $filterBy) !== null) {
            $body .= ' AND `daypart`.dayPartId = :dayPartId ';
            $params['dayPartId'] = $this->getSanitizer()->getInt('dayPartId', $filterBy);
        }

        if ($this->getSanitizer()->getInt('isAlways', $filterBy) !== null) {
            $body .= ' AND `daypart`.isAlways = :isAlways ';
            $params['isAlways'] = $this->getSanitizer()->getInt('isAlways', $filterBy);
        }

        if ($this->getSanitizer()->getInt('isCustom', $filterBy) !== null) {
            $body .= ' AND `daypart`.isCustom = :isCustom ';
            $params['isCustom'] = $this->getSanitizer()->getInt('isCustom', $filterBy);
        }

        if ($this->getSanitizer()->getString('name', $filterBy) != null) {
            $terms = explode(',', $this->getSanitizer()->getString('name', $filterBy));
            $this->nameFilter('daypart', 'name', $terms, $body, $params, ($this->getSanitizer()->getCheckbox('useRegexForName', $filterBy) == 1));
        }

        if ($this->getSanitizer()->getInt('userId', $filterBy) !== null) {
            $body .= ' AND `daypart`.userId = :userId ';
            $params['userId'] = $this->getSanitizer()->getInt('userId', $filterBy);
        }

        // Sorting?
        $order = '';
        if (is_array($sortOrder))
            $order .= 'ORDER BY ' . implode(',', $sortOrder);

        $limit = '';
        // Paging
        if ($filterBy !== null && $this->getSanitizer()->getInt('start', $filterBy) !== null && $this->getSanitizer()->getInt('length', $filterBy) !== null) {
            $limit = ' LIMIT ' . intval($this->getSanitizer()->getInt('start', $filterBy), 0) . ', ' . $this->getSanitizer()->getInt('length', 10, $filterBy);
        }

        $sql = $select . $body . $order . $limit;

        foreach ($this->getStore()->select($sql, $params) as $row) {
            $dayPart = $this->createEmpty()->hydrate($row, [
                'intProperties' => ['isAlways', 'isCustom']
            ]);
            $dayPart->exceptions = json_decode($dayPart->exceptions, true);

            $entries[] = $dayPart;
        }

        // Paging
        if ($limit != '' && count($entries) > 0) {
            $results = $this->getStore()->select('SELECT COUNT(*) AS total ' . $body, $params);
            $this->_countLast = intval($results[0]['total']);
        }

        return $entries;
    }
}