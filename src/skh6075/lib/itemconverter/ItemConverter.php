<?php

namespace skh6075\lib\itemconverter;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\TreeRoot;

final class ItemConverter{

	public static function itemToMap(Item $item, bool $pushMeta = true, bool $pushCount = true, bool $pushNBT = true): array{
		$map = [];
		$map["id"] = $item->getId();
		if($pushMeta){
			$map["meta"] = $item->getMeta();
		}
		if($pushCount){
			$map["count"] = $item->getCount();
		}
		if($pushNBT){
			$map["nbt"] = base64_encode((new LittleEndianNbtSerializer())->write(new TreeRoot($item->getNamedTag())));
		}
		return $map;
	}

	public static function mapToItem(array $map): Item{
		$nbt = "";
		if(isset($map["nbt"])){
			$nbt = base64_decode($map["nbt"], true);
		}
		return ItemFactory::getInstance()->get(
			(int) $map["id"],
			(int) ($map["meta"] ?? 0),
			(int) ($map["count"] ?? 1),
			$nbt !== "" ? (new LittleEndianNbtSerializer())->read($nbt)->mustGetCompoundTag() : null
		);
	}

	public static function mapToStr(array $map): string{
		return json_encode($map, JSON_THROW_ON_ERROR);
	}

	public static function strToMap(string $mapHash): array{
		return json_decode($mapHash, true);
	}
}