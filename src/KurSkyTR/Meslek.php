<?php

namespace KurSkyTR;

/**
 * Eklenti çok eski bir eklentidir. Yeni bilgilerim ile kodlamadım eklenti elimde yoktu hatta. Drive dan eklenti kodlarının sslerini buldum. Text Scanner ile kopyala yapıştır yapa yapa eklentiyi bir araya getirdim. Umarım işinize yarar.
 */

use pocketmine\{
plugin\PluginBase,
command\ConsoleCommandSender,
command\CommandSender,
command\Command,
event\Listener,
event\block\BlockBreakEvent,
event\player\PlayerJoinEvent,
event\player\PlayerQuitEvent,
utils\Config,
Player
};

use onebone\economyapi\EconomyAPI;
use FormAPI\{
Form,
ModalForm,
SimpleForm,
CustomForm
};

class Meslek extends PluginBase implements Listener{

public function onEnable(){
    date_default_timezone_set("Europe/Istanbul");
$this->getLogger()->info("Eklenti aktif edildi. By Engincan Ergün to North Network");
$this->getServer()->getPluginManager()->registerEvents($this, $this);
@mkdir($this->getDataFolder(). "Meslek/");
}

public function onJoin(PlayerJoinEvent $event)
{
$e = $event->getPlayer();
if(!(file_exists($this->getDataFolder(). "Meslek/" . $e->getName() . ".yml"))) {
    $cfg = new Config($this->getDataFolder(). "Meslek/" . $e->getName(). ".yml", Config::YAML);
$cfg->set("Meslek", "§cYok!");
$cfg->set("MeslekPuan", "O");
$cfg->save();
}else{
}
}

public function onQuit(PlayerQuitEvent $event){
$e = $event->getPlayer();
$cfg = new Config($this->getDataFolder(). "Meslek/".
$e->getName(). ".yml", Config::YAML);
$cfg->save();
}
/**
@param string|Player Se
*/

public function onBreak(BlockBreakEvent $event){
$e = $event->getPlayer();
$blok = $event->getBlock();
$cfg = new Config($this->getDataFolder(). "Meslek/".
$e->getName(). ".yml", Config::YAML);
if($cfg->get("Meslek") == "§cYok!"){
} elseif($cfg->get("Meslek") == "Oduncu"){
if($blok->getId() == "5" || $blok->getId() == "17"){
$mevcut = $cfg->get("MeslekPuan");
$cfg->set("MeslekPuan", $mevcut+1);
$cfg->save();
$e->sendPopup("§a+1 §eMeslek puanı ve para kazandın.");
EconomyAPI::getInstance()->addMoney($e, 1);
}
} elseif($cfg->get("Meslek") == "Madenci"){
if($blok->getId() == "1" || $blok->getId() == "4"){
$mevcut = $cfg->get("MeslekPuan");
$cfg->set("MeslekPuan", $mevcut+1);
$cfg->save();
$e->sendPopup("§a+1 §eMeslek puanı ve para kazandın.");
EconomyAPI::getInstance()->addMoney($e, 1);
}
} elseif($cfg->get("Meslek") == "Toprakcı"){
if($blok->getID() == "2" || $blok->getID() == "3"){
$mevcut = $cfg->get("MeslekPuan");
$cfg->set("MeslekPuan", $mevcut+1);
$cfg->save();
$e->sendPopup("§a+1 §eMeslek puanı ve para kazandın.");
EconomyAPI::getInstance()->addMoney($e, 1);
}
}
}

public function onCommand(CommandSender $e, Command $kmt, string $lbl, array $args): bool{
if($e instanceof Player) {
if($kmt->getName() == "meslek"){
$cfg = new Config($this->getDataFolder(). "Meslek/".
$e->getName(). ".yml", Config::YAML);
if($cfg->get("Meslek") == "§cYok!"){
$this->meslekYokForm($e);
$cfg = new Config($this->getDataFolder(). "Meslek/" . $e->getName().".yml", Config::YAML);
} elseif($cfg->get("Meslek") == "Oduncu" || $cfg->get("Meslek") == "Madenci" || $cfg->get("Meslek") == "Toprakcı"){
$this->meslekYonetForm($e);
}
}
return true;
}else{
$e->sendMessage("§cBu komut oyunda kullanılabilir.");
}
return true;
}

public function meslekYokForm(Player $e){
$f = new ModalForm(function (Player $e, $data){
$re = $data;
if($re === null){
return true;
}
switch($re){
case true;
break;
case false;
$this->meslekkatilForm($e);
break;
}
});
$f->setTitle("Mesleğin Yok");
$f->setContent("Henüz bir mesleğe katılmamışsın!\nKatılmak için 'Katıl' butonuna bas.");
$f->setButton1("§cÇıkış");
$f->setButton2("Katıl");
$f->sendToPlayer($e);
}

public function meslekkatilForm(Player $e){
$f = new SimpleForm(function (Player $e, $data){
$re = $data;
if($re === null){
return true;
}
switch($re) {
case 0;
$this->meslekYokForm($e);
break;
case 1;
$this->madenciKatil($e);
break;
case 2;
$this->oduncuKatil($e);
break;
case 3;
$this->toprakciKatil($e);
break;
}
});
$f->setTitle("Meslek Seç");
$f->setContent("Hangisini kolay buluyorsan ona tıkla!");
$f->addButton("§cGeri");
$f->addButton("Madenci Mesleği", 0, "textures/blocks/stone");
$f->addButton("Oduncu Mesleği", 0, "textures/blocks/planks_oak");
$f->addButton("Toprakcı Mesleği", 0, "textures/blocks/dirt");
$f->sendToPlayer($e);
}
public function madenciKatil(Player $e){
$f = new ModalForm(function (Player $e, $data){
$re = $data;
if($re === null){
return true;
}
switch($re) {
case true;
$this->meslekKatilForm($e);
break;
case false;
$cfg = new Config($this->getDataFolder(). "Meslek/".$e->getName(). ".yml", Config::YAML);
date_default_timezone_set("Europe/Istanbul");
$cfg->set("Meslek", "Madenci");
$cfg->set("MeslekPuan", "0");
$cfg->set("MeslekTarih", date("d.m.Y H:i:s"));
$cfg->save();
$this->katilimBasarili($e);
break;
}
});
$f->setTitle("Madenci Mesleği");
$f->setContent("Katılacağın meslek : Madenci\n\nGörevler:\nTaş kırma\nKatılmak için 'Katıl' butonuna basınız.");
    $f->setButton1 ("§cGeri");
$f->setButton2("Katıl");
$f->sendToPlayer($e);
}
public function oduncuKatil(Player $e){
$f = new ModalForm(function (Player $e, $data){
$re = $data;
if($re === null){
return true;
}
switch($re) {
case true;
$this->meslekKatilForm($e);
break;
case false;
$cfg = new Config($this->getDataFolder(). "Meslek/".$e->getName(). ".yml", Config::YAML);
date_default_timezone_set("Europe/Istanbul");
$cfg->set("Meslek", "Oduncu");
$cfg->set("MeslekPuan", "0");
$cfg->set("MeslekTarih", date("d.m.Y H:i:s"));
$cfg->save();
$this->katilimBasarili($e);
break;
}
});
$f->setTitle("Oduncu Mesleği");
$f->setContent("Katılacağın meslek : Oduncu\n\nGörevler:\nOdun kırma\nKatılmak için 'Katıl' butonuna basınız.");
$f->setButton1 ("§cGeri");
$f->setButton2("Katıl");
$f->sendToPlayer($e);
}
public function toprakciKatil(Player $e){
$f = new ModalForm(function (Player $e, $data){
$re = $data;
if($re === null){
return true;
}
switch($re) {
case true;
$this->meslekKatilForm($e);
break;
case false;
$cfg = new Config($this->getDataFolder(). "Meslek/".$e->getName(). ".yml", Config::YAML);
date_default_timezone_set("Europe/Istanbul");
$cfg->set("Meslek", "Toprakcı");
$cfg->set("MeslekPuan", "0");
$cfg->set("MeslekTarih", date("d.m.Y H:i:s"));
$cfg->save();
$this->katilimBasarili($e);
break;
}
});
$f->setTitle("Toprakcı Mesleği");
$f->setContent("Katılacağın meslek : Toprakcı\n\nGörevler:\nToprak kırma\nKatılmak için 'Katıl' butonuna basınız.");
$f->setButton1 ("§cGeri");
$f->setButton2("Katıl");
$f->sendToPlayer($e);
}

