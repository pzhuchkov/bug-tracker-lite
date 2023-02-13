<?php

namespace App\View\Helper;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\View\Helper;
use Cake\View\View;

/**
 * SortQuery helper
 */
class SortQueryHelper extends Helper
{
    /**
     * @var string Имя переменной в query params
     */
    public static string $queryName = 'order';
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var \Cake\Http\ServerRequest|null реквест
     */
    private \Cake\Http\ServerRequest $currentRequest;

    /**
     * @param View               $View    Вьюха
     * @param array              $config  Конфиг для вьюхи
     * @param ServerRequest|null $request Реквест. нужен для мока в тестах
     */
    public function __construct(View $View, array $config = [], ServerRequest $request = null)
    {
        parent::__construct($View, $config);

        if (is_null($request) === true) {
            $request = Router::getRequest();
        }
        $this->currentRequest = $request;
    }

    /**
     * Создания URL учитывая текущий, для сортировок
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function buildUrl(string $fieldName): string
    {
        $sortParams = $this->currentRequest->getQuery(self::$queryName);

        if (is_null($sortParams) === true || array_key_exists($fieldName, (array)$sortParams) === false) {
            $sortParams[$fieldName] = 'ASC';
        }

        $sortParams[$fieldName] = ($sortParams[$fieldName] != 'ASC') ? 'ASC' : 'DESC';

        $currentQueryParams = $this->currentRequest->getQuery();
        $currentQueryParams[self::$queryName] = $sortParams;

        return sprintf(
            '%s?%s',
            $this->currentRequest->getPath(),
            http_build_query(
                $currentQueryParams
            )
        );
    }

}
