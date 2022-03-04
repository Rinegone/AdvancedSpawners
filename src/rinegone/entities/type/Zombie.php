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

class Zombie extends Living {
    public const NETWORK_ID = EntityLegacyIds::ZOMBIE;

    public $width = 0.6;
    public $height = 1.8;

    public function getName(): string{
        return "Zombie";
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
        if(\mt_rand(0, 199) < 5){
            switch(\mt_rand(0, 2)){
                case 0:
                    return [
                        ItemFactory::getInstance()->get(265, 0, $lootingL),
                        ItemFactory::getInstance()->get(367, 0, mt_rand(0, 2) * $lootingL),
                    ];
                case 1:
                    return [
                        ItemFactory::getInstance()->get(391, 0, $lootingL),
                        ItemFactory::getInstance()->get(367, 0, mt_rand(0, 2) * $lootingL),
                    ];
                case 2:
                    return [
                        ItemFactory::getInstance()->get(392, 0, $lootingL),
                        ItemFactory::getInstance()->get(367, 0, mt_rand(0, 2), $lootingL),
                    ];
            }
        }

        return [
            ItemFactory::getInstance()->get(367, 0, mt_rand(0, 2)),
        ];
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::ZOMBIE;
    }
}