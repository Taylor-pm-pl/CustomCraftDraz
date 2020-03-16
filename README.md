# CustomCraftDraz
- One plugin custom craft for pmmp
# Config 
```
---
---
#  Item_1 is name default, you can change name example like: nam, dragovn, ....
Item_1:
#----------------------------------------------------------
#  if id & level = 0 -> enchantment not working !
#----------------------------------------------------------     
    shape:
    - [[0], ["260:0"], [0]]
    - [["260:0"], ["260:0"], ["260:0"]]
    - [[0], ["260:0"], [0]]    
    result:  ["278:0", 1]
          #  [Id enchantment, level enchantment]
    enchantment: [9, 1]
          #  [Name enchantment, item like result is "278:0", this is level]
    cenchantment: ["haste", "278:0", 1]    
...
```
# Update next !
- Update commands setup for shape and result to easy usage!
