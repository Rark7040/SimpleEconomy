<?php
declare(strict_types = 1);

namespace rark\simple_economy\event;

use rark\simple_economy\Account;

class MoneyEvent extends SimpleEconomyEvent{

	protected int $amount;
	protected bool $is_executable;

	public function __construct(Account $account, int $amount, bool $is_executable){
		parent::__construct($account);
		$this->amount = $amount;
		$this->is_executable = $is_executable;
	}

	public function getAmount():int{
		return $this->amount;
	}

	public function setAmount(int $amount):void{
		$this->amount = $amount;
	}

	public function isExecutable():bool{
		return $this->is_executable;
	}
}