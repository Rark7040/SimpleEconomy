<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use InvalidArgumentException;
use pocketmine\utils\Config;

class Economy{
	/** @var Money[string] */
	protected static array $currencies = [];

	public static function init(Config $conf){
		$setting = $conf->getAll();

		if(!isset($setting['currencies'])) throw new InvalidArgumentException('currencies キーが存在しません');
		foreach($setting['currencies'] as $name => $data){
		}
	}

	public static function findByName(string $name):?Money{
		return isset(self::$currencies[$name])? self::$currencies[$name]: null;
	}

	public function __destruct(){
		foreach(self::$currencies as $money) $money->save();
	}
}