    public function katilimBasarili(Player $e)
    {
    $form = new ModalForm(function (Player $e, bool $data){});
    $cfg = new Config($this->getDataFolder() . "Meslek/" . $e->getName() . ".yml", Config::YAML);
    $form->setTitle("Katılım Başarılı");
    $form->setContent("Mesleğe katılım başarılı!\n\nKatıldığın meslek : " . $cfg->get("Meslek"));
    $form->setButton1("§cÇıkış");
    $form->setButton2("§cÇıkış");
    $form->sendToPlayer($e);
    }
    
    public function meslekYonetForm(Player $e)
    {
        $f = new SimpleForm(function (Player $e, $data)
        {
            if ($data === null) return true;
            switch ($data)
            {
                case 1;
                    $this->meslekIstatistik($e);
                break;
                case 2;
                    $this->meslekAyril($e);
                break;
            }
        });
        $f->setTitle("Meslek Yönet");
        $f->setContent("İstediğin gibi faydalan :D");
        $f->addButton("§cÇıkış");
        $f->addButton("Meslek Istatistikleri");
        $f->addButton("Meslekten Ayrıl");
        $f->sendToPlayer($e);
    }
    
    public function meslekIstatistik(Player $e)
    {
        $f = new SimpleForm(function (Player $e, $data)
        {
            if ($data === null) return true;
            switch ($data)
            {
                case 0;
                    $this->meslekYonetForm($e);
                break;
            }
        });
        $cfg = new Config($this->getDataFolder() . "Meslek/" . $e->getName() . ".yml", Config::YAML);
        $f->setTitle("Meslek Bilgi");
        $f->setContent("Mesleğin : " . $cfg->get("Meslek") . "\n\nToplam gelirin : " . $cfg->get("MeslekPuan") . " TL\n\nKatıldığın tarih : " . $cfg->get("MeslekTarih") . "\n\nSeviye : §cYAKINDA!");
        $f->addButton("§cGeri");
        $f->sendToPlayer($e);
    }
    
