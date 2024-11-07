<?php
namespace Core\Db;

class DbParam
{
	public string $name;
	public string  $value;
	public string  $type;

	/**
	 * @param string $name
	 * @param string $value
	 * @param string $type
	 */
	public function __construct(string $name, string $value, string $type)
	{
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
	}

}