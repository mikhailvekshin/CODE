<?php

//Использовал следующие переменные:
//для подключения
$HOST = '127.0.0.1'; //имя хоста, у меня на локальном компьютере - IP 127.0.0.1
$USER = 'root'; //имя пользователя, у меня по умолчанию это root
$PASSWORD = 'root'; //пароль, у меня по умолчанию это root
$DB_NAME = 'library'; //имя БД
$CHARSET = 'utf8'; //кодировка utf8
//для таблиц БД
$db_table_name_1 = 'books';//имя таблицы №1 БД
$db_table_name_2 = 'authors';//имя таблицы №2 БД
$db_table_name_3 = 'rel';//имя таблицы №3 БД
$db_collumn_name_1 = 'books_name';//имя столбца таблицы №1 БД
$db_collumn_name_2 = 'authors_name';//имя столбца таблицы №2 БД
$db_collumn_name_3 = 'id_books';//имя столбца таблицы №3 БД
$db_collumn_name_4 = 'id_authors';//имя столбца таблицы №3 БД

$dsn = "mysql:dbname=$DB_NAME;host=$HOST;charset=$CHARSET"; //переменная $dns для PDO

//подключение к БД через PDO

    $dbh = new PDO($dsn, $USER, $PASSWORD);

//Для создания таблицы №1 (где будут все книги) формируем запрос в БД
$sql = "CREATE TABLE books ( id INT NOT NULL AUTO_INCREMENT , books_name VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (id)) ENGINE = InnoDB;";//текст запроса
$STH = $dbh->prepare($sql);//подготавливаем запрос с помощью метода prepare()
$STH->execute();//передаем данные запроса с помощью метода execute()

//Для создания таблицы №2 (где будут все авторы) формируем запрос в БД
$sql = "CREATE TABLE authors ( id INT NOT NULL AUTO_INCREMENT , authors_name VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (id)) ENGINE = InnoDB;";//текст запроса
$STH = $dbh->prepare($sql);//подготавливаем запрос с помощью метода prepare()
$STH->execute();//передаем данные запроса с помощью метода execute()

//Для создания таблицы №3 (где хранятся связи книг с авторами) формируем запрос в БД
$sql = "CREATE TABLE rel ( id INT NOT NULL AUTO_INCREMENT , id_books INT NULL DEFAULT NULL , id_authors INT NULL DEFAULT NULL , PRIMARY KEY (id)) ENGINE = InnoDB;";//текст запроса
$STH = $dbh->prepare($sql);//подготавливаем запрос с помощью метода prepare()
$STH->execute();//передаем данные запроса с помощью метода execute()


//формируем запрос в БД

$sql = "SELECT books.books_name, COUNT(rel.id_books) as count_authors, rel.id_books
        FROM books 
        LEFT JOIN rel ON rel.id_books=books.id
        LEFT JOIN authors ON authors.id = rel.id_authors
        GROUP BY rel.id_books, books.books_name 
        HAVING count_authors >=3 
        ORDER BY count_authors DESC";

$STH = $dbh->prepare($sql);//подготавливаем запрос с помощью метода prepare()
$STH->execute();//передаем данные запроса с помощью метода execute()
$posts = $STH->fetchAll();//получаем данные запроса с помощью метода fetchAll()



//Для отображения результата во вкладке браузера
//В виде таблицы
echo "<table border=\"1\">";//заголовок таблицы
echo "<th><b>Имя книги</b><th><b>Количество авторов</b></th>";

//вывод данных на экран с помощью цикла foreach
foreach ($posts as $post)
{
    echo "<tr><th><b>$post[books_name]</b></th><th><b>$post[count_authors]</b></th></tr>";
}

/*
//Для отладки:
//формируем запрос в БД
$sql = "SELECT authors.id AS id_authors, authors.authors_name, books.id AS id_books, books.books_name, rel.id AS id_rel
        FROM books 
        LEFT JOIN rel ON rel.id_books=books.id
        LEFT JOIN authors ON authors.id = rel.id_authors
        ORDER BY books.id DESC";

$STH = $dbh->prepare($sql);//подготавливаем запрос с помощью метода prepare()
$STH->execute();//передаем данные запроса с помощью метода execute()
$posts = $STH->fetchAll();//получаем данные запроса с помощью метода fetchAll()


//Для отображения результата во вкладке браузера
//В виде таблицы
echo "<table border=\"1\">";//заголовок таблицы
echo "<th><b>id связи</b><th><b>id книги</b><th><b>Название книги</b><th><b>id автора</b><th><b>Имя автора</b></th>";

//вывод данных на экран с помощью цикла foreach
foreach ($posts as $post)
{
    echo "<tr><th><b>$post[id_rel]</b></th><th><b>$post[id_books]</b></th><th><b>$post[books_name]</b></th><th><b>$post[id_authors]</b></th><th><b>$post[authors_name]</b></th></tr>";
}

/*

//Формируем данные для заполнения таблицы №1 БД
$sql_f1 = "INSERT INTO $db_table_name_1 ($db_collumn_name_1) VALUES";//формируем текстовый запрос в БД без данных
$values = array();//массив данных для заполнения таблицы №1 БД
for ($num_rows_to_fill = 1; $num_rows_to_fill <= 20; $num_rows_to_fill++) {
    //формируем имя книги
    $num_books_name = $num_rows_to_fill;
    $name = $db_collumn_name_1 . $num_books_name;
    $values [] = "('$name')";//формируем массив данных для заполнения
}
$sql_f1 = $sql_f1 . implode (',', $values);//формируем запрос в БД с массивом данных
$dbh->query($sql_f1);

//Делаем цикл для заполнения таблицы №2 БД 10 строками
$sql_f2 = "INSERT INTO $db_table_name_2 ($db_collumn_name_2) VALUES";//формируем текстовый запрос в БД без данных
$values = array();//массив данных для заполнения таблицы №2 БД
for ($num_rows_to_fill = 1; $num_rows_to_fill <= 20; $num_rows_to_fill++) {
    //формируем имя автора
    $num_authors_name = $num_rows_to_fill;
    $name = $db_collumn_name_2 . $num_authors_name;
    $values [] = "('$name')";//формируем массив данных для заполнения
}
$sql_f2 = $sql_f2 . implode (',', $values);//формируем запрос в БД с массивом данных
$dbh->query($sql_f2);

//Делаем цикл для заполнения таблицы №3 БД строками
$sql_f3 = "INSERT INTO $db_table_name_3 ($db_collumn_name_3,$db_collumn_name_4) VALUES";//формируем текстовый запрос в БД без данных
$values = array();//массив данных для заполнения таблицы №3 БД
for ($books = 1; $books <= 20; $books++) {//id книг в таблице №1 БД

    for ($authors = 1; $authors <= array_rand(range(1,10)); $authors++) {

        $name_authors = range(1,20);//id авторов в таблице №2 БД
        $name_author = array_rand($name_authors);//случайный выбор из 20 авторов
        $values [] = "('$books','$name_author')";//формируем массив данных для заполнения
    }
}
$sql_f3 = $sql_f3 . implode (',', $values);//формируем запрос в БД с массивом данных
$dbh->query($sql_f3);
*/
?>