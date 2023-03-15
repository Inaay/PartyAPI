<?php

namespace Inaayat\Party;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PartyAPI {

	private $parties = [];
	private static $instance;

	public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	public static function getPrefix(): string {
		return "§7[§eParty§7]§r ";
	}

	public function createParty(Player $leader): Party {
        $party = new Party($leader);
        $this->parties[$leader->getName()] = $party;
        return $party;
    }

    public function removeParty(Party $party): void {
        unset($this->parties[$party->getLeader()->getName()]);
    }

    public function getPartyByLeader(Player $leader): ?Party {
        return $this->parties[$leader->getName()] ?? null;
    }

    public function invitePlayer(Party $party, Player $sender, Player $player): void {
        $party->invitePlayer($sender, $player);
    }

    public function acceptInvite(Player $player, Player $sender): void {
        $party = $this->getPartyByLeader($sender);
        if ($party !== null && $party->isInvited($player)) {
            $party->addMember($player);
            $player->sendMessage(self::getPrefix() . TextFormat::GREEN . "You joined " . TextFormat::RED . $sender->getName() . TextFormat::GREEN . "'s party");
            $sender->sendMessage(self::getPrefix() . TextFormat::RED . $player->getName() . TextFormat::GREEN . " joined your party");
        } else {
            $player->sendMessage(self::getPrefix() . TextFormat::RED . "You have no pending invitations");
        }
    }

    public function removePlayer(Party $party, Player $player): void {
        if ($party->getLeader() === $player) {
            $partyMembers = $party->getMembers();
            $this->removeParty($party);
            foreach ($partyMembers as $member) {
                $member->sendMessage(self::getPrefix() . TextFormat::RED . "The party has been disbanded");
            }
        } else {
            $party->removeMember($player);
            $partyMembers = $party->getMembers();
            foreach ($partyMembers as $member) {
                $member->sendMessage(self::getPrefix() . TextFormat::RED . $player->getName() . " left the party");
            }
        }
    }

	public function getParties(): array {
        $parties = [];
        foreach ($this->parties as $party) {
            $parties[] = $party;
        }
        return $parties;
    }

    public function getPlayerParty(Player $player): ?Party {
        foreach ($this->parties as $party) {
            if ($party->getLeader() === $player || isset($party->getMembers()[$player->getName()])) {
                return $party;
            }
        }
        return null;
    }
}