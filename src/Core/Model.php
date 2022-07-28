<?php

namespace JLA;

class Model extends DB
{
	protected $primaryKey = 'id';
	private $select = array();
	private $from = array();
	private $clause = array();
	private $having = array();
	private $groupby = array();
	private $order = array();
	private $limit = array();
	private $store = array();
	private $data;
	private $resetAfterQuery = true;

	public function __construct($id = null)
    {
		if ($id) {
			$this->where($this->primaryKey, '=', $id)
				 ->limit(1)
				 ->find();
			$this->store = $this->fetch(\PDO::FETCH_ASSOC);
		}
	}

	public function setReset($reset = true)
    {
		$this->resetAfterQuery = $reset;
		return $this;
	}

	public function select($field, $as = null)
    {
		$this->select[] = array('field' => $field, 'as' => $as);
		return $this;
	}

	public function from($table, $joinType = null, $tableField = null, $joinField = null)
    {
		$this->from[] = array(
			'table'      => $table,
			'joinType'   => $joinType,
			'tableField' => $tableField,
			'joinField'  => $joinField
		);
		return $this;
	}

	public function where($field, $operator, $value, $joiner = null)
    {
		$this->clause[] = array(
			'field'    => $field,
			'operator' => $operator,
			'value'    => $value,
			'joiner'   => $joiner
		);
		return $this;
	}

	public function having($field, $operator, $value, $joiner = null, $brace = 0)
    {
		$this->having[] = array(
			'field'    => $field,
			'operator' => $operator,
			'value'    => $value,
			'joiner'   => $joiner,
			'brace'    => $brace
		);
		return $this;
	}

	public function group($field)
    {
		$this->groupby[] = $field;
		return $this;
	}

	public function brace($status, $joiner = null)
    {
		$this->clause[] = ($status == 'open' ? '(' : ')')
			. ($joiner ? " {$joiner} " : '');
	}

	public function order($field, $direction = 'ASC')
    {
		$this->order[] = array('field' => $field, 'direction' => $direction);
		return $this;
	}

	public function limit($limit, $start = null)
    {
		$this->limit = array('limit' => $limit, 'start' => $start);
		return $this;
	}

	public function insert($data = array())
    {
		foreach ($data as $field => $value) {
			$this->$field = $value;
		}

		if ($this->execute($this->build('insert'), $this->store, $this->resetAfterQuery)) {
			$this->{$this->primaryKey} = $this->getConnection()->lastInsertId();
		}
	}

	public function find()
    {
		$this->execute($this->build('select'), $this->data, $this->resetAfterQuery);
	}

	public function update($data = array())
    {
		foreach ($data as $field => $value) {
			$this->$field = $value;
		}

		if (! $this->clause) {
			$this->where($this->primaryKey, '=', $this->{$this->primaryKey});
		}

		$this->execute($this->build('update'), $this->data, $this->resetAfterQuery);
	}

	public function save($data = array())
    {
		$this->{$this->primaryKey}
			? $this->update($data)
			: $this->insert($data);
	}

	public function delete($id = null)
    {
		if ($id)
        {
			$this->where($this->primaryKey, '=', $id);
		}

		$this->execute($this->build('delete'), $this->data, $this->resetAfterQuery);
	}

	public function build($type)
    {
		switch ($type) {
			case 'insert' : $sql = $this->buildInsert(); break;
			case 'select' : $sql = $this->buildSelect(); break;
			case 'update' : $sql = $this->buildUpdate(); break;
			case 'delete' : $sql = $this->buildDelete(); break;
		}

		return $sql;
	}

	private function buildInsert()
    {
		$keys   = array_keys($this->store);
		$fields = implode(', ',   $keys);
		$values = implode(', :',  $keys);

		return "INSERT INTO {$this->table} ({$fields}) VALUES (:{$values})";
	}

	private function buildSelect()
    {
		return "SELECT {$this->buildFragmentSelect()}
			    FROM   {$this->buildFragmentFrom()}
			           {$this->buildFragmentWhere()}
			           {$this->buildFragmentWhere('HAVING')}
			           {$this->buildFragmentGroup()}
			           {$this->buildFragmentOrder()}
			           {$this->buildFragmentLimit()}";
	}

	private function buildUpdate()
    {
		return "UPDATE {$this->buildFragmentFrom()}
		        SET    {$this->buildFragmentUpdate()}
		               {$this->buildFragmentWhere()}
		               {$this->buildFragmentLimit()}";
	}

