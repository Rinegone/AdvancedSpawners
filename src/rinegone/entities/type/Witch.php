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

class Witch extends Living {
    const NETWORK_ID = EntityLegacyIds::WITCH;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Witch";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo($this->height, $this->width);
    }

    public function initEntity(CompoundTag $nbt): void{
        $this->setMaxHealth(26);
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
        if(($rand = mt_rand(1, 11)) < 4){
            return [
                ItemFactory::getInstance()->get(331, 0, $lootingL),
            ];
        }elseif($rand < 7){
            return [
                ItemFactory::getInstance()->get(353, 0, $lootingL),
            ];
        }elseif($rand < 9){
            return [
                ItemFactory::getInstance()->get(348, 0, $lootingL),
            ];
        }else{
            return [];
        }
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::WITCH;
    }
}