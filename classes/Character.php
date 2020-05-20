<?php
class Character
{
  private $damages;
  private $name;
  private $id;

  const ITS_ME = 1;
  const CHARACTER_KILL = 2;
  const CHARACTER_HIT = 3;

  public function __construct(array $characterRow)
  {
    $this->hydrate($characterRow);
  }

  public function hit(Character $character)
  {
    if($character->id() == $this->id){
      return self::ITS_ME;
    }    

    return $character->receiveDamages();

  }

  public function valideName()
  {
    return !empty($this->_nom);
  }
  
  public function hydrate(array $characterRow)
  {
    $this->setName($characterRow["nom"]);
    $this->setDamages($characterRow["degats"]);
  }
  
  public function receiveDamages()
  {
    $this->damages += 5;
    
    if($this->damages >= 100){
      return self::CHARACTER_KILL;
    }
    return self::CHARACTER_HIT;
  }

  public function id()
  {
    return $this->id;
  }

  public function damages()
  {
    return $this->damages;
  }
    
  public function name()
  {
    return $this->name;
  }
  
  public function setDamages($damages)
  {
    $damages = (int) $damages;
    
    if ($damages >= 0 && $damages <= 100)
    {
      $this->damages = $damages;
    }
  }
    
  public function setName($name)
  {
    if (is_string($name))
    {
      $this->name = $name;
    }
  }
}