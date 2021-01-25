<?php

namespace KurSkyTR;

use pocketmine\{
	Player,
	scheduler\Task,
	event\player\PlayerDeathEvent,
	event\entity\EntityLevelChangeEvent,
	event\entity\EntityDamageByEntityEvent,
	event\player\PlayerJoinEvent,
	event\player\PlayerQuitEvent,
	event\Listener,
	entity\Entity,
	command\CommandSender,
	command\Command,
	plugin\PluginBase,
	level\sound\ClickSound,
	utils\MainLogger as M,
	math\Vector3,
	item\Item
};

use FormAPI\{
	Form,
	SimpleForm
};
use KurSkyTR\Task\GameStartTask;

class OneVsOne extends PluginBase implements Listener{
	
	const PREFIX = "§bKur§fSky§c§lTR§r §7> §r";
	
	public function onJoin(PlayerJoinEvent $event){
		$e = $event->getPlayer();
		$e->setXpLevel(0);
		$e->setCurrentTotalXp(0);
	}
	
	public function onQuit(PlayerQuitEvent $event){
		$e = $event->getPlayer();
	}
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		M::getLogger()->notice("Eventler kaydedildi. Sunucu API uyumlu. Plugin aktif ediliyor...");
		M::getLogger()->info("Eklenti aktif edildi.");
	}
	
	public function onDeath(PlayerDeathEvent $event){
		$e = $event->getPlayer();
		$cause = $e->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				if($damager->getLevel()->getName() == "fistpe"){
					$this->getServer()->broadcastMessage("§a" . 					$damager->getName() . " §eZümrüt §abadlı haritada kazandı.");
					$damager->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
										$e->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
					$e->setImmobile(false);
					$e->setGamemode(0);
					$damager->setImmobile(false);
					$damager->setGamemode(0);
				}
			}
		}
	}
	
	public function onCommand(CommandSender $e, Command $kmt, string $lbl, array $args): bool{
		if($kmt->getName() == "1vs1" || $kmt->getName() == "onevsone"){
			if($e instanceof Player){
				$this->onevsone($e);
			}else{
				$e->sendMessage("§cBu komutu oyunda kullanabilirsin.");
			}
		}
		if($kmt->getName() == "ayril"){
			if($e instanceof Player){
				if($e->getLevel()->getName() == "fistpe"){
					$playercount = count($this->getServer()->getLevelByName("fistpe")->getPlayers());
					$e->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
					$e->sendMessage("§cOyundan ayrıldın!");
					$e->getInventory()->clearAll();
					$e->getArmorInventory()->clearAll();
					$e->setGamemode(0);
					$e->setImmobile(false);
					foreach($this->getServer()->getLevelByName("fistpe")->getPlayers() as $players){
					$players->sendMessage("§7[§aOyun§7] §b> §e" . $e->getName() . " §coyundan ayrıldı! §7[§a1 / §c2]");
					$players->setImmobile(false);	$players->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
					}
				}
			}
		}
		return true;
	}
	
	public function onevsone(Player $e){
		$f = new SimpleForm(function (Player $e, $data){
			if($data === null) return true;
			switch($data){
				case 0;
				break;
				case 1;
				$world = $this->getServer()->getLevelByName("fistpe");
				$playercount = count($world->getPlayers());
				if($playercount <= 0){
					if($e->getLevel()->getName() == "fistpe"){
						$e->sendMessage("§cZaten bu oyundasın!");
						return false;
					}
					$base = $world->getSafeSpawn();
					$e->teleport($base);
					$e->teleport(new Vector3($base->getX(), $base->getY(), $base->getZ()));
					$e->getInventory()->clearAll();
					$e->getArmorInventory()->clearAll();
					$inv = $e->getInventory();
					$armor = $e->getArmorInventory();
					$inv->addItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
					$inv->addItem(Item::get(Item::GOLDEN_APPLE, 0, 5));
					$inv->addItem(Item::get(Item::BOW, 0, 1));
					$inv->addItem(Item::get(Item::ARROW, 0, 12));
					$armor->setHelmet(Item::get(Item::DIAMOND_HELMET, 0, 1));
					$armor->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE, 0, 1));
					$armor->setLeggings(Item::get(Item::DIAMOND_LEGGINGS, 0, 1));
					$armor->setBoots(Item::get(Item::DIAMOND_BOOTS, 0, 1));
					$e->setImmobile(true);
					$e->setGamemode(2);
					foreach($world->getPlayers() as $players){
						$playercounts = count($this->getServer()->getLevelByName("fistpe")->getPlayers());
						$players->sendMessage("§7[§aOyun§7] §b> §e" . $e->getName() . " §aoyuna katıldı. §7[§a{$playercounts}§7 / §c2§7]");
					}
				} elseif($playercount <= 1){
					if($e->getLevel()->getName() == "fistpe"){
						$e->sendMessage("§cZaten bu oyundasın!");
						return false;
					}
					$base = $world->getSafeSpawn();
					$e->teleport($base);
					$e->teleport(new Vector3($base->getX() +30, $base->getY(), $base->getZ()));
					$e->getInventory()->clearAll();
					$e->getArmorInventory()->clearAll();
					$inv = $e->getInventory();
					$armor = $e->getArmorInventory();
					$inv->addItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
					$inv->addItem(Item::get(Item::GOLDEN_APPLE, 0, 5));
					$inv->addItem(Item::get(Item::BOW, 0, 1));
					$inv->addItem(Item::get(Item::ARROW, 0, 12));
					$armor->setHelmet(Item::get(Item::DIAMOND_HELMET, 0, 1));
					$armor->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE, 0, 1));
					$armor->setLeggings(Item::get(Item::DIAMOND_LEGGINGS, 0, 1));
					$armor->setBoots(Item::get(Item::DIAMOND_BOOTS, 0, 1));
					$e->setImmobile(true);
					$e->setGamemode(2);
					$this->getScheduler()->scheduleRepeatingTask(new GameStartTask($this), 20*1);
					foreach($world->getPlayers() as $players){
						$playercounts = count($this->getServer()->getLevelByName("fistpe")->getPlayers());
						$players->sendMessage("§7[§aOyun§7] §b> §e" . $e->getName() . " §aoyuna katıldı. §7[§a{$playercounts} §7/ §c2§7]");
						$players->sendMessage(self::PREFIX . "§bOyun §e10 saniye sonra başlayacak.");
				   	$players->getLevel()->addSound(new ClickSound($players));
					}
				} elseif($playercount >= 2){
					if($e->getLevel()->getName() == "fistpe"){
						$e->sendMessage("§cBu oyun dolu. Fakat sen zaten bu oyundasın.");
						return false;
					}
				}else{
					$e->sendMessage("§cBu oyun dolu!");
				}
				break;
			}
		});
		$this->getServer()->loadLevel("fistpe");
		$level = $this->getServer()->getLevelByName("fistpe");
		$playercount = count($level->getPlayers());
		$f->setTitle("1VS1");
		$f->setContent("§fArena seç ve katıl!");
		$f->addButton("§cKapat");
		$f->addButton("§6Zümrüt §7- " . $this->durum("fistpe"));
		$f->sendToPlayer($e);
	}
	
	public function durum(String $ad){
		$dunya = $this->getServer()->getLevelByName($ad);
		if(count($dunya->getPlayers()) <= 0){
			return "§f0 §7/ §c2\n§eBekleniyor!";
		} elseif(count($dunya->getPlayers()) <= 1){
			return "§f1 §7/ §c2\n§eBekleniyor!";
		} elseif(count($dunya->getPlayers()) >= 2){
			return "§c2 §7/ §c2\n§cOynanıyor!";
		}
	}
}