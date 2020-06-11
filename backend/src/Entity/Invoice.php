<?php 
  
  class Invoice  {
      private $id; 
      private $number; //invoice Number
      private $date; 
      private $amount ; // the final price
      private $paid; 

    public function getid():int{ 
        return $this ->id; 
    }
    public function setid(int $id){ 
      $this->id = $id; 
    }
    public function getnumber():int{ 
        return $this->number; 
    }
    public function setnumber(int $number):void{ 
        $this->number=$number; 
    }

    public function getdate():DateTime{ 
        return $this ->date; 
    }
    public function setdate(DateTime $date){ 
       $this->date= $date; 
    }

    public function getamount():float{ 
        return $this ->amount; 
    }
    public function setamount(float $amount){ 
        $this->amount= $amount; 
    }

    public function getpaid():float{ 
        return $this ->amount; 
    }
    public function setpaid(float $amount){ 
        $this->amount= $amount; 
    }


}

?>