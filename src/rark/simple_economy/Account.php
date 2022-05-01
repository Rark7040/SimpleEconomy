<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\player\Player;
use pocketmine\player\XboxLivePlayerInfo;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Account{
	/** @var array<string, self> */
	protected static array $instances = [];
	protected string $name;
	protected string $xuid;

	public static function init():void{
		$conf = new Config(Main::getPluginDataPath().'Accounts.json', Config::JSON);

		foreach($conf->getAll() as $name => $xuid){
			try{
				self::$instances[$name] = new self($name, $xuid);

			}catch(\Exception){
				Server::getInstance()->getLogger()->error(TextFormat::RED.$name.'のアカウントが復元できませんでした(考えられる原因: playersフォルダ上のデータ削除)');
			}
		}
	}

	public function __construct(string $name, string $xuid){
		$this->xuid = $xuid;
		$name = strtolower($name);

		if(!isset(self::$instances[$name])){
			if(Server::getInstance()->getOfflinePlayer($name) === null){
				throw new \RuntimeException('不正なアカウントが生成されました');
			}
			$this->name = $name;
			self::$instances[$this->name] = $this;

		}else{
			$this->name = $name;
		}
	}

	public static function create(Player $player):?Account{
		$info = $player->getNetworkSession()->getPlayerInfo();

		if(!$info instanceof XboxLivePlayerInfo) return null;
		return new self(strtolower($player->getName()), $info->getXuid());
	}

	public static function save():void{
		$conf = new Config(Main::getPluginDataPath().'Accounts.json', Config::JSON);
		$conf_data = [];

		foreach(self::$instances as $account){
			$conf_data[$account->getName()] = $account->getXuid();
		}
		$conf->setAll($conf_data);
		$conf->save();
	}

	public static function findByName(string $name):?self{
		$name = strtolower($name);

		if(isset(self::$instances[$name])) return self::$instances[$name];
		$player = Server::getInstance()->getPlayerByPrefix($name);

		if($player === null) return null;
		$accurate_name = strtolower($player->getName());
		return isset(self::$instances[$accurate_name])? self::$instances[$accurate_name]: self::create($player);
	}

	public function getName():string{
		return $this->name;
	}

	public function getXuid():string{
		return $this->xuid;
	}
	
	/**
	 * @return string[]
	 */
	public static function getAllAccountNames():array{
		return array_keys(self::$instances);
	}
}