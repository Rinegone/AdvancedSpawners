<?php

namespace rinegone\commands\type;

use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use rinegone\commands\Command;

class SpawnerCommand extends Command {

    public function __construct() {
        parent::__construct("spawner",
            "Give an online player a certain monster spawner!"
        );
        $this->setPermission("admin.spawner");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage("§r§cYou do not have permission to perform this action!");
            return;
        }
        if (!isset($args[0]) or !isset($args[1])) {
            $sender->sendMessage("Usage /spawner <player> <spawner> <amount>");
            return;
        }
        $amount = !isset($args[2]) ? 1 : $args[2];
        if(($target = $sender->getServer()->getPlayerByPrefix($args[0])) === null){
            $sender->sendMessage("§cPlayer not found!");
            return;
        }
        unset($args[0]);
        $id = EntityLegacyIds::class . "::" . strtoupper(implode("_", $this->ignoreArgument($args, 0)));
        if(!defined($id)){
            $sender->sendMessage("§cEntity not found");
            return;
        }
        $id = constant($id);
        $nbt = new CompoundTag();
        $nbt->setInt("EntityId", (int) $id);
        $spawner = ItemFactory::getInstance()->get(ItemIds::MOB_SPAWNER, 0, $amount, $nbt);
        $spawner->setCustomName("§r§b". ucwords(implode(" ", $args)) . "§r§f Spawner");
        $inventory = $target->getInventory();
        if($inventory->canAddItem($spawner)) $inventory->addItem($spawner);
        else $target->dropItem($spawner);
        $target->sendMessage("§aYou have received a ". ucwords(implode(" ", $args)) . " spawner.");
    }

    /**
     * @param array $args
     * @param int $i
     * @return array
     */
    protected function ignoreArgument(array $args, int $i): array {
        unset($args[$i]);
        return $args;
    }

}