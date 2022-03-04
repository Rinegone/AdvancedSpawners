<?php

namespace rinegone\entities;

use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Living;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use rinegone\utils\FileManager;

class EntityManager {

    public function __construct() {
        FileManager::callDirectory("entities/type", function (string $entity) {
            $this->register($entity);
        });
    }

    private function register(string $class) {
        EntityFactory::getInstance()->register($class, function (World $world, CompoundTag $nbt) use ($class): Entity {
            return new $class(EntityDataHelper::parseLocation($nbt, $world));
        }, [$class]);
    }
}