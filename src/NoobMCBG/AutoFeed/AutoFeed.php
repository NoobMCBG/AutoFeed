<?php

namespace NoobMCBG\AutoFeed;

use pocketmine\player\Player;
use pocketmine\event\listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\utils\Config;

class AutoFeed extends PluginBase implements listener {

	public static $instance;

	public static function getInstance() : self {
		return self::$instance;
	}

	public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->autofeed = new Config($this->getDataFolder() . "autofeed.yml", Config::YAML);
	}

	public function setAutoFeed($player){
		if(!$player instanceof Player){
             return true;
		}
		$this->autofeed->set(strtolower($player->getName()), true);
		$this->autofeed->save();
	}

	public function unsetAutoFeed($player){
		if(!$player instanceof Player){
             return true;
		}
		$this->autofeed->set(strtolower($player->getName()), false);
		$this->autofeed->save();
	}

	public function onMove(PlayerMoveEvent $ev){
        $player = $ev->getPlayer();
        if($this->autofeed->get(strtolower($player->getName())) == true) $player->getHungerManager()->setFood(20);
	}

	public function onHunger(PlayerExhaustEvent $ev){
		if($this->getConfig()->get("anti-hunger") == true) $ev->cancel();
	}
}