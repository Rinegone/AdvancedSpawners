<?php

namespace rinegone\world;

use pocketmine\block\BlockFactory;
use pocketmine\block\tile\TileFactory;
use pocketmine\utils\SingletonTrait;
use rinegone\world\blocks\MonsterSpawner;
use rinegone\world\tiles\MobSpawner;

class WorldManager {
    use SingletonTrait;

    public function __construct() {
        self::setInstance($this);
    }


    public function initialize() {
        BlockFactory::getInstance()->register(new MonsterSpawner(), true);

        TileFactory::getInstance()->register(MobSpawner::class, [MobSpawner::TILE_ID], MobSpawner::TILE_BLOCK);
    }



}