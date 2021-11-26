<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\utils\Config;
use rark\simple_economy\event\AddMoneyEvent;
use rark\simple_economy\event\GiveMoneyEvent;
use rark\simple_economy\event\ReduceMoneyEvent;

class Money extends Config{
	const KEY_VALID = 'valid';
	const KEY_TOTAL = 'total';

	protected string $name;
	protected string $prefix;
	protected bool $prefix_pos;
	protected int $default;

	public function __construct(string $name, string $prefix, bool $prefix_pos, int $default){
		parent::__construct(Main::getPluginDataPath().$name.'.json', Config::JSON);
		$this->name = $name;
		$this->prefix = $prefix;
		$this->prefix_pos = $prefix_pos;
		$this->default = $default;
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

	public function getFormatted(int $amount):string{
		return $this->prefix_pos?
			$this->prefix.number_format($amount):
			number_format($amount).$this->prefix;
	}

	public function setMoney(Account $account, int $amount):void{
		$old = $this->get(self::KEY_VALID, []);
		$old[$account->getXuid()] = $amount;
		$this->set(self::KEY_VALID, $old);
	}

	public function getMoney(Account $account):int{
		$old = $this->get(self::KEY_VALID, []);
		return isset($old[$account->getXuid()])? $old[$account->getXuid()]: $this->default;
	}

	public function addMoney(Account $account, int $amount):bool{
		$ev = new AddMoneyEvent($account, $amount, $this->canAddMoney($account, $amount));
		$ev->call();
		$amount = $ev->getAmount();

		if(!$this->canAddMoney($account, $amount) or $ev->isCancelled()) return false;
		$old = $this->get(self::KEY_TOTAL, []);

		if(!isset($old[$account->getXuid()])) $old[$account->getXuid()] = 0;
		$old[$account->getXuid()] += $amount;
		$this->set(self::KEY_TOTAL, $old);
		$this->setMoney($account, $this->getMoney($account)+$amount);
		return true;
	}

	public function getTotalMoney(Account $account):int{
		$old = $this->get(self::KEY_TOTAL, []);
		return isset($old[$account->getXuid()])? $old[$account]: $this->default;
	}

	public function reduceMoney(Account $account, int $amount):bool{
		$ev = new ReduceMoneyEvent($account, $amount, $this->canReduceMoney($account, $amount));
		$ev->call();
		$amount = $ev->getAmount();

		if(!$this->canReduceMoney($account, $amount) or $ev->isCancelled()) return false;
		$this->setMoney($account, $this->getMoney($account)-$amount);
		return true;
	}

	public function giveMoney(Account $from, Account $to, int $amount):bool{
		$ev = new GiveMoneyEvent($from, $to, $amount, !$this->canAddMoney($to, $amount) or !$this->canReduceMoney($from, $amount));
		$ev->call();
		$amount = $ev->getAmount();

		if($ev->isCancelled() or (!$this->canAddMoney($to, $amount) or !$this->canReduceMoney($from, $amount))) return false;
		$this->addMoney($to, $amount);
		$this->reduceMoney($from, $amount);
		return true;
	}

	public function canAddMoney(Account $account, int $amount):bool{
		return $this->getMoney($account)+$amount <= 100_0000;
	}

	public function canReduceMoney(Account $account, int $amount):bool{
		return $this->getMoney($account) >= $amount;
	}
}