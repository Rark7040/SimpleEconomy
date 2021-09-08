<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\player\Player;
use pocketmine\Server;

class Account{

	protected string $name;

	public function __construct($maybe_player){
		if($maybe_player instanceof Player){
			$this->name = $maybe_player->getName();

		}else{
			if(Server::getInstance()->getOfflinePlayer($maybe_player) === null){
				throw new \ErrorException('不正なアカウントが生成されました');
			}
			$this->name = $maybe_player;
		}
	}

	public function getName():string{
		return $this->name;
	}
}