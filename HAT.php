<?php
class HAT
{
protected $id=null;
protected $name=null;

public function publicMessages(){//функция вернет массив сообщений из бд ,не старше одного месяца
    $pdo = new PDO('mysql:"TEST";"hat"', "root", "");
    $stm = $pdo->query("use hat");
    $stm->execute();
    $stm = $pdo->prepare("SELECT data,publicMessages,sent,id FROM publicHAT WHERE data>?");//sent-отправленные,received-полученные
    $time=time()-2629743;
    $data=date('Y-m-d',$time);
    $stm->execute([$data]);
    $Messages = $stm->fetch(PDO::FETCH_LAZY);
    $messagesArray= get_object_vars($Messages);
    if(isset($messagesArray)) {
        return $messagesArray;
    }else{
        return false;
    }
}

public function register($name,$password){
    $pdo = new PDO('mysql:"TEST";"hat"', "root", "");
    $stm = $pdo->query("use hat");
    $stm->execute();
    $stm = $pdo->prepare("SELECT name,password FROM user WHERE name=?");//проверяем на совпадения
    $stm->execute([$name]);
    $nameMysql= $stm->fetch(PDO::FETCH_LAZY);
    if($nameMysql){
        return false;
        exit();
    }else{
        $passwordCash=password_hash($password, PASSWORD_DEFAULT);
        $stm = $pdo->prepare("INSERT INTO user VALUES (NULL,?,?)");//вносим записи в таблицу
        $stm->execute([$name,$passwordCash]);
        return true;
    }
}
}