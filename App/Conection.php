<?php
namespace App;

class Conection{

    protected $conection;

    public function __constructor(){
        $this->conection = $this->conection();
    }

    public function conection(){
        try {
            $server = 'localhost';
            $db = 'test';
            $user = 'root';
            $pwd = '';
            $conect = mysqli_connect($server, $user, $pwd, $db);
            $this->conection = $conect;
            return $conect;
        } catch (\Throwable $th) {
            //throw $th;
            return die(mysqli_error($conect));
        }
    }

}

