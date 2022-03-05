<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class ZombiePigman extends Living {
    const NETWORK_ID = EntityLegacyIds::ZOMBIE_PIGMAN;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Zombie Pigman";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo($this->height, $this->width);
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
        return [
            ItemFactory::getInstance()->get(ItemIds::GOLD_NUGGET, 0, mt_rand(1, 2) * $lootingL),
            ItemFactory::getInstance()->get(ItemIds::ROTTEN_FLESH, 0, mt_rand(0, 1) * $lootingL),
        ];
    }


    public static function getNetworkTypeId(): string{
        return EntityIds::ZOMBIE_PIGMAN;
    }

    public function getXpDropAmount(): int{
        return 5;
    }

}