	private function buildDelete()
    {
		return "DELETE FROM {$this->buildFragmentFrom()}
			                {$this->buildFragmentWhere()}
			                {$this->buildFragmentLimit()}";
	}

	private function buildFragmentSelect()
    {

		if (empty($this->select))
        {
			return '*';
		}

		$fields = array();
		foreach ($this->select as $select)
        {
			$as = $select['as']
				? " AS '{$select['as']}'"
				: '';

			$fields[] = "{$select['field']} {$as}";
		}

		return implode(', ', $fields);
	}

	private function buildFragmentFrom()
    {
		if (empty($this->from))
        {
			return $this->table;
		}
		$tables = array();
		foreach ($this->from as $from)
        {
			$tables[] = $from['tableField'] && $from['joinField']
				? "{$from['joinType']} JOIN {$from['table']} ON {$from['tableField']} = {$from['joinField']}"
				: $from['table'];
		}
		return implode(', ', $tables);
	}

	private function buildFragmentUpdate()
    {
		$fields = array();

		foreach ($this->store as $field => $value)
        {
			if ($field == $this->primaryKey)
            {
				continue;
			}

			$fields[] = "{$field} = :{$field}";
			$this->data[$field] = $value;
		}

		return implode(', ', $fields);
	}

	private function buildFragmentWhere($type = 'WHERE')
    {
		if ($type == 'HAVING' && empty($this->having))
        {
			return '';
		} else if (empty($this->clause)) {
			return '';
		}

		$sql        = '';
		$sqlClauses = '';
		$clauses    = $type == 'HAVING' ? $this->having : $this->clause;
		$clauseType = strtolower($type);

		foreach ($clauses as $clauseIndex => $clause)
        {
			if (! is_array($clause)) {
				$sqlClauses .= $clause;
				continue;
			}

			$clauseVar = "_{$clauseType}{$clauseIndex}";


			$sql = $clauseIndex==0?'':' AND ';

			if (is_array($clause['value'])) {

				$clauseIn = array();

				foreach ($clause['value'] as $index => $value) {
					$clauseIn[] = ":{$clauseVar}{$index}";
					$this->data["{$clauseVar}{$index}"] = $value;
				}

				$sql .= "{$clause['field']} IN (" . implode(', ', $clauseIn) . ")";
			}

			else {
				$sql .= "{$clause['field']} {$clause['operator']} :{$clauseVar}";
				$this->data[$clauseVar] = $clause['value'];
			}

			$sql .= $clause['joiner'] ? " {$clause['joiner']} " : '';

			$sqlClauses .= $sql;
		}

		return "{$type} {$sqlClauses}";
	}

	private function buildFragmentGroup()
    {
		return ! empty($this->groupby)
			? 'GROUP BY ' . implode(', ', $this->groupby)
			: '';
	}

	private function buildFragmentOrder()
    {

		if (empty($this->order)) {
			return '';
		}

		$orders = array();

		foreach ($this->order as $order) {
			$orders[] = "{$order['field']} {$order['direction']}";
		}

		return 'ORDER BY ' . implode(', ', $orders);
	}

	private function buildFragmentLimit()
    {
		if (empty($this->limit)) {
			return '';
		}

		if (! is_null($this->limit['start'])) {
			return "LIMIT {$this->limit['start']}, {$this->limit['limit']}";
		}

		return "LIMIT {$this->limit['limit']}";
	}

	public function rowCount()
    {
		return $this->statement
			? $this->statement->rowCount()
			: false;
	}

	public function fetch($method = \PDO::FETCH_OBJ)
    {
		return $this->statement
			? $this->statement->fetch($method)
			: false;
	}


	public function fetchAll($method = \PDO::FETCH_OBJ)
    {
		return $this->statement
			? $this->statement->fetchAll($method)
			: false;
	}

	public function reset()
    {
		$this->select = array();
		$this->from   = array();
		$this->clause = array();
		$this->having = array();
		$this->groupby  = array();
		$this->order  = array();
		$this->limit  = array();
		$this->data   = array();
		$this->store  = array();
	}

	public function __set($variable, $value)
    {
		$this->store[$variable] = $value;
	}

	public function __get($field)
    {
		return isset($this->store[$field])
			? $this->store[$field]
			: false;
	}
}
