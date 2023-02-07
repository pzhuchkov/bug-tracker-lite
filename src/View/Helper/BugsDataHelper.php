<?php

namespace App\View\Helper;

use App\Model\Entity\Bug;
use Cake\View\Helper;
use Cake\View\View;

/**
 * BugsData helper
 */
class BugsDataHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Возвращает тип по id
     *
     * @param int $id Идентификатор типа
     *
     * @return string Название типа
     */
    public function getTypeById(int $id): string
    {
        if (array_key_exists($id, Bug::getTypeList())) {
            return Bug::getTypeList()[$id];
        }

        return 'Unknown';
    }

    /**
     * Возвращает статус по id
     *
     * @param int $id Идентификатор статуса
     *
     * @return string Название статуса
     */
    public function getStatusById(int $id): string
    {
        if (array_key_exists($id, Bug::getStatusList())) {
            return Bug::getStatusList()[$id];
        }

        return 'Unknown';
    }
}
