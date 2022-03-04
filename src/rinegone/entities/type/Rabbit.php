<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Rabbit extends Living {
    const NETWORK_ID = EntityLegacyIds::RABBIT;

    public $width = 0.4;
    public $height = 0.5;

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo($this->height, $this->width);
    }

    public function getName(): string{
        return "Rabbit";
    }

    public function initEntity(CompoundTag $nbt): void{
        $this->setMaxHealth(3);
        parent::initEntity($nbt);
    }

    public function getDrops(): array{
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $dmg = $cause->getDamager();
            if($dmg instanceof Player){

                $looting = $dmg->getInventory()->getItemInHand()->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::LOOTING));
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }
            }
        }
        if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
            return [
                ItemFactory::getInstance()->get(415, 0, 1 * $lootingL),
                ItemFactory::getInstance()->get(412, 0, 1 * $lootingL),
            ];
        }else{
            return [
                ItemFactory::getInstance()->get(415, 0, 1 * $lootingL),
                ItemFactory::getInstance()->get(411, 0, 1 * $lootingL),
            ];
        }
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::RABBIT;
    }
}