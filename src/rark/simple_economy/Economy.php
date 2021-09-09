<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use InvalidArgumentException;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Economy{
	/** @var Money[string] */
	protected static array $currencies = [];

	public static function init(Config $conf){
		$setting = $conf->getAll();

		if(!isset($setting['currencies'])) throw new InvalidArgumentException('currencies キーが存在しません');
		foreach($setting['currencies'] as $name => $data){
			if(!isset($data['prefix']) or !isset($data['prefix_position']) or !isset($data['default'])){
				print_r(TextFormat::RED.'必要なキーが存在しない為,'.$name.'を正しく生成できませんでした');
				continue;
			}
			self::$currencies[$name] = new Money($name, $data['prefix'], $data['prefix_position'], $data['default']);
		}
	}

	public static function registerCurrency(Money $money):void{
		self::$currencies[$money->getName()] = $money;
	}

	public static function getInstance(string $name):?Money{
		return isset(self::$currencies[$name])? self::$currencies[$name]: null;
	}

	public static function getAllMoneyNames():array{
		return array_keys(self::$currencies);
	}

	public static function save(){
		foreach(self::$currencies as $money) $money->save();
	}
}