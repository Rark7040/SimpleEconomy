<?php
declare(strict_types = 1);

namespace rark\simple_economy\event;

use rark\simple_economy\Account;

class GiveMoneyEvent extends MoneyEvent{

	protected Account $given;

	public function __construct(Account $from, Account $to, int $amount, bool $is_executable){
		parent::__construct($from, $amount, $is_executable);
		$this->given = $to;
		$this->amount = $amount;
	}

	public function getGiven():Account{
		return $this->given;
	}

}