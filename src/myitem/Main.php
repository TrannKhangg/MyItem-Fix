<?php

namespace myitem;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\item\Item;

use pocketmine\block\Block;

use pocketmine\inventory\BaseInventory;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase implements Listener {
  
  public function onEnable(){
    $this->getLogger()->info("MyItem đã bật !");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  
  public function onDisable(){
    $this->getLogger()->info("MyItem đã tắt...");
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
    switch($cmd->getName()){
      case "mi":
        if ($sender instanceof Player){
          if($sender->hasPermission("myitem.command")){
            if(isset($args[0])){
              if($args[0] === "help"){
                $sender->sendMessage("§l§c➱ Help MyItem\n§l§c/mi help - hướng dẫn cách sử dụng\n
/mi create - tạo 1 vũ khí mới\n
/mi setname - đặt tên vũ khí\n
/mi setlore - đặt thông tin cho vũ khí\n
/mi id - Xem id enchant\n
/mi adden - thêm enchant\n");
              }else{
                if($args[0] === "create"){
                  $this->VuKhi($sender);
                }else{
                  if($args[0] === "setname"){
                    $this->SetName($sender);
                  }else{
                    if($args[0] === "setlore"){
                      $this->SetLore($sender);
                    }else{
                      if($args[0] === "id"){
                        $sender->sendMessage("§l§c﹝ Id Enchant ﹞\n§l§eThorns | 5 |
Respiration | 6 |\n
Depth Strider | 7 |\n
Aqua Infinity | 8 |\n
Weapons :\n
Sharpness | 9 |\n
Smite | 10 |\n
Babe of Arthropods | 11 |\n
Knockback | 12 |\n
Fire Aspect | 13 |\n
Looting | 14 |\n
Tools :\n
SilkTouch | 16 |\n
Fortune | 18 |\n
Bow :\n
Power | 19 |\n
Punch | 20 |\n
Flame | 21 |\n
Infinity | 22 |\n
All Items Above :n\n
Unbreaking | 17 |\n
Efficiency | 15 | ");
                      }else{
                        if($args[0] === "adden"){
                          $this->EnchantUi($sender);
                        }
                      }
                    }
                  }
                }
              }
            }else{
              $sender->sendMessage("§l§c➬ Cách Dùng: /mi help|create|setname|setlore|id|adden");
            }
          }else{
            $sender->sendMessage("§c§l•§e Bạn không có quyền để xài lệnh này !");
          }
        }else{
          $sender->sendMessage("Yêu cầu : Sử dụng lệnh này trong game");
        }
    }
    return true;
  }
  public function VuKhi($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        return true;
      }
      switch($data){
        case 0:
          $player->getInventory()->addItem(Item::get(Item::DIAMOMD_SWORD));
          break;
          
          case 1:
            $player->getInventory()->addItem(Item::get(Item::DIAMOND_AXE));
            break;
            
            case 2:
              $player->getInventory()->addItem(Item::get(Item::DIAMOND_PICKAXE));
              break;
              
              case 3:
                $player->getInventory()->addItem(Item::get(Item::DIAMOMD_SHOVEL));
                break;
                
                case 4:
                  $player->getInventory()->addItem(Item::get(Item::DIAMOMD_HOE));
                  break;
                  
                  case 5:
                    $player->getInventory()->addItem(Item::get(Item::BEDROCK));
                    break;
      }
    });
    $form->setTitle("§l§c【 Tạo Vũ Khí 】");
    $form->setContent("§l§b➪ Chọn vũ khí muốn tạo");
    $form->addButton("§l§e✔ Kiếm");
    $form->addButton("§l§e✔ Rìu");
    $form->addButton("§l§e✔ Cúp");
    $form->addButton("§l§e✔ Xẻng");
    $form->addButton("§l§e✔ Cuốc");
    $form->addButton("§l§e✔ Bedrock");
    $form->sendToPlayer($player);
    return $form;
  }
  public function SetName($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        return true;
      }
      if($data[0] == null){
        $player->sendMessage("§l§c✘ Làm ơn hãy ghi tên rồi mới ấn");
        return true;
      }
      $itemhand = $player->getInventory()->getItemInHand();
      $id = $itemhand->getId();
      $meta = $itemhand->getDamage();
      $count = $itemhand->getCount();
      $newitem = Item::get($id, $meta, $count);
      $player->getInventory()->removeItem(Item::get($id, $meta, $count));
      $newitem->setCustomName($data[0]);
      $player->sendMessage("§l§a✔ Set Name Thành Công");
      if($itemhand->hasEnchantments()){
        foreach($itemhand->getEnchantments() as $enchantment){
        $newitem->addEnchantment($enchantment);
        }
      }
      $player->getInventory()->addItem($newitem);
    });
    $form->setTitle("§l§c〚 Set Name 〛");
    $form->addInput("§l§c➪ Viết Tên Muốn Đặt Cho Item", "0");
    $form->sendToPlayer($player);
    return $form;
  }
  public function SetLore($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        return true;
      }
      if($data[0] == null){
        $player->sendMessage("§l§c✘ Làm Ơn Hãy Ghi Lore Đi");
        return true;
      }
      $itemhand = $player->getInventory()->getItemInHand();
      $lore = implode(" ", $data);
      $lore = explode("\\n",$lore);
      $id = $itemhand->getId();
      $meta = $itemhand->getDamage();
      $count = $itemhand->getCount();
      $newitem = Item::get($id, $meta, $count);
      $player->getInventory()->removeItem(Item::get($id, $meta, $count));
      $newitem->setLore($lore);
      $player->sendMessage("§l§a✔ Set Lore Thành Công");
      if($itemhand->hasEnchantments()){
        foreach($itemhand->getEnchantments() as $enchantment){
          $newitem->addEnchantment($enchantment);
        }
      }
      if($itemhand->hasCustomName()){
        $newitem->setCustomName($itemhand->getCustomName());
      }
      $player->getInventory()->addItem($newitem);
    });
    $form->setTitle("§l§c〚 Set Lore 〛");
    $form->addInput("§l§c➪ Hãy Nhập Lore Muốn Add Item", "0");
    $form->sendToPlayer($player);
    return $form;
  }
  public function EnchantUi($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        return $form;
      }
      if($data[0] == null){
        $player->sendMessage("§l§c✘ Làm Ơn Hãy Ghi Số Id");
        return true;
      }
      if($data[1] == null){
        $player->sendMessage("§l§c✘ Làm Ơn Hãy Ghi Số Level");
        return true;
      }
      if(!is_numeric($data[1])){
        $player->sendMessage("§l§c✘ Hãy Ghi Số Level Bằng Số");
        return true;
      }
      if(!is_numeric($data[0])){
        $player->sendMessage("§l§c✘ Hãy Ghi Số Id Của Enchant Bằng Sô");
        return true;
      }
      $itemhand = $player->getInventory()->getItemInHand();
      $id = $itemhand->getId();
      $meta = $itemhand->getDamage();
      $count = $itemhand->getCount();
      $newitem = Item::get($id, $meta, $count);
      $player->getInventory()->removeItem(Item::get($id, $meta, $count));
     $enchantdata = Enchantment::getEnchantment($data[0]);
     $newitem->addEnchantment(new EnchantmentInstance($enchantdata, $data[1]));
     $player->sendMessage("§l§a✔ Enchant Thành Công");
      $player->getInventory()->addItem($newitem);
      if($itemhand->hasEnchantments()){
        foreach($itemhand->getEnchantments() as $enchantment){
          $newitem->addEnchantment($enchantment);
        }
      }
      if($itemhand->hasCustomName()){
        $newitem->setCustomName($itemhand->getCustomName());
      }
   $player->getInventory()->addItem($newitem);
    });
    $form->setTitle("§l§c〚 Enchant Item 〛");
    $form->addInput("§l§e➫ Ghi Số Id Của Enchant", "0");
    $form->addInput("§l§e➫ Ghi Số  Level Của Enchant", "0");
    $form->sendToPlayer($player);
    return $form;
  }
  
}
