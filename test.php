<?php
const FILE_NAME = "message.json";
const DIR_NAME_MESSAGE = __DIR__."/data/";
const FILE_DATA_MESSAGE = DIR_NAME_MESSAGE.FILE_NAME;
$shortopts  = "";

$longopts  = array(
    "list::",
    "select::",
    "insert::"
);
$options = getopt($shortopts, $longopts);

if(!file_exists(FILE_DATA_MESSAGE)) $fp = fopen(FILE_DATA_MESSAGE, "w");

if(isset($options['list'])){ //Просмотр всего списка: php  test.php --list
        getItem();
}

if(isset($options['insert'])){ //Вставка: php test.php --insert='{"d_create": "2021-01-01","payload": "message 32432"}'
        $string_ins = $options['insert'];
        $string_select = file_get_contents(FILE_DATA_MESSAGE);
        $data_select = (!isJson($string_select)) ? array() : json_decode($string_select,true);


        if(!isJson($string_ins)) die('Входяща стрічка не є валідним json. Приклад: {"d_create": "2021-01-01","payload": "message 32432"}');
        $data_ins = json_decode($string_ins,true);

        if(strlen($data_ins['payload'])>250) die("Довжина входящої стрічки не повина перевищувати 250 символів");
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data_ins['d_create'])) die("Дата повинна бути в форматі 2012-09-12");

        $data_select[]=$data_ins;

        file_put_contents(FILE_DATA_MESSAGE, json_encode($data_select));
}

if(isset($options['select']) and (int)$options['select']>0){ //Выборка по id: php  test.php --select="1"
        $id = (int)$options['select'];
        getItem($id);
}


    function isJson($string){
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
    }

    function getItem($id = 'all'){
        $string_select = file_get_contents(FILE_DATA_MESSAGE);
        $data_select = json_decode($string_select,true);

        $arr = (empty($key)) ? $data_select : $data_select[$key];
        foreach($data_select as $key=>$item){
            if($id == $key){
                            echo"----------------------------------------------------".PHP_EOL;
                            echo "| ".$key." | ".$item['d_create']." | ".$item['payload']." |".PHP_EOL;
                            echo"----------------------------------------------------".PHP_EOL;
            }elseif($id == 'all'){
                            echo"----------------------------------------------------".PHP_EOL;
                            echo "| ".$key." | ".$item['d_create']." | ".$item['payload']." |".PHP_EOL;
                            echo"----------------------------------------------------".PHP_EOL;
            }
        }
    }
?>