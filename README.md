# SimpleEconomy

pm4.0 plugin
i18nはしばらくしない予定


## 開発者向け
使い方
```php
<?php

use rark\simple_economy\Economy;
/** @var pocketmine\player\Player */
$player;
$account = Account::getInstance($player->getName());
$money = Economy::findByName('yen');

if($money === null) return;

//所持金を設定
$money->setMoney($account, 1000);

//所持金を取得
$amount = $money->getMoney($account);

//フォーマットを整えた形の所持金を取得
$player->sendMessage($money->getFormattedAmount($amount));

```