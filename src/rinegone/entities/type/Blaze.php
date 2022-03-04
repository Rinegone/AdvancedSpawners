<?php

namespace rinegone\entities\type;


use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Blaze extends Living {
    const NETWORK_ID = EntityLegacyIds::BLAZE;

    public static function getNetworkTypeId() : string{ return EntityIds::BLAZE; }


    public function getName(): string{
        return "Blaze";
    }

    public function getDrops(): array{
        return [
            ItemFactory::getInstance()->get(369, 0, mt_rand(0, 1)),
        ];
    }

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(1.8, 0.6);
    }
}