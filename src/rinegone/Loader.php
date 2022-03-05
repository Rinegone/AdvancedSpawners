<?php

namespace rinegone;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\plugin\PluginBase;
use rinegone\commands\type\SpawnerCommand;
use rinegone\entities\EntityManager;
use rinegone\world\WorldManager;

class Loader extends PluginBase
{

    private static Loader $instance;
    private WorldManager $worldManager;
    private EntityManager $entityManager;

    public static function getInstance() {
        return self::$instance;
    }

    public function onLoad(): void
    {
        self::$instance = $this;
        $this->worldManager = new WorldManager();
        $this->entityManager = new EntityManager();
    }

    public function onEnable(): void {
        $this->getLogger()->info("AdvancedSpawners by Rinegone is now enabled!");

        $this->getServer()->getCommandMap()->register("AdvancedSpawners", new SpawnerCommand());

        //Looting enchantment
        EnchantmentIdMap::getInstance()->register(EnchantmentIds::LOOTING, new Enchantment(KnownTranslationFactory::enchantment_lootBonus(), Rarity::RARE, ItemFlags::SWORD, ItemFlags::NONE, 3));

        $this->worldManager->initialize();
        $this->entityManager->initialize();
    }

    public function getFile(): string {
        return parent::getFile();
    }

    /**
     * @return WorldManager
     */
    public function getWorldManager(): WorldManager
    {
        return $this->worldManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }


}