<?php

namespace rinegone\entities\type;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Guardian extends Living {
    const NETWORK_ID = EntityLegacyIds::GUARDIAN;

    public $height = 0.85;
    public $width = 0.85;

    public function getName(): string{
        return "Guardian";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo($this->height, $this->width);
    }

    public function initEntity(CompoundTag $nbt): void{
        $this->setMaxHealth(30);
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
        if(mt_rand(1, 15) < 2){
            return [
                ItemFactory::getInstance()->get(349, 0, mt_rand(1, 2) * $lootingL),
                ItemFactory::getInstance()->get(409, 0, mt_rand(1, 3) * $lootingL),
            ];
        }
        return [
            ItemFactory::getInstance()->get(349, 0, mt_rand(1, 2) * $lootingL),
            ItemFactory::getInstance()->get(409, 0, mt_rand(0, 1) * $lootingL),
        ];
    }


    public static function getNetworkTypeId(): string
    {
        return EntityIds::GUARDIAN;
    }
}