## PartyAPI 
<img src="https://github.com/Inaay/PartyAPI/blob/main/meta/Logo.png" alt="Logo">

The PartyAPI plugin provides a simple and easy-to-use API for creating and managing parties in PocketMine-MP. By using the PartyAPI methods, you can easily create and manage parties in your PocketMine-MP server.

* Example Plugin: [PartyAPI Example](https://github.com/Inaay/PartyAPI-Example)

## Usage

The PartyAPI plugin provides a simple API for creating and managing parties. You can use the following methods:

### - Returns the instance of the PartyAPI.
```php
PartyAPI::getInstance();
```

### - Creates a new party with the specified leader and returns it.
```php
PartyAPI::createParty(Player $leader): Party
```

### - Removes the specified party.
```php
PartyAPI::removeParty(Party $party): void
```

### - Returns the party led by the specified leader, or null if no such party exists.
```php
PartyAPI::getPartyByLeader(Player $leader): ?Party
```

### - Invites the specified player to the specified party on behalf of the specified sender.
```php
PartyAPI::invitePlayer(Party $party, Player $sender, Player $player): void
```

### - Accepts an invitation to join a party from the specified sender, if one exists.
```php
PartyAPI::acceptInvite(Player $player, Player $sender): void
```

### - Removes the specified player from the specified party.
```php
PartyAPI::removePlayer(Party $party, Player $player): void
```

### - Returns the party that the specified player is a member of, or null if the player is not in a party.

```php
PartyAPI::getPlayerParty(Player $player): ?Party
```

### Creating a Party

To create a party, simply call the ``createParty()`` method with the player you want to be the leader of the party:

```php
$partyAPI = PartyAPI::getInstance();
$leader = $player; // Replace this with the player you want to be the leader of the party
$party = $partyAPI->createParty($leader);
```
This will create a new party with the specified player as the leader, and return the party object.

### Inviting a Player

To invite a player to a party, call the ``invitePlayer()`` method with the party, the player sending the invitation, and the player being invited:

```php
$partyAPI = PartyAPI::getInstance();
$party = $partyAPI->getPartyByLeader($leader); // Replace $leader with the leader of the party
$sender = $playerSendingInvite; // Replace $playerSendingInvite with the player sending the invitation
$invitee = $playerBeingInvited; // Replace $playerBeingInvited with the player being invited
$partyAPI->invitePlayer($party, $sender, $invitee);
```

This will send an invitation to the specified player on behalf of the sender to join the specified party.

### Accepting an Invitation

To accept an invitation to join a party, call the ``acceptInvite()`` method with the player accepting the invitation and the player who sent the invitation:

```php
$partyAPI = PartyAPI::getInstance();
$player = $playerAcceptingInvite; // Replace $playerAcceptingInvite with the player accepting the invitation
$sender = $playerWhoSentInvite; // Replace $playerWhoSentInvite with the player who sent the invitation
$partyAPI->acceptInvite($player, $sender);
```

This will add the specified player to the party led by the sender if an invitation from the sender exists.

### Removing a Player

To remove a player from a party, call the ``removePlayer()`` method with the party and the player to be removed:

```php
$partyAPI = PartyAPI::getInstance();
$party = $partyAPI->getPlayerParty($player); // Replace $player with the player to be removed
$playerToRemove = $player; // Replace $player with the player to be removed
$partyAPI->removePlayer($party, $playerToRemove);
```

This will remove the specified player from the specified party.

### Getting All Parties

To get an array of all parties, call the getParties() method:

```php
$partyAPI = PartyAPI::getInstance();
$parties = $partyAPI->getParties();
```

This will return an array of all parties.

### Getting a Player's Party

To get the party that a player is a member of, call the ``getPlayerParty()`` method with the player:

```php
$partyAPI = PartyAPI::getInstance();
$player = $playerToCheck; // Replace $playerToCheck with the player to check the party membership for
$party = $partyAPI->getPlayerParty($player);
```

This will return the party that the specified player is a member of, or null if the player is not in a party.

### Example party warp

This is an example from my practice-core

```php
public function sendToNodebuffFFA(Player $player) {
    $party = PartyAPI::getInstance()->getPlayerParty($player);
    if ($party !== null) {
        if ($party->getLeader() === $player) {
            $worldManager = Server::getInstance()->getWorldManager();
            $world = $worldManager->getWorldByName("nodebuff"); //nodebuff world example
            if ($world === null) {
                $player->sendMessage(TextFormat::RED . "The world 'nodebuff' does not exist.");
                return;
            }
            $members = $party->getMembers();
            $sentPlayers = [];
            foreach ($members as $member) {
                if ($member->getWorld()->getFolderName() !== "nodebuff") { // nodebuff world example
                    $member->teleport($world->getSpawnLocation());
                    $this->giveNodebuffKit($member);
                    $sentPlayers[] = $member->getName();
                }
            }
            if (!empty($sentPlayers)) {
                $player->sendMessage(TextFormat::GREEN . "Sent the following party members to nodebuff: " . TextFormat::RED . implode(", ", $sentPlayers));
            } else {
                $player->sendMessage(TextFormat::RED . "All party members are already in nodebuff.");
            }
        } else {
            $player->sendMessage(TextFormat::RED . "You can't join Nodebuff while in a party.");
        }
    } else {
        $worldManager = Server::getInstance()->getWorldManager();
        $world = $worldManager->getWorldByName("nodebuff");
        if ($world === null) {
            $player->sendMessage(TextFormat::RED . "The world 'nodebuff' does not exist.");
            return;
        }
        $player->teleport($world->getSpawnLocation());
    }
}
```
