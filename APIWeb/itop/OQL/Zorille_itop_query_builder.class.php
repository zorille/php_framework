<?php

namespace Zorille\itop;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use ReflectionObject;
use Zorille\framework as core;
use Zorille\framework\options;
use Zorille\itop\QueryBuilderLikeOperatorType;
use Zorille\itop\QueryBuilderOperator as QLOperator;
use Zorille\salesforce\connexion_connector;

abstract class query_builder extends wsclient_rest implements iquery_builder
{
    #[ArrayShape([
        'max' => 'int',
        'page' => 'int',
        'limit' => 'int',
    ])]
    private array $pagination = [
        'max' => -1,
        'page' => 1,
        'limit' => 500
    ];

    protected array $query = [];
    protected array $selectedFields = [];
    protected array $results = [];

    protected array $joins = [];

    /**
     * Cette interface est dans la PHPDoc car elle est présente uniquement pour
     * l'auto_completion.
     * Elle ne doit pas être mise dans le typage natif car si non une erreur
     * sera générée par l'interpréteur php.
     * La raison est le mode de chargement des classes `ConnexionManager`
     * dans les modules de synchronisation itop qui entraine un soucis de
     * dépendence circulaire.
     *
     * @var connexion_connector|null
     */
    protected static $connexion = null;

    protected static string $itop_server;

    private static ?core\options $listOptions = null;

    public function __construct($sort_en_erreur = false, $entete = __CLASS__)
    {
        parent::__construct($sort_en_erreur, $entete);

        if (!empty(self::$connexion)) {
            $list_options = self::$connexion->getListOptions();
            static::setListeOptions($list_options);
        }

        if (!empty($this->query)) $this->setQuery($this->query);
    }

    /**
     * @param options|null $list_options
     * @param datas|null $data
     * @param bool $sort_en_erreur
     * @param string $entete
     * @return self
     * @throws Exception
     */
    public static function create(
        ?core\options $list_options = null,
        ?datas $data = null,
        bool $sort_en_erreur = false,
        string $entete = __CLASS__
    ): self {
        core\abstract_log::onDebug_standard ( __METHOD__, 1 );
        $list_options = $list_options ?? static::getListOptions();

        if (empty($list_options)) {
            core\abstract_log::onError_standard(
                "\$list_option doit être de type Zorille\\framework\\options, or il est null"
            );
        }

        if (empty($data)) {
            $data = datas::creer_datas($list_options);
        }

        $object = new static($sort_en_erreur, $entete);
        $object->_initialise([
            "options" => $list_options,
            "datas" => $data
        ]);

        $object->prepare_connexion($object->getItopServer());

        return $object;
    }

    abstract protected static function getObjectName(): string|array;
    abstract protected function getAssociatedModel(): string;

    public function select(string ...$fields): self
    {
		$objName = static::getObjectName();
		if (is_array($objName)) {
			[$objName, $alias] = $objName;
		}
		else {
			$alias = substr(ucfirst($objName), 0, 1);
		}
        $this->selectedFields = $fields;
        if (empty($fields)) {
            $this->selectedFields =
                ($ref = new ReflectionClass(query_builder_default_selected_fields::class)) &&
                $ref->hasConstant($objName)
                    ? $ref->getConstant($objName)
                    : ['*'];
        }

        $this->completeQuery(['SELECT', $objName, 'AS', $alias]);

        return $this;
    }

    #[ArrayShape([
        0 => 'string',
        1 => 'string',
    ])]
    private function getAlias(query_builder|string $model): array
    {
        $objName = $model::getObjectName();
        if (is_array($objName) && !empty($objName[1])) {
            [$objName, $alias] = $objName;
        }
        else {
            $alias = substr(ucfirst($objName), 0, 1);
        }

        return [$objName, $alias];
    }

	/**
	 * @throws Exception
	 */
	public function join(
		query_builder|string $model,
		#[ArrayShape([
			'source' => 'string',
			'target' => 'string',
			'operator' => QLOperator::class,
		])]
		array|string $on
	): self
	{
        [,$localAlias] = $this->getAlias(static::class);

        [$objName, $alias] = $this->getAlias($model);
		
		if (is_string($on)) {
			if (preg_match("/^(?<field_1>[a-zA-Z_]+) *(?<operator>=|!=|<|>|<=|>=) *(?<field_2>[a-zA-Z_]+)$/m", $on, $matches)) {
                $equation = "{$alias}.{$matches['field_1']}{$matches['operator']}{$localAlias}.{$matches['field_2']}";
			}
            elseif (preg_match('/^((?<class_1>[a-zA-Z_\\\]+)(::|\.))?(?<field_1>[a-zA-Z_]+) *(?<operator>=|!=|<|>|<=|>=) *((?<class_2>[a-zA-Z_\\\]+)(::|\.))?(?<field_2>[a-zA-Z_]+)$/m', $on, $matches)) {
                [,$alias1] = !empty($matches['class_1'])
                    ? $this->getAlias($matches['class_1'])
                    : [null, $localAlias];

                [,$alias2] = !empty($matches['class_2'])
                    ? $this->getAlias($matches['class_2'])
                    : [null, $localAlias];

                $equation = "{$alias1}.{$matches['field_1']}{$matches['operator']}{$alias2}.{$matches['field_2']}";
            }
			else {
                $equation = $on;
                if (!preg_match('/^(?<alias_1>([A-Za-z_]+)\.(?<field_1>[a-zA-Z_]+)) *(?<operator>=|!=|<|>|<=|>=) *(?<alias_2>([A-Za-z_]+)\.(?<field_2>[a-zA-Z_]+))$/m', $on)) {
                    throw new Exception("Le format du champ 'on' est incorrect");
                }
			}
		}
		else {
			$equation = "{$alias}.{$on['target']}{$on['operator']->value}{$localAlias}.{$on['source']}";
		}
		
		$this->completeQuery([
			'JOIN', $objName, 
			'AS', $alias, 
			'ON', $equation
		]);
        $this->joins[] = $alias;
		return $this;
	}

    public function where(
		#[ArrayShape([
			'var' => 'string',
			'model' => query_builder::class,
		])]
		array|string $var,
		QLOperator $operator,
		mixed $value = null
    ): self
    {
        if (is_null($value)) $value = 'NULL';
        elseif (is_string($value)) {
            preg_match("/^SELECT [A-Z][A-Za-z0-9_]* .*/", $value, $matches_query);
            preg_match("/^DATE_.*/", $value, $matches_dates);
            preg_match("/^NOW\(\)/", $value, $matches_now);

            $value = empty($matches_query) && empty($matches_dates) && empty($matches_now)
                ? (str_contains($value, "'") ? '"' : "'") . $value . (str_contains($value, "'") ? '"' : "'")
                : "({$value})";
        }
        elseif (is_int($value) || is_float($value)) {
            $value = (string)$value;
        }
        elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        elseif (is_array($value)) {
            if (in_array($operator, [QLOperator::IN, QLOperator::NOT_IN])) {
                $value = '(' . implode(',', array_map(static fn($v) => is_string($v) ? "'{$v}'" : $v, $value)) . ')';
            }
        }

		if (is_array($var)) {
			[
				'var' => $var,
				'model' => $model
			] = $var;
		}
		elseif (preg_match('/^(?<model>[a-zA-Z_\\\\]+):{2}(\$?(?<var>[a-zA-Z_]+))$/m', $var, $matches)) {
			$var = $matches['var'];
			$model = $matches['model'];
		}
		else {
			$model = static::class;
		}

        if (is_array($model::getObjectName())) {
            [, $alias] = $model::getObjectName();
        }
        else {
		    $alias = substr(ucfirst($model::getObjectName()), 0, 1);
        }

        $isFirst = !in_array('WHERE', $this->query);
        $inOr = $this->query[count($this->query) - 1] === 'OR';

        $value = is_string($value)
            ? $value
            : (str_contains($value->value, "'") ? '"' : "'") . $value->value . (str_contains($value->value, "'") ? '"' : "'");

        $this->query = [
            ...$this->query,
            ...($isFirst ? ['WHERE'] : []),
            "{$alias}.{$var} {$operator->value} {$value}"
        ];

        if ($inOr) {
            $this->query[] = ')';
        }

        return $this;
    }
    public function and(): self
    {
        $toAdd = 'AND';
        $this->addQueryItemIf(
            $toAdd,
            $this->query[array_key_last($this->query)] !== $toAdd
        );
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
            elseif (
                $this->query[count($this->query) - 2] === 'AND' ||
                $this->query[count($this->query) - 2] === 'WHERE'
            ) {
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
        return [QLOperator::from(($not ? 'NOT ' : '') . 'LIKE'), $v];
    }

    /**
     * @throws Exception
     */
    private function getRecursive(?string $q = null): self
    {
        $query = is_null($q) ? $this->query : $q;
        $strQuery = is_array($query) ? implode(' ', $query) : $query;
        if (str_starts_with($strQuery, 'SELECT ') && !empty($this->joins)) {
            $strQuery = str_replace('SELECT ', "SELECT " . $this->getObjectName()[is_array($this->getObjectName()) ? 1 : 0] . ',' . implode(',', $this->joins).' FROM ', $strQuery);
        }
        $this->query = explode(' ', $strQuery);
        $this->joins = [];

        $objectName = static::getObjectName();
	    if (is_array($objectName)) {
		    [$objectName] = $objectName;
	    }

        $results = json_decode(
            json_encode(
                $this->paginate(
                    $this->pagination['page'],
                    $this->pagination['limit'],
                )
                    ->core_get(
                        $objectName, $strQuery,
                        implode(',', (is_null($q) ? $this->selectedFields : ['*'])),
                        force_pagination_reset: false
                    )
            ),
            true
        );

        if ($results['code'] === 0) {
            if ($this->pagination['max'] === -1) {
                $this->pagination['max'] = intval(explode(': ', $results['message'])[1]);

                $this->results = [
                    'code' => $results['code'],
                    'objects' => $results['objects'],
                ];
            } else {
                if (!is_null($results['objects'])) {
                    $this->results['objects'] = array_merge(($this->results['objects'] ?? []), $results['objects']);
                }
            }

            $this->getListeOptions()->onDebug("Results number : " . count($this->results['objects'] ?? []), 1, get_class($this));

            if ($this->pagination['page'] * $this->pagination['limit'] < $this->pagination['max']) {
                $this->pagination['page']++;
                return $this->getRecursive();
            }
        }

        $this->pagination = array_merge($this->pagination, [
            'max' => -1,
            'page' => 1,
        ]);
        return $this;
    }

    /**
     * @template T of (string[]|string|null)
     * @param T $q
     * @return (T is null ? self : array)
     * @throws Exception
     */
    public function build($q = null): self|array
    {
        if (is_null($q)) {
            $this->setResult([]);
        }

        $query = is_null($q) ? $this->query : $q;
        $strQuery = is_array($query) ? implode(' ', $query) : $query;

        $this->onDebug("OQL : {$strQuery}", 1);

        $this->beforeFetch();

        $results = $this->getRecursive()->results;

        if (is_null($q)) {
            $this->setResult($results)->resetQuery();
        }

        $this->afterFetch();

        return $this;
    }

    protected function beforeFetch(): self
    {
		return $this;
    }

    protected function afterFetch(): self
    {
		return $this;
    }

    public function getResult(): array
    {
        return $this->results;
    }
    protected function setResult(array $results, bool $merge = false): self
    {
        $results['objects'] = array_map(
            fn(array|data_model $m) => is_array($m)
                ? (in_array('fields', array_keys($m))
                    ? $m['fields']
                    : $m)
                : (is_a($m, data_model::class)
                    ? $m->toArray()
                    : $m),
            $results['objects'] ?? []
        );

        if ($merge) {
            $results = array_merge(
                ($this->results ?? []),
                $results
            );
        }

        $this->results = $results;

        return $this;
    }

    protected function completeQuery(array $query): self
    {
        $this->query = [...$this->query, ...$query];

        return $this;
    }
    protected function completeQueryIf(array $query, bool $condition): self
    {
        if ($condition) {
            $this->completeQuery($query);
        }

        return $this;
    }
    protected function addQueryItemIf(string $queryItem, bool $condition): self
    {
        return $this->completeQueryIf([$queryItem], $condition);
    }
    protected function resetQuery(): self
    {
        $this->query = [];

        return $this;
    }

    protected static function getListOptions(): core\options
    {
        return self::$listOptions;
    }
    public static function setListOptions(?core\options $listOptions): void
    {
        self::$listOptions = $listOptions;
    }

    protected function getItopServer(): string
    {
        return self::$itop_server;
    }
    public static function setItopServer(string $itop_server): void
    {
        self::$itop_server = $itop_server;
    }

    public function setWsClient(): self
    {
        return $this;
    }
    public function getWsClient(): query_builder
    {
        return $this;
    }

    public static function setConnexion($connexion): void
    {
        if (empty(self::$connexion)) {
            self::$connexion = $connexion;
            static::setListOptions($connexion->getListOptions());
        }
    }

    /**
     * @param array $query
     * @return $this
     */
    public function setQuery($query): self
    {
        $this->query = $query;
        return $this;
    }
    public function getQuery(): string
    {
        return implode(' ', $this->query);
    }

    #[ArrayShape([
        'objects' => 'array'
    ])]
    public function toModel(): array
    {
        $results = $this->getResult();
        /** @var core\data_model $model */
        $model = $this->getAssociatedModel();
        foreach ($results['objects'] as $key => $object) {
            $results['objects'][$key] = $model::convert($object);
            $ref = new ReflectionObject($results['objects'][$key]);
            if ($ref->hasProperty('class')) {
                $results['objects'][$key]->setClass(explode("::", $key)[0]);
            }
        }

        return $results;
    }

    public function __toString(): string
    {
        return $this->getQuery();
    }
}