<?php

namespace KurSkyTR\Task;

use pocketmine\{
	level\sound\ClickSound,
	level\sound\GhastShootSound,
	scheduler\Task,
	Player,
	Server
};
use KurSkyTR\OneVsOne;

class GameStartTask extends Task {
	
	public $timer = 11;
	
	const PREFIX = "§bKur§fSky§c§lTR§r §7> §r";
	
	public function __construct(OneVsOne $plugin){
		$this->pl = $plugin;
	}
	
	public function onRun($tick){
		$this->timer--;
		$sw = Server::getInstance();
		foreach($sw->getLevelByName("fistpe")->getPlayers() as $players){
			$playercount = $this->pl->getServer()->getLevelByName("fistpe")->getPlayers();
			if(count($playercount) >= 1){
				$players->setGamemode(2);
				$players->setImmobile(true);
		  }else{
		  	$players->sendMessage("§cOyuncu maça bağlanamadı.");
		  	$players->teleport($this->pl->getServer()->getDefaultLevel()->getSafeSpawn());
		  	$players->setImmobile(false);
				$players->setXpLevel(0);
				$players->setCurrentTotalXp(0);
				$players->setGamemode(0);
		  }
		}
		if($this->timer <= 10 and $this->timer > 5){
			foreach($sw->getLevelByName("fistpe")->getPlayers() as $players){
				$playercount = $this->pl->getServer()->getLevelByName("fistpe")->getPlayers();
				if(!count($playercount) >= 1){
					$players->teleport($sw->getDefaultLevel()->getSafeSpawn());
					$players->setImmobile(false);
					$players->setXpLevel(0);
					$players->setCurrentTotalXp(0);
					$players->setGamemode(0);
				}else{
					$players->setXpLevel($this->timer);
					$players->setCurrentTotalXp(0);
					$players->setImmobile(true);
					$players->setGamemode(2);
				}
			}
		}
		if($this->timer < 6 and $this->timer > 0){
			foreach($sw->getLevelByName("fistpe")->getPlayers() as $players){
				$playercount = $this->pl->getServer()->getLevelByName("fistpe")->getPlayers();
				if(!count($playercount) >= 1){
					$players->setImmobile(false);
					$players->setXpLevel(0);
					$players->setCurrentTotalXp(0);
					$players->setGamemode(0);
					$players->teleport($sw->getDefaultLevel()->getSafeSpawn());
				}else{
					$players->setXpLevel($this->timer);
					$players->getLevel()->addSound(new ClickSound($players));
					$players->sendMessage(self::PREFIX . "§bOyunun başlamasına §e" . $this->timer . " §bsaniye!");
					$players->setXpLevel($this->timer);
					$players->setCurrentTotalXp(0);
					$players->setImmobile(true);
					$players->setGamemode(2);
				}
			}
		}
		if($this->timer == 0){
			foreach($sw->getLevelByName("fistpe")->getPlayers() as $players){
				$playercount = $this->pl->getServer()->getLevelByName("fistpe")->getPlayers();
				if(!count($playercount) >= 1){
					$players->setImmobile(false);
					$players->sendMessage("§cOyuncu maça bağlanamadı.");
					$players->teleport($sw->getDefaultLevel()->getSafeSpawn());
				}else{
					$players->sendMessage(self::PREFIX . "§aOyun başladı. İyi şanslar.");
					$players->getLevel()->addSound(new GhastShootSound($players));
					$players->setXpLevel(0);
					$players->setImmobile(false);
					$this->pl->getScheduler()->cancelTask($this->getTaskId());
				}
			}
		}
 	}
}