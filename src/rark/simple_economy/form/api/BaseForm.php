<?php

declare(strict_types = 1);

namespace rark\simple_economy\form\api;

use pocketmine\form\Form;
use pocketmine\player\Player;

abstract class BaseForm implements Form{

	public const SIMPLE = 'form';
	public const MODAL = 'modal';
	public const CUSTOM = 'custom_form';

	protected string $type;
	protected array $contents = [];
	public string $title = '';
	public string $label = '';
	public callable $submit;
	public callable $cancelled;
	public callable $illegal;

	public function reciveIllegalData(Player $player):void{
		if(!is_callable($this->illegal)) return;
		($this->illegal)($player);
	}

	public function onCancelled(Player $player):void{
		if(!is_callable($this->cancelled)) return;
		($this->cancelled)($player);
	}

	public function onSubmit(Player $player, $data):void{
		if(!is_callable($this->submit)) return;
		($this->submit)($player, $data);
	}


	final public function handleResponse(Player $player, $data):void{
		if($data === null){
			$this->onCancelled($player);
			return;
		}
		switch(true){
			case $this instanceof SimpleForm:
				if(!is_int($data)){
					$this->reciveIllegalData($player, $data);
					return;
				}
				break;

			case $this instanceof ModalForm:
				if(!is_bool($data)){
					$this->reciveIllegalData($player, $data);
					return;
				}
				break;

			case $this instanceof CustomForm:
				if(count($this->contents) !== count($data)){
					$this->reciveIllegalData($player, $data);
					return;
				}
				$data = array_combine(array_keys($this->contents), $data);
				break;

			default: throw new \ErrorException('BaseFormはSimpleForm、ModalForm、CustomForm以外で継承することは出来ません');
		}
		$this->onSubmit($player, $data);
	}

	final public function jsonSerialize(){
		switch(true){
			case $this instanceof SimpleForm:
				$type = self::SIMPLE;
				break;
			case $this instanceof ModalForm:
				$type = self::MODAL;
				break;
			case $this instanceof CustomForm:
				$type = self::CUSTOM;
				break;
			default: throw new \ErrorException('BaseFormはSimpleForm、ModalForm、CustomForm以外で継承することは出来ません');
		}
		$json = [
			'type' => $type,
			'title' => $this->title
		];

		if($type === self::CUSTOM){
			$json['content'] = array_values($this->contents);

		}else{
			$json['content'] = $this->label;

			if($type === self::SIMPLE){
				$json['buttons'] = $this->contents;

			}else{
				$json['button1'] = $this->contents[0];
				$json['botton2'] = $this->contents[1];
			}
		}
		return $json;
	}
}