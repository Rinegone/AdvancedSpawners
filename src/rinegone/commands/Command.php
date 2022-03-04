<?php

namespace rinegone\commands;

use pocketmine\command\Command as PMCommand;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

abstract class Command extends PMCommand {

    public function setPermission(?string $permission): void{
        if($permission !== null){
            $permManager = PermissionManager::getInstance();
            $opRoot = $permManager->getPermission(DefaultPermissions::ROOT_OPERATOR);

            foreach(explode(";", $permission) as $perm){
                if(PermissionManager::getInstance()->getPermission($perm) === null){
                    $permManager->addPermission($perm = new Permission($perm));
                    $opRoot->addChild($perm->getName(), true);
                }
            }
        }
        parent::setPermission($permission);
    }

}