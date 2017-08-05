<?
namespace Volex\GruppenAttribute;

use Volex\GruppenAttribute;

class ApiStructure {
	protected $fields = [
		'entity' => [
			'require' => true,
			'type' => 'string'
		],
		'method' => [
			'require' => true,
			'type' => 'string'
		],
		'input_data' => [
			'require' => false,
			'type' => 'json'
		]
	];

	protected $basic_methods = [
		'getAll',
		'getById',
		'add',
		'delete',
		'update',
	];

	protected $entities = [
		'iblocks'		=> [
			'getAll',
			'getUsed',
			'getUnused'
		],
		'groups'			=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		],
		'group'			=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		],
		'sections'		=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		],
		'section'		=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		],
		'properties'	=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		],
		'property'		=> [
			'getAll',
			'getById',
			'add',
			'delete',
			'update',
		]
	];

	public function getEnteties() {
		return $this->entities;
	}

	public function getBasicMethods() {
		return $this->basic_methods;
	}

	public function getFields() {
		return $this->fields;
	}

	public function isMethodExists($method, $entity) {
		return isset($this->entities[$entity][$method]);
	}

	public function isEntityExists($entity) {
		return isset($this->entities[$entity]);
	}


	public function isFieldExists($field) {
		return isset($this->fields[$field]);
	}
}
