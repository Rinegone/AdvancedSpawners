<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class CaveSpider extends Living
{
    const NETWORK_ID = EntityLegacyIds::CAVE_SPIDER;


    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.5, 1);
    }

    public function getName(): string
    {
        return "Cave Spider";
    }

    public function getDrops(): array
    {
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
        if (mt_rand(1, 3) == 2) {
            return [
                ItemFactory::getInstance()->get(375, 0, mt_rand(0, 1) * $lootingL),
                ItemFactory::getInstance()->get(287, 0, mt_rand(0, 1) * $lootingL),
            ];
        } else {
            return [
                ItemFactory::getInstance()->get(287, 0, mt_rand(0, 1) * $lootingL),
            ];
        }
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::CAVE_SPIDER;
    }
}