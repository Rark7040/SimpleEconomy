<?php
declare(strict_types = 1);

namespace rark\simple_economy\form\api;

use pocketmine\form\Form;
use pocketmine\player\Player;

trait BackableTrait{
	private ?Form $before = null;

	public function putBefore(Form $before):void{
		$this->before = $before;
	}

	public function back(Player $player):void{
		if($this->before == null) return;
		sendForm($player, $this->before);
	}
}