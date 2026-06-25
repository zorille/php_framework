<?php

namespace Zorille\salesforce;

use Error;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use stdClass;
use Zorille\framework as core;
use Zorille\framework\QueryBuilderLikeOperatorType;
use Zorille\framework\QueryBuilderOperator as QLOperator;

abstract class query_builder implements iquery_builder
{
    private string $salesforceServeurOption = '';
    private core\options $list_options;

//    https://nlighten--uat.sandbox.my.salesforce.com/services
    protected string $baseUrl = '/data/v60.0/query';
    private array $headers = [];
    /** @var array|string|null */
    private string|array|null $result = null;

    /** @var bool|array $error */
    private array|bool $error = false;

    protected array $addedQueryString = [];

    /** @var string|string[] $query */
    protected string|array $query = '';

    /** @var connexion_connector|null $connexion */
    private static $connexion;

    private wsclient $wsclient;

    /********************************************************/
    /**** Abstract methods definition                    ****/
    /********************************************************/

    abstract protected function getAssociatedModel(): string;
    abstract protected function getObjectName(): string;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!empty(self::$connexion)) {
            $this->list_options = self::$connexion->getListOptions();
            $this->setWsClient();
        }

        if (!empty($this->query)) $this->setQuery($this->query);
    }

    public static function create(): self
    {
        return new static();
    }

    public static function setConnexion($connexion): void
    {
        if (empty(self::$connexion)) {
            self::$connexion = $connexion;
        }
    }

    /**
     * @return connexion_connector|null
     */
    protected function getConnexion()
    {
        return self::$connexion;
    }

    protected function beforeFetch(): self
    {
        return $this;
    }

    protected function afterFetch(): self
    {
        return $this;
    }

    private function getErrorHeader(string $errCode): string {
	    return match ($errCode) {
		    'MALFORMED_QUERY' => 'La requête est mal formattée',
		    default => $errCode,
	    };
    }

    /**
     * Permet de lancer une requête recursive tant qu'il n'y a pas moins de 200 résultats dans la requête
     *
     * @throws Exception
     */
    private function recursiveGet(bool $first = true, ?string $nextUrl = null): self
    {
        $this->getWsClient()->onDebug( __METHOD__, 1 );

        if ($first) $this->beforeFetch();

        if (is_null($nextUrl)) {
            $query = $this->getQuery();
            $regex = "/(SELECT) (.*) (FROM .*)";
            if (str_contains($query, ' LIMIT ')) {
                $regex .= "( LIMIT .*)";
            }
            $regex .= "/m";
            $query = preg_replace($regex, "$1 COUNT(Id) $3", $query);

            $this->getListOptions()->onInfo($query, entete: get_class($this));
            $query = preg_replace("/(SELECT) (.*) (FROM .*)/m", "$1 COUNT(Id) $3", $this->getQuery());

            $result = $this->getWsClient()
                ->getMethod(
                    $this->getBaseUrl(),
                    ['q' => $query]
                );
            $result = json_decode(json_encode($result), true);
            if (!isset($result['records'])) {
                throw new Exception($result[0]['message']);
            }

            $count = $result['records'][0]['expr0'];
            $result = $this->limit($count)
                ->setQuery($this->query)
                ->getWsClient()
                ->getMethod(
                    $this->getBaseUrl(),
                    $this->addedQueryString
                );
        } else {
            $result = $this->getWsClient()
                ->getMethod(str_replace('/services', '', $nextUrl));
        }

        $results = json_decode(json_encode($result), true);
        if (!isset($results['records'])) {
            throw new Exception($results[0]['message']);
        }

        if (isset($results['nextRecordsUrl'])) {
            $this->setResult([
                'records' => [
                    ...($this->getResult()['records'] ?? []),
                    ...$results['records']
                ]
            ], true);
        }
        else {
            if (isset($results[0]['errorCode'])) {
                $this->setResult([]);
                $this->error = $results;
                core\abstract_log::onError_standard("{$this->getErrorHeader($results[0]['errorCode'])}: {$results[0]['message']}");
                return $this;
            }

            $this->getListOptions()->onDebug("Results number : " . count($this->results['objects'] ?? []), 1, get_class($this));

            $this->setResult([
                'records' => [
                    ...($this->getResult()['records'] ?? []),
                    ...$results['records']
                ]
            ], true);

            $this->afterFetch();

            return $this;
        }

        return $this->recursiveGet(false, $results['nextRecordsUrl']);
    }

    /**
     * @throws Exception
     */
    public function get(string $uri = '', array $queryString = []): self
    {
        $this->getWsClient()->onDebug( __METHOD__, 1 );

        $result = $this->getWsClient()
            ->getMethod(
                "{$this->getBaseUrl()}{$uri}",
                array_merge($queryString, $this->addedQueryString)
            );

        return $this->setResult(json_decode(json_encode($result), true));
    }
    /**
     * @throws Exception
     */
    public function post(string $uri, array $body = []): self
    {
        return $this->setResult(
            $this->getWsClient()
                ->postMethod("{$this->getBaseUrl()}{$uri}", $body)
        );
    }
    /**
     * @throws Exception
     */
    public function put(string $uri, array $body = []): self
    {
        return $this->setResult(
            $this->getWsClient()
                ->putMethod("{$this->getBaseUrl()}{$uri}", $body)
        );
    }
    /**
     * @throws Exception
     */
    public function patch(string $uri, array $body = []): self
    {
        return $this->setResult(
            $this->getWsClient()
                ->patchMethod("{$this->getBaseUrl()}{$uri}", $body)
        );
    }
    /**
     * @throws Exception
     */
    public function delete(string $uri, array $body = []): self
    {
        return $this->setResult(
            $this->getWsClient()
                ->deleteMethod("{$this->getBaseUrl()}{$uri}", $body)
        );
    }

    /********************************************************/
    /**** Array to Model converter                       ****/
    /********************************************************/

    #[ArrayShape([
        'totalSize' => 'integer',
        'done' => 'boolean',
        'records' => 'array'
    ])]
    public final function toModel(): array
    {
        /** @var data_model $modelClass */
        $modelClass = $this->getAssociatedModel();
        $result = $this->getResult();

        if ($this->error) {
            return [
                'totalSize' => 0,
                'records' => []
            ];
        }

        foreach ($result['records'] as $i => $record) {
            $result['records'][$i] = $modelClass::convert($record);
        }

        return $result;
    }

    /********************************************************/
    /**** Getters / Setters                              ****/
    /********************************************************/

    public final function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
    protected final function getHeaders(): array|stdClass
    {
        return $this->headers;
    }

    public final function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    public final function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return array|string|null
     */
    public final function getResult(): array|string|null
    {
        return $this->result;
    }

	/**
	 * @param array|string|null $result
	 * @param bool $merge
	 * @return query_builder
	 */
    protected final function setResult(array|string|null $result, bool $merge = false): self
    {
        if ($merge) {
            $result = array_merge(
                ($this->result ?? []),
                $result
            );
        }
        $this->result = $result;

        return $this;
    }

    /** @param string|string[] $query */
    public final function setQuery($query): self
    {
        if (empty($query)) {
            unset($this->addedQueryString['q']);
            return $this;
        }

        if ($query[1] !== 'COUNT()') {
            if (is_array($query)) {
                if (!empty($query) && !in_array('LIMIT', $query)) {
                    $query = [...$query, 'LIMIT', '200'];
                }

                $query = implode(" ", $query);
            } else {
                if (!empty($query) && !str_contains($query, ' LIMIT ')) {
                    $query .= "LIMIT 200";
                }
            }
        }

        $this->addedQueryString['q'] = $query;
        return $this;
    }

    protected final function getSalesforceServeurOption(): string
    {
        return $this->salesforceServeurOption;
    }
    /**
     * @throws Exception
     */
    public final function setSalesforceServeurOption(string $salesforceServeurOption): self
    {
        $this->salesforceServeurOption = $salesforceServeurOption;
        try {
            $wsClient = $this->getWsClient();
        } catch (Error $e) {
            $this->setWsClient();
        } finally {
            $wsClient = $this->getWsClient();
        }
        $wsClient->prepare_connexion($salesforceServeurOption);

        return $this;
    }

    protected function getListOptions(): core\options
    {
        return $this->list_options;
    }

    /**
     * @throws Exception
     */
    public function setWsClient(): self
    {
        if (empty($this->list_options)) {
            global $liste_option;
            $this->list_options = $liste_option;
        }
        $list_options = $this->getListOptions();
        $this->wsclient = wsclient::creer_wsclient(
            $list_options,
            datas::creer_datas($list_options)
        );

        return $this;
    }
    public function getWsClient(): wsclient
    {
        return $this->wsclient;
    }

    /**
     * @param bool|array $error
     * @return self
     */
    protected function setError(bool|array $error): self
    {
        $this->error = $error;

        return $this;
    }

    /********************************************************/
    /**** Query Builder                                  ****/
    /********************************************************/

    public function select(string ...$fields): self
    {
        if (empty($fields)) {
            /** @var data_model $model */
            $model = $this->getAssociatedModel();
            $fields = $model::getFields();
        }
        $fields = array_map(fn ($field) => ucfirst($field), $fields);
        $this->query = ['SELECT', implode(',', $fields)];

        return $this;
    }
    public function count(): self
    {
        $this->query = ['SELECT', 'COUNT(Id)'];

        return $this;
    }
    public function where(string $var, QLOperator $operator, $value): self
    {
        $var = ucfirst($var);
        $isFirst = !in_array('FROM', $this->query);
        $inOr = $this->query[count($this->query) - 1] === 'OR';
        if ($isFirst) {
            $this->query = [
                ...$this->query,
                'FROM',
                $this->getObjectName()
            ];
        }
        $isDate = is_string($value) ? preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/", $value) : false;
        $printValue = (is_string($value) && !$isDate ? "'" : "") .
            (is_bool($value) ? ($value ? 'true' : 'false') : "{$value}") .
            (is_string($value) && !$isDate ? "'" : "");
        $this->query = [
            ...$this->query,
            ...($isFirst ? ['WHERE'] : []),
            "{$var} {$operator->value} {$printValue}"
        ];

        if ($inOr) {
            $this->query[] = ')';
        }

        return $this;
    }
    public function limit(int $limit = 200): self
    {
        $isFirst = !in_array('FROM', $this->query);
        if ($isFirst) {
            $this->query = [
                ...$this->query,
                'FROM',
                $this->getObjectName()
            ];
        }

        if (in_array('LIMIT', $this->query)) {
            $index = array_search('LIMIT', $this->query, true);

            $this->query[$index + 1] = $limit;
        }
        else {
            $this->query = [
                ...$this->query,
                'LIMIT',
                $limit,
            ];
        }

        return $this;
    }
    public function offset(int $offset = 0): self
    {
        $isFirst = !in_array('FROM', $this->query);
        if ($isFirst) {
            $this->query = [
                ...$this->query,
                'FROM',
                $this->getObjectName()
            ];
        }

        if ($offset > 0) {
            if (in_array('OFFSET', $this->query)) {
                $index = array_search('OFFSET', $this->query, true);

                $this->query[$index + 1] = $offset;
            }
            else {
                $this->query = [
                    ...$this->query,
                    'OFFSET',
                    $offset
                ];
            }
        }

        return $this;
    }
    public function and(): self
    {
        if (in_array('WHERE', $this->query)) {
            $this->query = [
                ...$this->query,
                'AND'
            ];
        }
        return $this;
    }
    public function or(): self
    {
        if (in_array('WHERE', $this->query)) {
            if ($this->query[count($this->query) - 1] === ')') {
                array_pop($this->query);
            }

            if ($this->query[count($this->query) - 2] === 'OR') {
                $this->query[] = 'OR';
            }
            elseif ($this->query[count($this->query) - 2] === 'AND' || $this->query[count($this->query) - 2] === 'WHERE') {
                $q = [];
                foreach ($this->query as $i => $p) {
                    if ($i === count($this->query) - 1) {
                        $q[] = "(";
                    }

                    $q[] = $p;
                }
                $q[] = 'OR';

                $this->query = $q;
            }
        }
        return $this;
    }

    /**
     * @param QueryBuilderLikeOperatorType $type
     * @param string $value
     * @param bool $not
     * @return string[]
     * @uses
     * ```
     * $opportunitiesProductsFetcher->select()
     * ->where('Property', ...$this->like(QueryBuilderLikeOperatorType::CONTAINS, 'une valeur', not: false))
     * ->build()
     * ->toModel()['records'];
     * ```
     */
    public static function like(QueryBuilderLikeOperatorType $type, string $value, bool $not = false): array
    {
        $v = match ($type) {
            QueryBuilderLikeOperatorType::START_BY => "{$value}%",
            QueryBuilderLikeOperatorType::END_BY => "%{$value}",
            QueryBuilderLikeOperatorType::PATTERN => $value,
            default => "%{$value}%",
        };
        return [QLOperator::from(($not ? 'NOT_' : '') . 'LIKE'), $v];
    }

    /**
     * @throws Exception
     */
    public function build(): self
    {
        if (empty($this->getSalesforceServeurOption())) {
            core\abstract_log::onError_standard('Il nous faut salesforce_serveur pour travailler');
            return $this;
        }

        $isFirst = !in_array('FROM', $this->query);
        if ($isFirst) {
            $this->query = [
                ...$this->query,
                'FROM',
                $this->getObjectName()
            ];
        }
        $this->setQuery($this->query);

        return $this->recursiveGet();
    }

    public function getQuery(): string
    {
        if (!in_array('FROM', $this->query)) {
            $this->query = [...$this->query, 'FROM', $this->getObjectName()];
        }
        return implode(' ', $this->query);
    }
}