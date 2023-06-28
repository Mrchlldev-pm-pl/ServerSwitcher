<?php

declare(strict_types=1);

namespace iMD14\ServerSwitcher;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase{
  public $servers;
  public function onEnable(): void{
    @mkdir($this->getDataFolder());
    $this->saveResource("servers.yml");
    #$this->saveDefaultConfig();
    $this->getResource("servers.yml");
    $this->servers = new Config($this->getDataFolder() . "servers.yml", Config::YAML);
  }

public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    switch($command->getName()) {
        case "server":
            if(count($args) !== 1) {
                $sender->sendMessage("/server <name>");
                return false;
            }
            $config = new Config($this->getDataFolder() . "servers.yml", Config::YAML);
            $servers = $config->get("servers");

            $name = $args[0];
            foreach($servers as $server) {
                if($server['name'] == $name) {
                    $sender->sendMessage("Taking you to " . $server['ip'] . ":" . $server['port']);
                    $sender->sendMessage($server['ip'] . ":" . $server['port']);
                    if ($sender instanceof Player) {
                        $sender->transfer($server['ip'], $server['port']);
                    } else {
                        $sender->sendMessage("Cannot transfer you to " . $server['name']);
                    }
                    return true;
                }
            }
            $sender->sendMessage("Server not found: $name");
            return false;
        default:
            return false;
        case "servers":
            $config = new Config($this->getDataFolder() . "servers.yml", CONFIG::YAML);
            $serverData = $this->serversConfig->getAll()["server"] ?? [];
            
            $sender->sendMessage("Server List:");
            $serverNames = [];
            foreach ($serverData as $server) {
                $name = $server['name'] ?? "Unknown";
                $serverNames[] = "Server" . count($serverNames) + 1 . ": " . $name;
                }
            $sender->sendMessage(implode(", ", $serverNames));
            return true;
              
        
    }
    return false;
}
}
