<?php
declare(strict_types = 1);

namespace rark\simple_economy\event;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use rark\simple_economy\Account;

class SimpleEconomyEvent extends Event implements Cancellable{

	protected Account $account;
	protected bool $is_cancelled = false;

	public function __construct(Account $account){
		$this->account = $account;
	}

	public function getAccount():Account{
		return $this->account;
	}

	public function setCancelled(bool $cancelled){
		$this->isCancelled = $cancelled;
	}

	public function isCancelled():bool{
		return $this->is_cancelled;
	}
}