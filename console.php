<html>
<head>
</head>
<body>

<form action='/console.php' method='post'>
    <th><b>Введите команду dirFilt_ixt</b><th>
        <input type="text" name="command">

</form>
</body>
</html>
<?php
//Для отладки использовал окно браузера с полем ввода, пока не понял как сделать консоль и выполнить там команду
//для приема данных из HTML в php использовал метод $_POST
//если в поле введено какое-либо значение, то показываем его ниже
if(isset($_POST['command'])) {
    echo $_POST['command'] . ' >><br><br>';

//если введенное в поле строка соответствует команде, то выполняем поиск и фильтрацию в папке \datafiles
    if ($_POST['command'] == "dirFilt_ixt" ) {
        //папка для поиска, у меня путь к папке \datafiles - C:\openserver\domains\console\datafiles
        $dir = 'C:\openserver\domains\console\datafiles';
        //открываем папку для поиска и записываем весь массив данных в переменную $files
        $files = scandir($dir);
        //отбираем все файлы, состоящие только из латинских букв и цифр
        $filter_files = preg_grep("/[0-9a-zA-Z]+/", $files);
        //отбираем все файлы, которые не начинается с цифры
        $filter_files_begin = preg_grep("/^[a-zA-Z]+/", $filter_files);
        //отбираем все файлы, которые заканчиваются цифрой и с расширением .ixt
        $filter_files_end_extension = preg_grep("/[0-9].ixt$/", $filter_files_begin);
        //cортировка массива данных по алфавиту без учета регистра
        $sort_filter_files = natcasesort($filter_files_end_extension);
        //Вывод всех данных построчно, цикл по всем элементам массива
        foreach ($filter_files_end_extension as $elem) {

            echo $elem . '<br>';

        }
    }
    //если введенное в поле строка не соответствует команде, то выводим ошибку
    else {
        print_r("Введите правильно команду dirFilt_ixt");
    }
}

?>

