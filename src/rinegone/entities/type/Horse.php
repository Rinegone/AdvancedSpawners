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

class Horse extends Living {
    const NETWORK_ID = EntityLegacyIds::HORSE;

    public $height = 1.5;
    public $width = 1.3;

    public function getName(): string{
        return "Horse";
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
            ItemFactory::getInstance()->get(334, 0, mt_rand(1, 2) * $lootingL),
        ];
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::HORSE;
    }
}