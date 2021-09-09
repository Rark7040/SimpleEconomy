<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventHandler implements Listener{

	public function onJoin(PlayerJoinEvent $ev):void{
		if(Account::findByName($ev->getPlayer()->getName()) === null){
			new Account($ev->getPlayer()->getName());
		}
	}
}