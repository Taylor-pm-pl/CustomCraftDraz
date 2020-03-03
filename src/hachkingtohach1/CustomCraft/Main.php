<?php

namespace hachkingtohach1\CustomCraft;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Main extends PluginBase { 

    public function onLoad() : void {
        $this->saveDefaultConfig();
    }	

    public function onEnable() : void {
        $this->registerItemsCraft();		
    }     	
    
	/**
	 *  This is getEnchantment int $id
	 **/
    public function getEnchantment(int $id) {		
		$enchantment = Enchantment::getEnchantment($id);	
		return $enchantment;
	}		
    
    public function getItem(array $item) : Item {
		
        $items = Item::fromString($item[0]);	
        if(isset($item[1])) { 
		    $items->setCount((int) $item[1]);
			if(!$item[2] == "0" || !$item[3] == "0") {
			    $items->addEnchantment(new EnchantmentInstance($this->getEnchantment($item[2]), $item[3]));
			}
        } 
        return $items;
    }
	
	// Lenght => array() & short => []	
	public function registerItemsCraft() {
		
		foreach($this->getConfig()->getAll() as $craft) {
		
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
			    [$this->getItem($craft["result"])]
			);				
            $this->getServer()->getCraftingManager()->registerRecipe($recipes);
        }
	}
}
