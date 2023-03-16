<?php

namespace Inaayat\Party;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Party {

    private $leader;
    private $members = [];
    private $invitedPlayers = [];

    public function __construct(Player $leader) {
        $this->leader = $leader;
        $this->addMember($leader);
    }

    public function getLeader(): Player {
        return $this->leader;
    }

    public function addMember(Player $player): void {
        $this->members[$player->getName()] = $player->getUniqueId();
        $player->sendMessage(PartyAPI::getPrefix() . TextFormat::GREEN . "You joined a party led by " . $this->leader->getName());
        $this->notifyMembers(PartyAPI::getPrefix() . TextFormat::GREEN . $player->getName() . " joined the party");
    }

    public function removeMember(Player $player): void {
        unset($this->members[$player->getName()]);
        $player->sendMessage(PartyAPI::getPrefix() . TextFormat::GREEN . "You left the party");
        $this->notifyMembers(PartyAPI::getPrefix() . TextFormat::RED . $player->getName() . " left the party");
    }

    public function getMembers(): array {
        $members = [];
        foreach ($this->members as $name => $uuid) {
            $player = $this->getPlayerByUuid($uuid);
            if ($player !== null) {
                $members[] = $player;
            }
        }
        return $members;
    }

    public function invitePlayer(Player $sender, Player $player): void {
        $this->invitedPlayers[$player->getName()] = $sender->getName();
        $player->sendMessage(PartyAPI::getPrefix() . TextFormat::GREEN . "You have been invited to " . TextFormat::RED . $sender->getName() . TextFormat::GREEN . "'s party");
        $sender->sendMessage(PartyAPI::getPrefix() . TextFormat::GREEN . "You invited " . TextFormat::RED . $player->getName() . TextFormat::GREEN . " to your party");
    }

    public function isInvited(Player $player): bool {
        return isset($this->invitedPlayers[$player->getName()]);
    }

    public function notifyMembers(string $message): void {
        foreach ($this->members as $name => $uuid) {
            $player = $this->getPlayerByUuid($uuid);
            if ($player !== null) {
                $player->sendMessage($message);
            }
        }
    }

    public function isMember(Player $player): bool {
        return isset($this->members[$player->getName()]);
    }

    public function getPlayerByUuid($uuid): ?Player {
	return Server::getInstance()->getPlayerByUUID($uuid);
    }
}
