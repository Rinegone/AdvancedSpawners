<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Pig extends Living {
    const NETWORK_ID = EntityLegacyIds::PIG;

    public $width = 0.9;
    public $height = 0.9;

    public function getName(): string{
        return "Pig";
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
        if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
            return [
                ItemFactory::getInstance()->get(320, 0, mt_rand(1, 3) * $lootingL),
            ];
        }
        return [
            ItemFactory::getInstance()->get(319, 0, mt_rand(1, 3) * $lootingL),
        ];
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::PIG;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}