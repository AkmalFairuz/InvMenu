<?php

declare(strict_types=1);

namespace muqsit\invmenu\type;

use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\graphic\BlockActorInvMenuGraphic;
use muqsit\invmenu\type\graphic\InvMenuGraphic;
use muqsit\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use muqsit\invmenu\type\util\InvMenuTypeHelper;
use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\world\World;

final class BlockActorFixedInvMenuType implements FixedInvMenuType{

	private Block $block;
	private int $size;
	private string $tile_id;
	private ?InvMenuGraphicNetworkTranslator $network_translator;
	private int $animation_duration;

	public function __construct(Block $block, int $size, string $tile_id, ?InvMenuGraphicNetworkTranslator $network_translator = null, int $animation_duration = 0){
		$this->block = $block;
		$this->size = $size;
		$this->tile_id = $tile_id;
		$this->network_translator = $network_translator;
		$this->animation_duration = $animation_duration;
	}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic{
		$origin = $player->getPosition()->addVector(InvMenuTypeHelper::getBlockOffset())->floor();
		if($origin->y < World::Y_MIN || $origin->y >= World::Y_MAX){
			return null;
		}

		return new BlockActorInvMenuGraphic($this->block, $origin, BlockActorInvMenuGraphic::createTile($this->tile_id, $menu->getName()), $this->network_translator, $this->animation_duration);
	}

	public function createInventory() : Inventory{
		return new InvMenuInventory($this->size);
	}
}