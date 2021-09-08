<?php
declare(strict_types = 1);

namespace rark\simple_economy\event;

use rark\simple_economy\Account;

class AddMoneyEvent extends MoneyEvent{

	public function __construct(Account $account, int $amount, bool $is_executable){
		parent::__construct($account, $amount, $is_executable);
		$this->amount = $amount;
	}
}