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
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/carousel.js"></script>
    <script type="text/javascript">
                $(document).ready(function() {     
                    if (window.matchMedia('screen and (max-width: 786px)').matches) {
                        $('.container').Carousel({
                        visible: 1,
                        rotateBy: 1,
                        speed: 1000,
                        btnNext: '#next',
                        btnPrev: '#prev',                      
                        auto: false,                       
                        backslide: true,   
                        margin: 10                     
                        });
                       
                        $('.container2').Carousel({
                        visible: 1,
                        rotateBy: 1,
                        speed: 1000,
                        btnNext: '#next2',
                        btnPrev: '#prev2',
                        position: "v",                     
                        auto: false,                       
                        backslide: true,   
                        margin: 10                     
                        });
                    } else {
                        $('.container').Carousel({
                        visible: 3,
                        rotateBy: 1,
                        speed: 1000,
                        btnNext: '#next',
                        btnPrev: '#prev',                      
                        auto: false,                       
                        backslide: true,   
                        margin: 10                     
                        });
                       
                        $('.container2').Carousel({
                        visible: 3,
                        rotateBy: 1,
                        speed: 1000,
                        btnNext: '#next2',
                        btnPrev: '#prev2',
                        position: "v",                     
                        auto: false,                       
                        backslide: true,   
                        margin: 10                     
                        });
                    }                        
                });
        </script>
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
        <!-- Бургер меню -->
        <nav class="mobile-menu" style="display: none;">
            <a href="index.php">
                <img src="img/logo.png" alt="Логотип" class="logo">
            </a>
            <input type="checkbox" id="checkbox" class="mobile-menu__checkbox">
            <label for="checkbox" class="mobile-menu__btn"><div class="mobile-menu__icon"></div></label>
            <div class="mobile-menu__container">
            <ul class="mobile-menu__list">
                <li class="mobile-menu__item"><a href="index.php" class="mobile-menu__link">Главная</a></li>
                <li class="mobile-menu__item"><a href="catalog.php" class="mobile-menu__link">Каталог</a></li>
                <li class="mobile-menu__item"><a href="#" class="mobile-menu__link">О нас</a></li>
                <li class="mobile-menu__item"><a href="#" class="mobile-menu__link">Контакты</a></li>
            </ul>
            </div>
        </nav>
        <!-- Бургер меню -->

        <p class="menu-catalog" style="margin-right: 300px; border-right: none;"><a href="catalog.php">Каталог</a></p>
        <p class="menu-catalog" style="margin-left: 300px;"><a href="#">О нас</a></p>
        <p class="menu-catalog" style="border-right: none;"><a href="#">Контакты</a></p>

        <a class="call" href="#openModal">Заказать звонок</a>
        <div class="auth">
            <?php
                if (isset($_SESSION['id_FIO'])){
                    if(isset($_SESSION['id'])){
                        echo '
                        <p class="privet">Добро пожаловать,<br>
                            <a href="admin.php">'.$_SESSION['id_FIO'].'</a>!
                        </p>';
                    } else if(isset($_SESSION['id_client'])) {
                        echo '
                        <p class="privet">Добро пожаловать,<br>
                            <a href="client.php">'.$_SESSION['id_FIO'].'</a>!
                        </p>';
                    } else if(isset($_SESSION['id_manager'])){
                        echo '
                        <p class="privet">Добро пожаловать,<br>
                            <a href="client.php">'.$_SESSION['id_FIO'].'</a>!
                        </p>';
                    }
                } else {
                    echo '<a class="log" href="client.php">Войти</a> 
                    <span>|</span>
                    <a class="reg" href="registration.php">Регистрация</a>';
                }
            ?>
        </div>
    </header>
    <main class="call-mobille">
        <h3>Заказать звонок</h3>
        <form action="index.php" method="POST">
            <label>Фамилия Имя Отчество:</label><i> *</i><br>
            <input type="text" name='FIO' required placeholder="Фамилия Имя Отчество"><br>
            <label>Номер телефона:</label><i> *</i><br>
            <input type="tel" name="phone" required placeholder="+7 (916) 123-45-67" value="+7" minlength="12"><br>
            <label for="agree" style="font-size: 11px;">Я даю свое согласие на обработку персональных данных:</label><i> *</i>
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
    </main>
    <footer style="position: absolute; bottom: 0; width: 96.1vw;">
        <?php
            if(isset($_SESSION['id'])) {
                echo '<a href="admin.php">Перейти в панель администратора</a>';
            } else {
                echo '<a href="admin.php">Вход для администратора</a>';
            }
        ?>
    </footer>
</body>
</html>