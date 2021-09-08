<?php
declare(strict_types = 1);

namespace rark\simple_economy\command\sub;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\libs\CortexPE\Commando\BaseSubCommand;

class RankingSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct('ranking', 'お金の保有量のランキングを表示します');
		$this->setPermission('simple_economy.command.public');
	}

	protected function prepare():void{
	}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED.'ゲーム内で実行してください');
			return;
		}
		//$sender->sendForm();
	}
}