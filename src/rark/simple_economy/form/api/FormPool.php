<?php

declare(strict_types = 1);

namespace rark\simple_economy\form\api;

use pocketmine\player\Player;


abstract class FormPool{

	private static array $cash = [];

	public static function clear():void{
		self::$cash = [];
	}

	public static function register(string $id, BaseForm $form):void{
		self::$cash[$id] = $form;
	}

	public static function isRegistered(string $id):bool{
		return isset(static::$cash[$id]);
	}

	public static function send(string $id, Player $player):bool{
		if(!static::isRegistered($id)) return false;
		$form = self::$cash[$id];
		$form->send($player);
	}
}