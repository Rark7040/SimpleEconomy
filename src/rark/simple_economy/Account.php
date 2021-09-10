<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use Exception;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Account{
	/** @var self[string] */
	protected static array $instances = [];
	protected string $name;

	public static function init():void{
		$conf = new Config(Main::getPluginDataPath().'Accounts.json', Config::JSON);

		foreach($conf->getAll() as $name => $true){
			try{
				self::$instances[$name] = new self($name);

			}catch(Exception){
				print_r(TextFormat::RED.$name.'のアカウントが復元できませんでした(考えられる原因: playersフォルダ上のデータ削除)');
			}
		}
	}

	public function __construct(string $name){
		if(!isset(self::$instances[$name])){
			if(Server::getInstance()->getOfflinePlayer($name) === null){
				throw new \ErrorException('不正なアカウントが生成されました');
			}
			$this->name = $name;
			self::$instances[$this->name] = $this;

		}else{
			$this->name = $name;
		}
	}

	public static function save():void{
		$conf = new Config(Main::getPluginDataPath().'Accounts.json', Config::JSON);
		$conf->setAll(array_combine(array_keys(self::$instances), array_fill(0, count(self::$instances), true)));
		$conf->save();
	}

	public static function findByName(string $name):?self{
		return isset(self::$instances[$name])? self::$instances[$name]: null;
	}

	public function getName():string{
		return $this->name;
	}
	
	/**
	 * @return string[]
	 */
	public static function getAllAccountNames():array{
		return array_keys(self::$instances);
	}
}