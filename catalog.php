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
                <a href="admin.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться на панель администратора</span>
                </a>
                ';
            } else if(!isset($_SESSION['id_manager']) && !isset($_SESSION['id']) && !isset($_SESSION['id_client'])) {
                echo '
                <a href="index.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться на главную</span>
                </a>
                ';
            } else if(isset($_SESSION['id_client']) || isset($_SESSION['id_manager'])) {
                echo '
                <a href="client.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в личный кабинет</span>
                </a>
                ';
            }
        ?>
    </div>
    <form class="search" method="POST" action="search-result.php">
        <input type="text" name="search_q" placeholder="Искать по наименованию...">
        <button type="submit" name='search'><img src="img/lupe.png" alt="поиск"></button>
    </form>
    <h2>Каталог автогрейдеров</h2>
    <div class="catalog">
        <?php            
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            
            $sql_res = mysqli_query($link, "SELECT * FROM graders");

            while($row = mysqli_fetch_assoc($sql_res)){
                $id_grader = $row['id_grader']; 
                $name_grader = $row['name_grader'];  
                $engine_power = $row['engine_power'];
                $weight = $row['weight'];
                $blade_height = $row['blade_height'];
                $blade_width = $row['blade_width'];
                $price = $row['price'];
                $image = $row['image'];
                $id_owner = $row['id_owner'];

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

                //Удаление заказов, связанных с автогрейдером

                require_once 'connection.php'; // подключаем скрипт
                $link = mysqli_connect($host, $user, $password, $database) 
                    or die("Ошибка " . mysqli_error($link));

                $sql = mysqli_query($link, "SELECT COUNT(*) FROM graders NATURAL JOIN orders WHERE id_grader={$_POST['delid']}");
                $count = mysqli_fetch_row($sql)[0];
                if($count > 1) {
                    $sql = mysqli_query($link, "DELETE FROM `orders` WHERE `id_grader` = {$_POST['delid']}");
                    $sql = mysqli_query($link, "DELETE FROM `graders` WHERE `id_grader` = {$_POST['delid']}");
                    if ($sql) {
                        print("<script type='text/javascript'>
                                window.alert('Автогрейдер удален!'');
                                </script>");
                                echo "<script>window.location = 'catalog.php'</script>";
                    } else {
                        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
                    }
                } else if ($count == 0){
                    //удаляем автогрейдер
                    $sql = mysqli_query($link, "DELETE FROM `graders` WHERE `id_grader` = {$_POST['delid']}");
                    if ($sql) {
                        print("<script type='text/javascript'>
                                window.alert('Автогрейдер удален!');
                                </script>");
                                echo "<script>window.location = 'catalog.php'</script>";
                    } else {
                        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
                    }
                    // echo "<script>window.location = 'catalog.php'</script>";
                }
            }
        ?>
    </div>
    <div id="openModal" class="modalDialog">
        <div>
            <div class="container-close">
                <a href="#close" title="Close" class="close"><img src="img/close.png" alt="закрыть" class="close"></a>
            </div>
            <h3>Заказать звонок</h3>
            <form action="index.php" method="POST">
                <label>Фамилия Имя Отчество:</label><i> *</i><br>
                <input type="text" name='FIO' required placeholder="Фамилия Имя Отчество"><br>
                <label>Номер телефона:</label><i> *</i><br>
                <input type="tel" name="phone" required placeholder="+7 (916) 123-45-67" value="+7" minlength="12"><br>
                <label for="agree" style="font-size: 12px;">Я даю свое согласие на обработку персональных данных:</label><i> *</i>
                                <input name="agree" type="checkbox" id="agree" required><br>
                            <sub><i>* Поля, отмеченные звездочкой, обязательны для заполнения</i></sub><br><br>
                <input class="call" name="call" type="submit" value="Оставить заявку"></input>
            </form>
        <?php
            require_once 'connection.php'; // подключаем скрипт

            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
                or die("Ошибка ".mysqli_error($link));
            
            // если были переданы данные для добавления в БД
            if( isset($_POST['call'])) {
                $sql_res = mysqli_query($link, "SELECT * FROM users WHERE role = 'manager'");
                for ($i=0; $i < mysqli_num_rows($sql_res); $i++) {
                    $row = mysqli_fetch_array($sql_res, MYSQLI_ASSOC);
                    $manager_id = $row['id_user'];
                }
                
                $sql_res_prod=mysqli_query($link, 'INSERT INTO requests (`FIO`, `phone`, `status`, `purpose`, `id_user`) VALUES (
                    "'.htmlspecialchars($_POST['FIO']).'",
                    "'.htmlspecialchars($_POST['phone']).'",
                    "Ожидает звонка",
                    "", "'.$manager_id.'")');
                
                // если при выполнении запроса произошла ошибка – выводим сообщение
                if( mysqli_errno($link) )
                    echo '<script>alert("Заявка не создана. Повторите.");</script>';
                else // если все прошло нормально – выводим сообщение
                    echo '<script>alert("Заявка на обратный звонок успешно создана! Ожидайте звонка.");</script>';
            }              
        ?>
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