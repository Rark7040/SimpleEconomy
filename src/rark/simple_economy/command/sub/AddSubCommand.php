<?php
declare(strict_types = 1);

namespace rark\simple_economy\command\sub;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use rark\simple_economy\form\MoneyForms;
use rark\simple_economy\libs\CortexPE\Commando\BaseSubCommand;

class AddSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct('Add', '所持金を任意の値増加させます');
		$this->setPermission('simple_economy.command.op');
	}

	protected function prepare():void{
		
	}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED.'ゲーム内で実行してください');
			return;
		}

		if(!Server::getInstance()->isOp($sender->getName())){
			$sender->sendMessage(TextFormat::RED.'実行権限がありません');
			return;
		}
		$sender->sendForm(MoneyForms::getAddForm());
	}
}