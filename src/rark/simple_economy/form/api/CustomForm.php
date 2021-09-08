<?php

declare(strict_types = 1);

namespace rark\simple_economy\form\api;


class CustomForm extends BaseForm{

	public function addDropdown(string $id, string $text = '', int $default = 0, string ...$options):void{
		$this->contents[$id] = [
			'type' => 'dropdown',
			'text' => $text,
			'options' => $options,
			'default' => $default
		];
	}

	public function addInput(string $id, string $text = '', string $placeholder = '', string $default = ''):void{
		$this->contents[$id] =[
			'type' => 'input',
			'text' => $text,
			'placeholder' => $placeholder,
			'default' => $default
		];
	}

	public function addLabel(string $id, string $text = ''):void{
		$this->contents[$id] = [
			'type' => 'label',
			'text' => $text
		];
	}

	public function addSlider(string $id, string $text = '', int $min = 0, int $max = 0, ?int $default = null):void{
		$default?? $default = $min;
		$this->contents[$id] = [
			'type' => 'slider',
			'text' => $text,
			'min' => $min,
			'max' => $max,
			'default' => $default
		];
	}

	public function addStepSlider(string $id, string $text = '', int $default = 0, string ...$steps):void{
		$this->contents[$id] = [
			'type' => 'step_slider',
			'text' => $text,
			'steps' => $steps,
			'default' => $default
		];
	}

	public function addToggle(string $id, string $text = '', bool $default = false):void{
		$this->contents[$id] = [
			'type' => 'toggle',
			'text' => $text,
			'default' => $default
		];
	}
}