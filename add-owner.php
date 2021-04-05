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
    if(isset($_SESSION['id'])) {
            echo '
            <div class="back">
                <a href="admin.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список владельцев автогрейдеров</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Добавление владельца автогрейдера</h2>
                <form action="add-owner.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <label for="name_owner">ФИО владельца:</label><i> *</i><br>
                        <input id="name_owner" name="name_owner" type="text" required><br>
                      <label for="inn">ИНН:</label><i> *</i><br>
                        <input name="inn" type="number" id="inn" required><br>
                      <label for="address">Адрес:</label><i> *</i><br>
                        <input name="address" type="text" id="address" required><br>
                      <label for="phone">Телефон:</label><br>
                        <input name="phone" type="text" id="phone" value="+7" required><br>
                      <label for="email">Email:</label><br>
                        <input name="email" type="email" id="email" required><br>
                      <input class="btn-success" type="submit" name="save" value="Сохранить"></input>
                  </form>
                </div>';
        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        // если были переданы данные для добавления в БД
        if( isset($_POST['save'])) {               

        $sql_res_prod=mysqli_query($link, 'INSERT INTO owners (`name_owner`, `INN`, `address`, `phone`, `email`) VALUES (
            "'.htmlspecialchars($_POST['name_owner']).'",
            "'.htmlspecialchars($_POST['inn']).'",
            "'.htmlspecialchars($_POST['address']).'",
            "'.htmlspecialchars($_POST['phone']).'",
            "'.htmlspecialchars($_POST['email']).'")');
        
        // если при выполнении запроса произошла ошибка – выводим сообщение
        if( mysqli_errno($link) )
        echo '<hr><div class="error col" style="color:red; font-size:20px">Информация о владельце не добавлена</div>'.mysqli_error($link);
        else // если все прошло нормально – выводим сообщение
        echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о владельце добавлена</div>';
        }
            
    } else {
        echo 'Авторизуйтесь как администратор';
    }

?>
</body>
</html>