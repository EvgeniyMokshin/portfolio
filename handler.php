<?php
namespace content\aytForm;
use pdo;
$postName = htmlspecialchars($_POST['name']);
$postPassword = htmlspecialchars($_POST['password']);
if ($postName and $postPassword) {
    $pdo = new PDO('mysql:"html_realtor_client";"html_realtor_client"', "root", "");
    $stm = $pdo->query("use html_realtor_client");
    $stm->execute();
    $stm = $pdo->prepare("SELECT  id,name,cache,status FROM user WHERE name=?");
    $stm->execute([$postName]);
    $userBD = $stm->fetch(PDO::FETCH_LAZY);
    $passwordMysqlString = $userBD->cache;
    if (password_verify($postPassword, $passwordMysqlString)) {
        $_COOKIE['id']=$userBD->id;
        $_COOKIE['name']=$userBD->name;
        $_COOKIE['status']=$userBD->status;
        header('Location: index.php');

    }else{header('Location: index.php');}
}else{
    header('Location: index.php');
}

