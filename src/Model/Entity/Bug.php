<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bug Entity
 *
 * @property int                        $id
 * @property string                     $title
 * @property string|null                $description
 * @property string|null                $comment
 * @property int                        $type
 * @property string|null                $author
 * @property string|null                $assigned
 * @property int|null                   $status
 * @property \Cake\I18n\FrozenTime      $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null                   $author_id
 * @property int|null                   $assigned_id
 */
class Bug extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title'       => true,
        'description' => true,
        'comment'     => true,
        'type'        => true,
        'author'      => true,
        'assigned'    => true,
        'status'      => true,
        'created'     => true,
        'modified'    => true,
        'author_id'   => true,
        'assigned_id' => true,
    ];

    /**
     * Доступные типы багов
     *
     * @var string<int, string>
     */
    protected static $_types = [
        1 => 'Critical',
        2 => 'Normal',
        3 => 'Feature',
    ];

    /**
     * Доступны варианты статусов бага
     *
     * @var string<int, string>
     */
    protected static $_status = [
        1 => 'New',
        2 => 'WIP',
        3 => 'Done',
        4 => 'Cancel',
    ];

    /**
     * Возвращает список доступных типов бага
     *
     * @return string<int, string>
     */
    public static function getTypeList(): array
    {
        return self::$_types;
    }

    /**
     * Возвращает список доступных статусов бага
     *
     * @return string<int, string>
     */
    public static function getStatusList(): array
    {
        return self::$_status;
    }
}
