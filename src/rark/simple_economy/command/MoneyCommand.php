<?php
declare(strict_types = 1);

namespace rark\simple_economy\command;

use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use rark\simple_economy\command\sub\AddSubCommand;
use rark\simple_economy\command\sub\GiveSubCommand;
use rark\simple_economy\command\sub\RankingSubCommand;
use rark\simple_economy\command\sub\ReduceSubCommand;
use rark\simple_economy\command\sub\SetSubCommand;
use rark\simple_economy\command\sub\ViewSubCommand;
use rark\simple_economy\libs\CortexPE\Commando\BaseCommand;

class MoneyCommand extends BaseCommand{

	public function __construct(PluginBase $plugin){
		parent::__construct($plugin, 'money', 'お金に関するあれこれ', ['m']);
		$this->setPermission('simple_economy.command.public');
	}

	protected function prepare():void{
		$this->registerSubCommand(new AddSubCommand);
		$this->registerSubCommand(new GiveSubCommand);
		$this->registerSubCommand(new RankingSubCommand);
		$this->registerSubCommand(new ReduceSubCommand);
		$this->registerSubCommand(new SetSubCommand);
		$this->registerSubCommand(new ViewSubCommand);
	}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		$usage = <<<USAGE
			[Usage]
			/money give <string:name> <int:amount>
			/money ranking
			/money View <string:name>
		USAGE;
		
		if(Server::getInstance()->isOp($sender->getName())){
			$usage .= <<<ADD
				/money set <string:name> <int:amount>
				/money add <string:name> <int:amount>
				/money reduce <string:name> <int:amount>
			ADD;
		}
		$sender->sendMessage($usage);
	}
}