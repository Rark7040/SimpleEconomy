<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class Money extends Config{

	protected string $name;
	protected string $prefix;
	protected bool $prefix_pos;
	protected int $default;

	public function __construct(string $name, string $prefix, bool $prefix_pos, int $default){
		$this->name = $name;
		$this->prefix = $prefix;
		$this->prefix_pos = $prefix_pos;
		parent::__construct(Main::getPluginDataPath().$name.'json', Config::JSON);
	}

	public function __destruct(){
		$this->save();
	}

	public function getName():string{
		return $this->name;
	}

	public function getPrefix():string{
		return $this->prefix;
	}

	public function getDefault():int{
		return $this->default;
	}

	public function getNumberFormat(int $amount):string{
		return $this->prefix_pos?
			$this->prefix.number_format($amount):
			number_format($amount).$this->prefix;
	}

	public function setMoney(Account $account, int $amount):void{
		$this->set($account->getName(), $amount);
	}

	public function getMoney(Account $account):int{
		return $this->get($account->getName(), null)?? $this->default;
	}

	public function addMoney(Account $account, int $amount):bool{
		if(!$this->canAddMoney($account, $amount)) return false;
		$this->setMoney($account, $this->getMoney($account)+$amount);
		return true;
	}

	public function reduceMoney(Account $account, int $amount):bool{
		if(!$this->canReduceMoney($account, $amount)) return false;
		$this->setMoney($account, $this->getMoney($account)-$amount);
		return true;
	}

	public function giveMoney(Account $from, Account $to, int $amount):bool{
		if(!$this->canAddMoney($to, $amount) or !$this->canReduceMoney($from, $amount)) return false;
		$this->addMoney($to, $amount);
		$this->reduceMoney($from, $amount);
		return true;
	}

	public function canAddMoney(Account $account, int $amount):bool{
		return $this->getMoney($account)+$amount < PHP_INT_MAX;
	}

	public function canReduceMoney(Account $account, int $amount):bool{
		return $this->getMoney($account)-$amount > -1;
	}
}