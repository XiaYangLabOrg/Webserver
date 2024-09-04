<?php
class Sessions {
    public $prevPage  = "";
    public $curPage   = "";
    public $sessionID = "";
    public function __construct(?string $prevPage, ?string $curPage){
        #Write to SQL
        $this->prevPage = $prevPage;
        $this->curPage = $curPage;
    }
    public function setPrevPage(?string $prevPage){

    }
    public function readPrevPage(?string $prevPage){
        
    }
    private function generateSession($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sessionID = '';
        for ($i = 0; $i < $length; $i++) {
            $sessionID .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $sessionID;
    }

    function loadSession($sessionID){

    }
  
}
?>