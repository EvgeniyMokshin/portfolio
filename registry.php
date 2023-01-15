<?php

namespace pattern_class\registry;
use pdo;
class registry
{
    /*****************************************************************************************************************
     *                                                   свойства
     **************************************************************************************************************** */
    protected static array $global_array_list = array();//публичный массив
    public array $public_array=array();//публичный массив обьекта ,доступный для взаимодействия
    private array $pass=array();//массив для кешированных данных с бд ,со столбцами ид,кэш
    private array $private_array=array();//приватный массив для конфициальной информации ,после получение пары,данные кешируются и присваивается ид
//create table array_cache (id varchar(12) not null ,cache CHAR(60) not null);
    /*****************************************************************************************************************
     *                                            функция загрузки в базу данных
     ******************************************************************************************************************/
    public function load_string_db(string $key, string $value): bool
    {
        $pdo = new PDO('mysql:"pattern";"pattern"', "root", "");
        $stm = $pdo->query("use pattern");
        $stm->execute();
        $stm = $pdo->prepare("SELECT key_array,value_array FROM array WHERE key_array=?");
        $stm->execute([$key]);
        $array = $stm->fetch(PDO::FETCH_ASSOC);
        if (!$array['key_array']) {
            $stm = $pdo->prepare("INSERT INTO array VALUES (?,?)");
            $stm->execute([$key, $value]);
            return true;
        }
        return false;
    }

    /*****************************************************************************************************************
     * удаления из базы данных ключа
     ******************************************************************************************************************/
    public function delete_db(string $key): bool
    {
        if ($key !== '') {
            $pdo = new PDO('mysql:"pattern";"pattern"', "root", "");
            $stm = $pdo->query("use pattern");
            $stm->execute();
            $stm = $pdo->prepare("DELETE FROM array WHERE key_array=?");
            $stm->execute([$key]);
            return true;
        }
        return false;
    }

    /*****************************************************************************************************************
     *функция выгружает все или определенные ключи из бд и возвращяет свойство обьекта класса ,registry
     ******************************************************************************************************************/
    public function viewing_db_registry(string $key,$all = false):bool
    {

        if ($key!='' and $all===false ) {
            $pdo = new PDO('mysql:"pattern";"pattern"', "root", "");
            $stm = $pdo->query("use pattern");
            $stm->execute();
            $stm = $pdo->prepare("SELECT key_array,value_array FROM array WHERE key_array=?");
            $stm->execute([$key]);
            $return=$stm->fetch(PDO::FETCH_ASSOC);
            $this->public_array[]=$return;
            return true;
        } elseif ($key!='' and $all===true) {
            $pdo = new PDO('mysql:"pattern";"pattern"', "root", "");
            $stm = $pdo->query("use pattern");
            $stm->execute();
            $sql = 'SELECT key_array,value_array FROM array';
            foreach ($pdo->query($sql) as $row) {
                $return[]=array(
                    $row['key_array']=>$row['value_array'],
                );
            }
            $this->public_array= $return;
            return true;
        } else {
            return false;
        }
    }
    /*****************************************************************************************************************
     *                                     работа со свойством  global_array_list
     *****************************************************************************************************************/
    /*****************************************************************************************************************
     * записываем ключ и значение в свойство обьекта global_array_list
     *****************************************************************************************************************/
    public static function load_Registry_object_string(string $key, string $value):bool
    {
        self::$global_array_list[] = [
            $key => $value,
        ];
        return true;
    }
    /*****************************************************************************************************************
    удаление ключей массива $global_array_list
     *****************************************************************************************************************/
    public function delete_Registry_array(string $key):bool
    {
        if (self::$global_array_list[$key]){
            unset(self::$global_array_list[$key]);
            return true;
        }else{
            return false;
        }
    }
    /*****************************************************************************************************************
     просмотр публичного свойства global_array_list
     *****************************************************************************************************************/
    public static function viewing_Registry_array(string $key=null):array
    {
        if($key!=null){
            return self::$global_array_list[$key];
        }else{
            return self::$global_array_list;
        }
    }

}

$test=new registry();
$key="66666";
$value="78888877";
$resulf=$test->viewing_db_registry($key,true);
var_dump($resulf);
echo "<br>";
echo "ключ=";
var_dump($key);
echo "<br>";
echo "value=";
var_dump($value);
echo "<br>";
echo "свойство обьекто=";
print_r($test->public_array);

