<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class IronGolem extends Living
{

    public const NETWORK_ID = EntityLegacyIds::IRON_GOLEM;

    public function getName(): string
    {
        return "Iron Golem";
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
        if(mt_rand(0, 5) == 0) {
            return [
                ItemFactory::getInstance()->get(ItemIds::IRON_INGOT, 0, 3 * $lootingL),
                ItemFactory::getInstance()->get(ItemIds::RED_FLOWER, 0, 1 * $lootingL)
            ];
        } else {
            return [
                ItemFactory::getInstance()->get(ItemIds::IRON_INGOT, 0, 3 * $lootingL),
            ];
        }
    }


    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(2.7, 1.4);
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::IRON_GOLEM;
    }
}