<?php
    session_start(); 
?> 
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="img/favicon.ico" rel="icon" type="image/x-icon" />
    <title>Аренда автогрейдеров</title>
</head>
<body>
    <header>
        <a href="index.php">
            <img src="img/logo.png" alt="Логотип" class="logo">
        </a>
        <span class="text-logo">
            <a href="index.php">
                Аренда автогрейдеров
            </a>    
        </span>
        <p class="menu-catalog" style="margin-right: 300px; border-right: none;"><a href="index.php">Главная</a></p>
        <p class="menu-catalog" style="margin-left: 0; border-right: none;"><a href="catalog.php">Каталог</a></p>
        <p class="menu-catalog" style="margin-left: 600px;"><a href="#">О нас</a></p>
        <p class="menu-catalog" style="margin-left: 300px;  border-right: none;"><a href="#">Контакты</a></p>

    </header>
    <div class="back">
        <?php
            if(isset($_SESSION['id'])){
                echo '
                <a href="catalog.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в каталог</span>
                </a>
                ';
            } else if(!isset($_SESSION['id_manager']) && !isset($_SESSION['id']) && !isset($_SESSION['id_client'])) {
                echo '
                <a href="catalog.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в каталог</span>
                </a>
                ';
            } else if(isset($_SESSION['id_client']) || isset($_SESSION['id_manager'])) {
                echo '
                <a href="catalog.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в каталог</span>
                </a>
                ';
            }
        ?>
    </div>
    <h2>Результат поиска по каталогу автогрейдеров</h2>
    <div class="catalog">
    <?php
        if(isset($_POST['search'])){
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            
            $search_q=$_POST['search_q'];
            $search_q = trim($search_q);
            $search_q = strip_tags($search_q);
            $q = mysqli_query($link, "SELECT * FROM `graders` WHERE name_grader LIKE '%$search_q%'");

            while ($itog = mysqli_fetch_assoc($q)) {
                $id_grader = $itog['id_grader']; 
                $name_grader = $itog['name_grader'];  
                $engine_power = $itog['engine_power'];
                $weight = $itog['weight'];
                $blade_height = $itog['blade_height'];
                $blade_width = $itog['blade_width'];
                $price = $itog['price'];
                $image = $itog['image'];

                echo '<div class="catalog-card">
                <img src="'.$image.'" alt="автогрейдер" class="photo-grader">
                <p>Наименование: '.$name_grader.'</p>
                <p>Мощность двигателя: '.$engine_power.' л.с.</p>
                <p>Масса: '.$weight.' тонн</p>
                <p>Длина лезвия: '.$blade_height.' м.</p>
                <p>Ширина лезвия: '.$blade_width.' м.</p>
                <p>Стоимость: '.$price.' руб.</p>';
                if(isset($_SESSION['id'])) {
                    echo "
                        <form action='edit-grader.php' method='POST'>
                            <input type='hidden' name='edid' value='$id_grader'/>
                            <input type='submit' class='btn-success' value='Редактировать'>
                        </form>
                        <form action='catalog.php' method='POST'>
                            <input type='hidden' name='delid' value='$id_grader' />
                            <input type='submit' class='btn-danger' value='Удалить'>
                        </form>
                    ";
                } else if(isset($_SESSION['id_client'])){
                    echo "
                        <form action='catalog.php' class='form-client-catalog' method='POST'>
                            <input type='hidden' name='id_grader' value='$id_grader'/>
                            <input type='submit' class='btn-success' name='rent-request' value='Подать заявку на аренду'>
                        </form>
                    ";
                }
                echo '</div>';
            }
            mysqli_free_result($q);
            mysqli_close($link);
        }
        ?>
        <?php            
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            // если была нажата кнопка Подать заявку на аренду
            if (isset($_POST['rent-request'])) { 
                $id_user = $_SESSION['id_client'];
                //Вставляем строку
                $sql=mysqli_query($link, 'INSERT INTO orders (`id_grader`, `id_user`, `status`) VALUES (
                    "'.htmlspecialchars($_POST['id_grader']).'",
                    "'.$id_user.'",
                    "Принято к исполнению")');
                if ($sql) {
                    echo "<p style='color: green'>Заявка принята к исполнению.</p>";
                } else {
                    echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
                }
                // echo "<script>window.location = 'catalog.php'</script>";
            }

            // если былы нажата кнопка удалить
            if (isset($_POST['delid'])) { //проверяем, есть ли переменная
                //удаляем строку из таблицы
                $sql = mysqli_query($link, "DELETE FROM `graders` WHERE `id_grader` = {$_POST['delid']}");
                if ($sql) {
                    echo "<p>Автогрейдер удален.</p>";
                } else {
                    echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
                }
                echo "<script>window.location = 'catalog.php'</script>";
            }
        ?>
        </div>
    </div>
    </div>
    <footer>
        <?php
            if(isset($_SESSION['id'])) {
                echo '<a href="/admin.php">Перейти в панель администратора</a>';
            } else {
                echo '<a href="/admin.php">Вход для администратора</a>';
            }
        ?>
    </footer>
</body>
</html>


