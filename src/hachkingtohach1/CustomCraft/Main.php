<?php

namespace hachkingtohach1\CustomCraft;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\item\enchantment\{Enchantment,EnchantmentInstance};
use DaPigGuy\PiggyCustomEnchants\{CustomEnchantManager,PiggyCustomEnchants,utils\Utils};

class Main extends PluginBase { 

    public function onLoad() : void 
	{
        $this->saveDefaultConfig();
    }	

    public function onEnable() : void 
	{
		$this->checkPluginNeed();
        $this->registerItemsCraft();		
    }

    public function checkPluginNeed() 
	{
		$this->ce = $this->getServer()->getPluginManager()->getPlugin('PiggyCustomEnchants');
        if($this->ce === null) {
			$this->getLogger()->warning('You need install plugin PiggyCustomEnchants to use this plugin!');
            $this->getServer()->shutDown();				
		}
	}		
    
	/**
	 *  This is getEnchantment int $id
	 **/
    public function getEnchantment(int $id) 
	{	
		$enchantment = Enchantment::getEnchantment($id);	
		return $enchantment;
	}
    
	/**
	 * This is getCEnchantment string $name Item $item, int $level
	 **/
    public function getCEnchantment(string $name, $item, int $level) 
	{
		$items = Item::fromString($item);
		$enchant = CustomEnchantManager::getEnchantmentByName($name);
        if ($enchant === null) {
			$this->getLogger()->warning('CE is '.$name.' with level'.$level.' name is null!');
			return;
		}
		if ($level > $enchant->getMaxLevel()) {
			$this->getLogger()->warning('CE is '.$name.' with level'.$level.' max level is '.$enchant->getMaxLevel());
			return;
		}
		if(!Utils::checkEnchantIncompatibilities($items, $enchant)) {
			$this->getLogger()->warning('CE is '.$name.' with level '.$level.'This enchant is not compatible with another enchant.');
            return;
        }
		return $enchant;		
	}

    public function getItem(array $item) : Item 
	{		
        $items = Item::fromString($item[0]);	
        if(isset($item[1])) { 
		    $items->setCount((int) $item[1]);
        } 
        return $items;
    }	
    
    public function getItemResult(array $item, array $enchantment, array $cenchantment) : Item 
	{	
        $items = Item::fromString($item[0]);
        $ec = $enchantment;
        $ce = $cenchantment; 		
        if(isset($item[1])) { 
		    $items->setCount((int) $item[1]);
			if(!$enchantment[0] == "0" && !$enchantment[1] == "0") {
			    $items->addEnchantment(new EnchantmentInstance($this->getEnchantment($ec[0]), $ec[1]));
			}
			if(!$ce[0] == "0" && !$ce[1] == "0" && !$ce[2] == "0") {
				$items->addEnchantment(new EnchantmentInstance($this->getCEnchantment($ce[0], $ce[1], $ce[2]), $ce[2]));
			}
        } 
        return $items;
    }
	
	// Lenght => array() & short => []	
	public function registerItemsCraft() 
	{		
		foreach($this->getConfig()->getAll() as $craft) 
		{		
            $recipes = new ShapedRecipe( 
			array("abc","def","ghi"), 
			    array (			
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
			    [$this->getItemResult($craft["result"], $craft["enchantment"], $craft["cenchantment"])]
			);				
            $this->getServer()->getCraftingManager()->registerRecipe($recipes);
        }
	}
}
