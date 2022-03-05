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

class Ocelot extends Living {
    const NETWORK_ID = EntityLegacyIds::OCELOT;

    const TYPE_WILD = 0;
    const TYPE_TUXEDO = 1;
    const TYPE_TABBY = 2;
    const TYPE_SIAMESE = 3;

    public $width = 0.6;
    public $height = 0.7;

    public function getName(): string{
        return "Ocelot";
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
            ItemFactory::getInstance()->get(349, 0, mt_rand(0, 1) * $lootingL),
        ];
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::OCELOT;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}