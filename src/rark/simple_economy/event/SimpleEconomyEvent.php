<?php
declare(strict_types = 1);

namespace rark\simple_economy\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use rark\simple_economy\Account;

class SimpleEconomyEvent extends Event implements Cancellable{
	use CancellableTrait;

	protected Account $account;

	public function __construct(Account $account){
		$this->account = $account;
	}

	public function getAccount():Account{
		return $this->account;
	}
}