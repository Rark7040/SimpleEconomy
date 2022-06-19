# SimpleEconomy
#### PocketMine4.0向けの経済プラグイン
<br>
ジョブなどの生活鯖で使用できる機能はその他のプロジェクトで忙しいため、<br>
経済APIだけのリリースです。<br>
<br>

## コマンド

コマンドは全て /money /mから操作できます

#### サブコマンド

| サブコマンド | パーミッション | 用途 |
| ---- | ---- | ---- |
| give | 全員 | 対象に自分の所持金からお金を渡す |
| view | 全員 | 対象の所持金を確認する |
| add | opのみ | 対象の所持金を任意の値増加させる |
| reduce | opのみ | 対象の所持金を任意の値減少させる | 
| set | opのみ | 対象の所持金を任意の値に設定する |

<br>


## コンフィグ
ここから通貨の種類を追加できます

```yml
'currencies':
  'yen': #通貨の名前
    'prefix': '￥'
    #true => 数字の先頭  $1,000
    #false => 数字の後ろ 1,000$
    'prefix_position': true
    'default': 1000 #最初にプレイヤーが持っている所持金
    
  'dollar': 
    'prefix': '$'
    'prefix_position': true
    'default': 1000
```

## 開発者向け
使い方
```php
<?php
use rark\simple_economy\Account;
use rark\simple_economy\Economy;

/** @var pocketmine\player\Player */
$player;
$account = Account::findByName($player->getName())?? new Account($player->getName());
$money = Economy::getInstance('yen');

if($money === null) return;
//所持金を設定
$money->setMoney($account, 1000);

//所持金を取得
$amount = $money->getMoney($account);

//フォーマットを整えた形の所持金を取得
$player->sendMessage($money->getFormattedAmount($amount));

```