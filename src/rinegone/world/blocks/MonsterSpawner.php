<?php

namespace rinegone\world\blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\block\MonsterSpawner as PMSpawner;
use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use rinegone\Loader;
use rinegone\utils\FileManager;
use rinegone\world\tiles\MobSpawner;

class MonsterSpawner extends PMSpawner
{

    protected int $entityId;

    public function __construct()
    {
        parent::__construct(new BlockIdentifier(BlockLegacyIds::MOB_SPAWNER, 0, null, MobSpawner::class), "Monster Spawner", new BlockBreakInfo(5.0, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel()));
    }

    public function isAffectedBySilkTouch(): bool {
        return true;
    }

    public function getSilkTouchDrops(Item $item): array
    {
        $tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
        if ($tile instanceof MobSpawner) {
            $name = LegacyEntityIdToStringIdMap::getInstance()->legacyToString($tile->getEntityId());
            $displayName = [];
            foreach(explode("_", $name) as $value){
                $displayName[] = ucfirst(strtolower($value));
            }
            $displayName = implode(" ", $displayName);
            $nbt = new CompoundTag();
            $nbt->setInt("EntityId", (int)$tile->getEntityId());
            $spawner = ItemFactory::getInstance()->get(ItemIds::MOB_SPAWNER, 0, 1, $nbt);
            $spawner->setCustomName("§r§e" . ucfirst(strtolower(str_replace("Minecraft:", "", $displayName))) . "§r§f Spawner");
            return [$spawner];
        }
        return [];
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
        parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        if($item->getNamedTag()->getTag("EntityId") !== null) {
            $this->entityId = $item->getNamedTag()->getInt("EntityId", -1);
            if($this->entityId > 10) {
                $this->generateSpawnerTile();
            }
        }
        return true;
    }

    private function generateSpawnerTile(): void {
        $tile = $this->getPosition()->getWorld()->getTile($this->getPosition());

        if(!$tile instanceof MobSpawner) {
            $tile = new MobSpawner($this->getPosition()->getWorld(), $this->getPosition());
        }
        $tile->setEntityId($this->entityId);
        $tile->writeSaveData(new CompoundTag());
        $this->onScheduledUpdate();
        $this->getPosition()->getWorld()->addTile($tile);
    }

    public function onScheduledUpdate(): void{
        $tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
        if(!$tile instanceof MobSpawner){
            return;
        }
        if($tile->getTick() > 0) $tile->decreaseTick();
        if($tile->isValidEntity() && $tile->canEntityGenerate() && $tile->getTick() <= 0){
            $tile->setTick(20);
            if($tile->getSpawnDelay() > 0 ){
                $tile->decreaseSpawnDelay();
            }else{
                $tile->setSpawnDelay($tile->getMinSpawnDelay() + mt_rand(0, min(0, $tile->getMaxSpawnDelay() - $tile->getMinSpawnDelay())));
                for($i = 0; $i < $tile->getSpawnCount(); $i++){
                    $x = ((mt_rand(-10, 10) / 10) * $tile->getSpawnRange()) + 0.5;
                    $z = ((mt_rand(-10, 10) / 10) * $tile->getSpawnRange()) + 0.5;
                    $pos = $tile->getPosition();
                    $pos = new Location($pos->x + $x, $pos->y + mt_rand(1, 3), $pos->z + $z, $pos->getWorld(), 0, 0);
                    $entities = Loader::getInstance()->getEntityManager()->getEntities();
                    if (!isset($entities[$tile->getEntityId()])) return;
                    $e = new $entities[$tile->getEntityId()][2]($pos);
                    $e->spawnToAll();
                    $i++;
                }
            }
        }
        $this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
    }
}