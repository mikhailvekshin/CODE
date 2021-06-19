<?php

//Создаем класс MyClass от которого нельзя наследоваться
final class MyClass
{
    //Использовал следующие данные для подключения
    const HOST = '127.0.0.1'; //имя хоста, у меня на локальном компьютере - 127.0.0.1
    const USER = 'root'; //имя пользователя, у меня по умолчанию это root
    const PASSWORD = 'root'; //пароль, у меня по умолчанию это root
    const DB_NAME = 'test'; //имя БД
    const CHARSET = 'utf8'; //кодировка utf8

    private static $dbh; //создаем статическую переменную для подключения к БД

    //конструктор
    private function __construct()
    {
        $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::HOST . ';charset=' . self::CHARSET; //переменная $dns для PDO

        //подключение к БД через PDO
        self::$dbh = new PDO($dsn, self::USER, self::PASSWORD);

        //вызываем два приватных метода create() и fill()
        $this->create();
        $this->fill();
    }

    //определяем создан ли ранее класс MyClass
    public function getMyClass()
    {
        if (self::$dbh != NULL) {
            return self::$dbh;//если создан, то возвращаем ранее созданный
        }
        return new self;//если не создан, то запускаем конструктор
    }

    //метод get()
    public function get()
    {
        //запрос БД отсортированных по полю sort_index в порядке убывания у которых значение поля result находится среди значений 'normal' и 'success'
        $sql = "SELECT * FROM test
              WHERE result='normal' OR result='success'
              ORDER BY sort_index DESC LIMIT 20";
        return self::$dbh->query($sql)->fetchAll();

    }

    //метод create()
    private function create()
    {
        //запрос БД для создания таблицы test
        $sql = "CREATE TABLE test ( id INT NOT NULL AUTO_INCREMENT , script_name VARCHAR(25) NULL DEFAULT NULL , sort_index INT NULL DEFAULT NULL, result ENUM ('normal','illegal','failed','success') NULL DEFAULT NULL, PRIMARY KEY (id)) ENGINE = InnoDB;";
        self::$dbh->query($sql);

    }

    //метод fill()
    private function fill()
    {
        //запрос БД для заполнения таблицы test
        $sql = "INSERT INTO test (script_name, sort_index, result) VALUES";
        $values = array();//формуруем переменную для массива данных
        //Делаем цикл для заполнения таблицы БД 1000 строками
        for ($num_rows_to_fill = 1; $num_rows_to_fill <= 1000; $num_rows_to_fill++) {
            //Формируем случайные данные для заполнения таблицы БД
            $num_script_name = array_rand(range(1, 999));
            $script_name = 'script_name' . $num_script_name;//для поля script_name
            $sort_indexs = range(1, 999);
            $sort_index = array_rand($sort_indexs);//для поля sort_index
            $results = array("normal", "illegal", "failed", "success","success");//2 раза присутствует "success", потому что у меня при заполнении вообще
            //не появляется в таблице БД поле "resulst" со значением "success"
            $result = array_rand($results);

            $values[] = "('$script_name', '$sort_index', '$result')"; //формируем массив данных для заполнения
        }
        $sql = $sql . implode (',', $values);//формируем запрос в БД с массивом данных
        self::$dbh->query($sql);
    }
    //создаем приватный метод, чтобы никто не смог его склонировать
    private function __clone() {

    }

}
//инициализируем MyClass
$DB=MyClass::getMyClass();
//для предварительной отладки
//print_r ($DB -> get());
//вызываем публичный метод get()
$posts = $DB -> get();

//Для отображения результата во вкладке браузера
//В виде таблицы
echo "<table border=\"1\">";//заголовок таблицы
echo "<th><b>id</b><th><b>script_name</b><th><b>sort_index</b><th><b>result</b></th>";

//вывод данных на экран с помощью цикла foreach
foreach ($posts as $post)
{
    echo "<tr><th><b>$post[id]</b></th><th><b>$post[script_name]</b></th><th><b>$post[sort_index]</b></th><th><b>$post[result]</b></th></tr>";
}
?>