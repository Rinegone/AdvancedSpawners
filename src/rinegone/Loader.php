<?php

namespace rinegone;

use pocketmine\plugin\PluginBase;
use rinegone\commands\type\SpawnerCommand;
use rinegone\entities\EntityManager;
use rinegone\world\WorldManager;

class Loader extends PluginBase
{

    private static Loader $instance;
    private WorldManager $worldManager;

    public static function getInstance() {
        return self::$instance;
    }

    public function onLoad(): void
    {
        self::$instance = $this;
        $this->worldManager = new WorldManager();
    }

    public function onEnable(): void {
        new EntityManager();

        $this->getLogger()->info("AdvancedSpawners by Rinegone is now enabled!");

        $this->getServer()->getCommandMap()->register("AdvancedSpawners", new SpawnerCommand());

        $this->worldManager->initialize();
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


}