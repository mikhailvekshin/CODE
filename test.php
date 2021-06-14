<?php

/*
//БД:
$host = 'localhost'; //имя хоста, у меня на локальном компьютере это localhost
$user = 'root'; //имя пользователя, у меня по умолчанию это root
$password = 'root'; //пароль, у меня по умолчанию это root
$db_name = 'test'; //имя БД
$db_table_name = 'test'; //имя таблицы БД, используется ниже для запросов
*/

final class MyClass{

  public function create()
    {
        //Подключаем файл 'connection.php' для соединения с БД
        require_once 'connection.php';

        //Формируем текстовый запрос для создания в БД новой таблицы
        $query = "CREATE TABLE `$db_name`.`$db_table_name` ( `id` INT NOT NULL AUTO_INCREMENT , `script_name` VARCHAR(25) NULL DEFAULT NULL , `sort_index` INT NULL DEFAULT NULL, `result` ENUM ('normal','illegal','failed','success') NULL DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        //Делаем запрос к БД, результат запроса пишем в $result_create
        $result_create = mysqli_query($connection, $query);

        //Для отладки проверяем что же нам отдала база данных, если null – то какие-то проблемы
        var_dump($result_create);
    }

    public function fill()
    {

        //Подключаем файл 'connection.php' для соединения с БД
        require_once 'connection.php';

        //Делаем цикл для заполнения БД 1000 строк
        for ($num_rows_to_fill=1;$num_rows_to_fill<=1000;$num_rows_to_fill++ ) {
            //Формируем случайные данные для заполнения таблицы БД
            $num_script_name = array_rand(range(1, 999));
            $sort_indexs = range(1, 999);
            $sort_index = array_rand($sort_indexs);
            $results=array("normal", "illegal", "failed", "success", "success");
            $result= array_rand($results);

            //Формируем текстовый запрос в БД для вставки новой строки в таблицу
            $query = "INSERT INTO `$db_table_name` (`id`, `script_name`, `sort_index`, `result`) VALUES (NULL, 'script_name$num_script_name', '$sort_index', '$result')";

            //Делаем запрос к БД, результат запроса пишем в $result_fill:
            $result_fill = mysqli_query($connection, $query);

            //Для отладки проверяем что же нам отдала база данных, если null – то какие-то проблемы
            var_dump($result_fill);

             }
    }

    public function get()
    {

        //Подключаем файл 'connection.php' для соединения с БД
        require_once 'connection.php';

        //Формируем текстовый запрос в БД для вставки новой строки в таблицу
        $query = "SELECT * FROM `$db_table_name` WHERE `result`='normal' OR `result`='success' ORDER BY `sort_index` DESC LIMIT 20";

        //Делаем запрос к БД, результат запроса пишем в $result_fill
        $result_get = mysqli_query($connection, $query);

        //Для отладки проверяем что же нам отдала база данных, и выводим массив даных
        for ($data = []; $row = mysqli_fetch_assoc($result_get); $data[] = $row);

        var_dump($data);

    }

}


$MyClass = new MyClass();
$MyClass -> create();
$MyClass -> fill();
$MyClass -> get();


/*
//Для отладки удаляем таблицу в БД
require_once 'connection.php';
$query = "DROP TABLE `$db_name`.`$db_table_name`;"; //формируем текстовый запрос в БД для удаления таблицы в БД
$result_delete = mysqli_query($connection, $query) or die(mysqli_error($connection)); //делаем запрос к БД, результат запроса пишем в $result_delete:
var_dump($result_delete); //для отладки проверяем что же нам отдала база данных, если null – то какие-то проблемы
*/
?>

