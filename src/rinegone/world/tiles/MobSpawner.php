<?php

namespace rinegone\world\tiles;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use rinegone\Loader;

class MobSpawner extends Spawnable {

    const TILE_ID = "MobSpawner";
    const TILE_BLOCK = BlockLegacyIds::MOB_SPAWNER;

    const TAG_ENTITY_ID = "EntityId";
    const TAG_SPAWN_COUNT = "SpawnCount";
    const TAG_SPAWN_RANGE = "SpawnRange";
    const TAG_MIN_SPAWN_DELAY = "MinSpawnDelay";
    const TAG_MAX_SPAWN_DELAY = "MaxSpawnDelay";

    private bool $validEntity = true;

    private int $entityId = -1;
    private int $spawnCount = 4;
    private int $spawnRange = 4;
    private int $spawnDelay = 40;
    private int $minSpawnDelay = 30;
    private int $maxSpawnDelay = 80;
    private int $tick = 20;


    public function getEntityId(): int{
        return $this->entityId;
    }

    public function setEntityId(int $entityId): void{
        $this->entityId = $entityId;
        $this->validEntity = true;
        $this->setDirty();
        $this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);

    }

    public function getSpawnCount(): int{
        return $this->spawnCount;
    }

    public function getSpawnRange(): int{
        return $this->spawnRange;
    }

    public function getSpawnDelay(): int{
        return $this->spawnDelay;
    }

    public function setSpawnDelay(int $spawnDelay): void{
        $this->spawnDelay = $spawnDelay;
    }

    public function decreaseSpawnDelay(): void{
        $this->spawnDelay--;
    }

    public function getMinSpawnDelay(): int{
        return $this->minSpawnDelay;
    }

    public function getMaxSpawnDelay(): int{
        return $this->maxSpawnDelay;
    }

    public function getTick(): int{
        return $this->tick;
    }

    public function setTick(int $tick): void{
        $this->tick = $tick;
    }

    public function decreaseTick(): void{
        $this->tick--;
    }

    public function isValidEntity(): bool{
        return $this->validEntity;
    }

    public function canEntityGenerate(): bool{
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            if ($player->getPosition()->distance($this->getPosition()) < 16){
                return true;
            }
        }
        return false;
    }

    public function readSaveData(CompoundTag $nbt): void{
        if (($tag = $nbt->getTag(self::TAG_ENTITY_ID)) !== null){
            $this->setEntityId($tag->getValue());
        }
        if (($tag = $nbt->getTag(self::TAG_SPAWN_COUNT)) !== null){
            $this->spawnCount = (int)$tag->getValue();
        }
        if (($tag = $nbt->getTag(self::TAG_SPAWN_RANGE)) !== null){
            $this->spawnRange = (int)$tag->getValue();
        }
        if (($tag = $nbt->getTag(self::TAG_MIN_SPAWN_DELAY)) !== null){
            $this->minSpawnDelay = (int)$tag->getValue();
        }
        if (($tag = $nbt->getTag(self::TAG_MAX_SPAWN_DELAY)) !== null){
            $this->maxSpawnDelay = (int)$tag->getValue();
        }
    }

    public function writeSaveData(CompoundTag $nbt): void{
        $this->addAdditionalSpawnData($nbt);
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt): void{
        foreach([self::TAG_SPAWN_COUNT, self::TAG_SPAWN_RANGE, self::TAG_MIN_SPAWN_DELAY, self::TAG_MAX_SPAWN_DELAY] as $id){
            if ($nbt->getTag($id) instanceof IntTag){
                $nbt->removeTag($id);
            }
        }
        $nbt->setInt(self::TAG_ENTITY_ID, $this->entityId);
        $nbt->setShort(self::TAG_SPAWN_COUNT, $this->spawnCount);
        $nbt->setShort(self::TAG_SPAWN_RANGE, $this->spawnRange);
        $nbt->setShort(self::TAG_MIN_SPAWN_DELAY, $this->minSpawnDelay);
        $nbt->setShort(self::TAG_MAX_SPAWN_DELAY, $this->maxSpawnDelay);
    }
}