<?php

namespace rinegone\entities;

use Closure;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Living;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use rinegone\Loader;
use rinegone\utils\FileManager;
use const pocketmine\BEDROCK_DATA_PATH;

class EntityManager {

    private array $entities;
    private array $entityIdMap;

    public function __construct() {
        $this->entityIdMap = json_decode(file_get_contents(BEDROCK_DATA_PATH . "/entity_id_map.json"), true);
    }

    public function initialize() {
        FileManager::callDirectory("entities/type", function (string $entity) {
            /* @var Living $entity */
            $this->registerEntity($entity, [$entity::getNetworkTypeId()]);
            Loader::getInstance()->getLogger()->info("$entity was successfully registered.");
        });
    }

    public function registerEntity(string $namespace, array $saveNames = [], ?Closure $closure = null): void{
        EntityFactory::getInstance()->register($namespace, $closure ?: function(World $world, CompoundTag $nbt)use($namespace): Entity{
            return new $namespace(EntityDataHelper::parseLocation($nbt, $world));
        }, $saveNames);

        /** @var Living $namespace */
        $name = $namespace::getNetworkTypeId();
        $displayName = [];
        foreach(explode("_", $name) as $value){
            $displayName[] = ucfirst(strtolower($value));
        }
        $displayName = implode(" ", $displayName);
        $legacyId = $legacyId ?? $this->entityIdMap[$name];
        $this->entities[$legacyId] = [$displayName, ucfirst(strtolower(str_replace("Minecraft:", "", $displayName))), $namespace];
    }

    /**
     * @return array
     */
    public function getEntities(): array {
        return $this->entities;
    }
}