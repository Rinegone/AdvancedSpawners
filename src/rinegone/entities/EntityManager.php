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

    public static function getEntityNameFromID(int $entityID): string
    {
        $names = [
            EntityIds::BAT => "Bat",
            EntityIds::BLAZE => "Blaze",
            EntityIds::CAVE_SPIDER => "Cave Spider",
            EntityIds::CHICKEN => "Chicken",
            EntityIds::COW => "Cow",
            EntityIds::CREEPER => "Creeper",
            EntityIds::DOLPHIN => "Dolphin",
            EntityIds::DONKEY => "Donkey",
            EntityIds::ELDER_GUARDIAN => "Elder Guardian",
            EntityIds::ENDERMAN => "Enderman",
            EntityIds::ENDERMITE => "Endermite",
            EntityIds::GHAST => "Ghast",
            EntityIds::GUARDIAN => "Guardian",
            EntityIds::HORSE => "Horse",
            EntityIds::HUSK => "Husk",
            EntityIds::IRON_GOLEM => "Iron Golem",
            EntityIds::LLAMA => "Llama",
            EntityIds::MAGMA_CUBE => "Magma Cube",
            EntityIds::MOOSHROOM => "Mooshroom",
            EntityIds::MULE => "Mule",
            EntityIds::OCELOT => "Ocelot",
            EntityIds::PANDA => "Panda",
            EntityIds::PARROT => "Parrot",
            EntityIds::PHANTOM => "Phantom",
            EntityIds::PIG => "Pig",
            EntityIds::POLAR_BEAR => "Polar Bear",
            EntityIds::RABBIT => "Rabbit",
            EntityIds::SHEEP => "Sheep",
            EntityIds::SHULKER => "Shulker",
            EntityIds::SILVERFISH => "Silverfish",
            EntityIds::SKELETON => "Skeleton",
            EntityIds::SKELETON_HORSE => "Skeleton Horse",
            EntityIds::SLIME => "Slime",
            EntityIds::SNOW_GOLEM => "Snow Golem",
            EntityIds::SPIDER => "Spider",
            EntityIds::SQUID => "Squid",
            EntityIds::STRAY => "Stray",
            EntityIds::VEX => "Vex",
            EntityIds::VILLAGER => "Villager",
            EntityIds::VINDICATOR => "Vindicator",
            EntityIds::WITCH => "Witch",
            EntityIds::WITHER_SKELETON => "Wither Skeleton",
            EntityIds::WOLF => "Wolf",
            EntityIds::ZOMBIE => "Zombie",
            EntityIds::ZOMBIE_HORSE => "Zombie Horse",
            EntityIds::ZOMBIE_PIGMAN => "Zombie Pigman",
            EntityIds::ZOMBIE_VILLAGER => "Zombie Villager",
        ];

        return $names[$entityID] ?? "Monster";
    }

}