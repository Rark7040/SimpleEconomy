<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\Server;

class Account{
	/** @var self[string] */
	protected static array $instances = [];
	protected string $name;

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

	public function getName():string{
		return $this->name;
	}
}