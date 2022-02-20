<?php

declare(strict_types=1);

namespace CustomCraft;

use pocketmine\plugin\PluginBase;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use DaPigGuy\PiggyCustomEnchants\utils\Utils;
use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;
use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;

class Main extends PluginBase {

	public function onEnable(): void {
		$this->saveDefaultConfig();
		$this->checkPluginNeed();
		$this->registerItemsCraft();
	}

	public function checkPluginNeed(): bool {
		if (!$this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants")) {
			return false;
		}
		return true;
	}

	public function getEnchantment(int $id) {
		$enchantment = EnchantmentIdMap::getInstance()->fromId($id);
		return $enchantment;
	}

	public function getCEnchantment(string $name, $item, int $level) {
		$items = LegacyStringToItemParser::getInstance()->parse($item[0]);
		$enchant = CustomEnchantManager::getEnchantmentByName($name);
		if ($enchant === null) {
			$this->getLogger()->warning('CE is ' . $name . ' with level ' . $level . ' name is null!');
			return;
		}
		if ($level > $enchant->getMaxLevel()) {
			$this->getLogger()->warning('CE is ' . $name . ' with level' . $level . ' max level is ' . $enchant->getMaxLevel());
			return;
		}
		if (!Utils::checkEnchantIncompatibilities($items, $enchant)) {
			$this->getLogger()->warning('CE is ' . $name . ' with level ' . $level . 'This enchant is not compatible with another enchant.');
			return;
		}
		return $enchant;
	}

	public function getItem(array $item): Item {
		$items = LegacyStringToItemParser::getInstance()->parse($item[0]);
		if (isset($item[1])) {
			$items->setCount((int) $item[1]);
			foreach ($this->getConfig()->getAll() as $craft) {
				if ($craft["enable_enchant"] == "true") {
					foreach ($craft["enchantment"] as $id => $level) {
						$items->addEnchantment(new EnchantmentInstance($this->getEnchantment($id), $level));
					}
				}
				if ($craft["enable_cenchant"] == "true") {
					if ($this->checkPluginNeed() == true) {
						foreach ($craft["cenchantment"] as $id => $level) {
							$items->addEnchantment(new EnchantmentInstance($this->getCEnchantment($id, $craft["result"][0], $level), $level));
						}
					}
				}
			}
		}
		return $items;
	}

	public function registerItemsCraft() {
		// Lenght => array() & short => []
		foreach ($this->getConfig()->getAll() as $craft) {
			$recipes = new ShapedRecipe(
				array("abc", "def", "ghi"),
				array(
					"a" => $this->getItem($craft["shape"][0][0]),
					"b" => $this->getItem($craft["shape"][0][1]),
					"c" => $this->getItem($craft["shape"][0][2]),
					"d" => $this->getItem($craft["shape"][1][0]),
					"e" => $this->getItem($craft["shape"][1][1]),
					"f" => $this->getItem($craft["shape"][1][2]),
					"g" => $this->getItem($craft["shape"][2][0]),
					"h" => $this->getItem($craft["shape"][2][1]),
					"i" => $this->getItem($craft["shape"][2][2]),
				),
				[$this->getItem($craft["result"])]
			);
			$this->getServer()->getCraftingManager()->registerShapedRecipe($recipes);
		}
	}
}
