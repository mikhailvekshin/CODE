<?php

//Для БД
$host = 'localhost'; //имя хоста, у меня на локальном компьютере это localhost
$user = 'root'; //имя пользователя, у меня по умолчанию это root
$password = 'root'; //пароль, у меня по умолчанию это root
$db_name = 'test'; //имя БД
$db_table_name = 'test'; //имя таблицы БД, используется ниже для запросов

//Соединяемся с БД
$connection=mysqli_connect($host, $user, $password, $db_name);
//Сообщение об ошибке соединения с БД
if (!$connection){
    die ('Error connection to database!');
}

?>