    public function meslekAyril(Player $e)
    {
        $f = new ModalForm(function (Player $e, bool $data)
        {
            if ($data === null) return true;
            switch ($data)
            {
                case true;
                    $this->meslekYonetForm($e);
                break;
                case false;
                    $cfg = new Config($this->getDataFolder() . "Meslek/" . $e->getName() . ".yml", Config::YAML);
                    $cfg->set("Meslek", "§cYok!");
                    $cfg->set("MeslekPuan", 0);
                    $cfg->set("MeslekTarih", 0);
                    $cfg->save();
                    $form = new ModalForm(function (Player $e, bool $data){});
                    $form->setTitle("Ayrılma Başarılı");
                    $form->setContent("Meslekten ayrılma başarılı!");
                    $form->setButton1("§cÇıkış");
                    $form->setButton2("§cÇıkış");
                    $form->sendToPlayer($e);
                break;
            }
        });
        $cfg = new Config($this->getDataFolder() . "Meslek/" . $e->getName() . ".yml", Config::YAML);
        $f->setTitle("Mesleğinden Ayrılmak Üzeresin");
        $f->setContent($cfg->get("Meslek") . " adlı meslekten ayrılmak üzeresin.\n\nAyrılmak istiyorsan 'Ayrıl' butonuna bas.");
        $f->setButton1("§cGeri");
        $f->setButton2("Ayrıl");
        $f->sendToPlayer($e);
    }
}
