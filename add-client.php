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
    </header>
    <?php   
    if(isset($_SESSION['id_manager'])) {
            echo '
            <div class="back">
                <a href="list-clients.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список клиентов</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Добавление нового клиента</h2>
                <form action="add-client.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <label for="fio">ФИО клиента:</label><i> *</i><br>
                        <input id="fio" name="fio" type="text" required><br>
                     <label for="email">Email:</label><i> *</i><br>
                        <input name="email" type="email" id="email" required><br>
                      <label for="phone">Телефон:</label><i> *</i><br>
                        <input name="phone" type="text" id="phone" value="+7" required><br>
                      <label for="pass">Пароль:</label><i> *</i><br>
                        <input name="pass" type="password" required><br>
                      <label for="password">Повторите пароль:</label><i> *</i><br>
                        <input name="password" type="password" required><br>
                      <input class="btn-success" type="submit" name="save" value="Сохранить"></input>
                  </form>
                </div>';
        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        // если были переданы данные для добавления в БД
        if( isset($_POST['save'])) {          
        if($_POST['pass'] == $_POST['password']){
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);     

            $sql_res_prod=mysqli_query($link, 'INSERT INTO users (`FIO`, `email`, `phone`, `password`, `role`) VALUES (
                "'.htmlspecialchars($_POST['fio']).'",
                "'.htmlspecialchars($_POST['email']).'",
                "'.htmlspecialchars($_POST['phone']).'", 
                "'.$pass_hash.'",
                "client")');
            
            // если при выполнении запроса произошла ошибка – выводим сообщение
            if( mysqli_errno($link) )
            echo '<hr><div class="error col" style="color:red; font-size:20px">Информация о пользователе не добавлена</div>'.mysqli_error($link);
            else // если все прошло нормально – выводим сообщение
            echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о пользователе добавлена</div>';
        } else {
            echo '<hr><div class="error col" style="color:red; font-size:20px; margin: 20px;">Введеные пароли не совпадают</div>'.mysqli_error($link);
        }
    }
            
    } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
    }

?>
</body>
</html>