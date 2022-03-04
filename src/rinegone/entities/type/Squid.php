<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Squid as PMSquid;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Squid extends Living {
    const NETWORK_ID = EntityLegacyIds::SQUID;
    private $height = 0.95;
    private $width = 0.95;

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
            ItemFactory::getInstance()->get(351, 0, mt_rand(1, 3) * $lootingL),
        ];
    }

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo($this->height, $this->width);
    }


    public static function getNetworkTypeId(): string
    {
        return EntityIds::SQUID;
    }

    public function getName(): string
    {
        return "Squid";
    }
}