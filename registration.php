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
            <a href="index.php">Аренда автогрейдеров</a>    
        </span>
        <button class="call">Заказать звонок</button>
        <div class="auth">
            <a class="log" href="client.php">Войти</a> 
            <span>|</span>
            <a class="reg" href="registration.php">Регистрация</a>
        </div>
    </header>
    <main>
        <div id="registration">
            <h1>Регистрация</h1>
            <form action="registration.php" method="POST" enctype="multipart/form-data" class="big-form">
                <label for="FIO">Фамилия Имя Отчество:</label><i> *</i><br>
                    <input name="FIO" type="text" required placeholder="Фамилия Имя Отчество"><br>
                <label for="email">E-mail:</label><i> *</i><br>
                    <input type="email" name="email" rows="5" required placeholder="example@mail.ru"></textarea><br>
                <label for="phone">Телефон:</label><i> *</i><br>
                    <input type="text" name="phone" rows="5" required placeholder="+7(977) 182 73 29" value="+7"></textarea><br>
                <label for="pass">Пароль:</label><i> *</i><br>
                    <input name="pass" type="password" required><br>
                <label for="password">Повторите пароль:</label><i> *</i><br>
                    <input name="password" type="password" required><br>
                <input name="agree" type="checkbox" id="agree" required>
                <label for="agree">Я даю свое согласие на обработку персональных данных</label><i> *</i><br>
                <i>* Поля, отмеченные звездочкой, обязательны для заполнения</i><br><br>
                <button name="button" value="Сохранить" class="btn-success">Зарегистрироваться</button>
                <br><br><br><br>
            </form>

            <?php 
                require_once 'connection.php'; // подключаем скрипт
                
                // подключаемся к серверу
                $link = mysqli_connect($host, $user, $password, $database) 
                    or die("Ошибка ".mysqli_error($link));
                
                // если были переданы данные для добавления в БД
                if( isset($_POST['button']) && $_POST['button']== 'Сохранить' ) {
                    if($_POST['pass'] == $_POST['password']){
                        $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
                        $sql_res_prod=mysqli_query($link, 'INSERT INTO users (`FIO`, `email`, `phone`, `password`, `role`) VALUES (
                            "'.htmlspecialchars($_POST['FIO']).'",
                            "'.htmlspecialchars($_POST['email']).'",
                            "'.htmlspecialchars($_POST['phone']).'",
                            "'.$pass_hash.'",
                            "client")');
                        
                        // если при выполнении запроса произошла ошибка – выводим сообщение
                        if( mysqli_errno($link) )
                        echo '<hr><div class="error col" style="color:red; font-size:20px; margin: 20px;">Вы не зарегистрированы</div>'.mysqli_error($link);
                        else // если все прошло нормально – выводим сообщение
                        echo '<hr><div class="ok col" style="color:green; font-size:20px; margin: 20px;">Вы зарегистрированы</div>';
                    } else {
                    echo '<hr><div class="error col" style="color:red; font-size:20px; margin: 20px;">Введеные пароли не совпадают</div>'.mysqli_error($link);
                }
            }
            ?>
        </div>
    </main>
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