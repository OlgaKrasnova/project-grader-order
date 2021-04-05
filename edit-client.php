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
        if(isset($_POST['edid'])){
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
                or die("Ошибка " . mysqli_error($link)); 
            
            $sql_res = mysqli_query($link, "SELECT * FROM `users` WHERE `id_user`={$_POST['edid']}");

            $row = mysqli_fetch_assoc($sql_res);
            $id = $row['id_user'];
            $fio = $row['FIO']; 
            $email = $row['email'];
            $phone = $row['phone'];

            echo '
            <div class="back">
                <a href="list-clients.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список клиентов</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Редактирование информации о клиенте</h2>
                <form action="edit-client.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <input type="hidden" name="edid" value="'.$id.'"/>
                      <label for="fio">ФИО клиента:</label><i> *</i><br>
                        <input id="fio" name="fio" value="'.$fio.'" type="text" required><br>
                     <label for="email">Email:</label><i> *</i><br>
                        <input name="email" type="email" value="'.$email.'" id="email" required><br>
                      <label for="phone">Телефон:</label><i> *</i><br>
                        <input name="phone" type="text" id="phone" value="'.$phone.'" value="+7" required><br>
                      <label for="pass">Новый пароль:</label><i> *</i><br>
                        <input name="pass" type="password" required><br>
                      <label for="password">Повторите новый пароль:</label><i> *</i><br>
                        <input name="password" type="password" required><br>
                      <input class="btn-success" type="submit" name="update" value="Сохранить"></input>
                  </form>
                </div>';

        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        // если были переданы данные для добавления в БД
        // если были переданы данные для обновления информации
        if( isset($_POST['update'])) {
        if($_POST['pass'] == $_POST['password']){

            $id = $_POST['edid'];
            $fio = htmlspecialchars($_POST['fio']);
            $email = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);

            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);     

            $sql_res_prod=mysqli_query($link, "UPDATE users SET 
                FIO='$fio', email='$email', password='$pass_hash', 
                phone='$phone' WHERE id_user='$id'")
                    or die("Ошибка " . mysqli_error($link));

                // если при выполнении запроса произошла ошибка – выводим сообщение
            if( mysqli_errno($link) )
            echo '<hr><div class="error col" style="color:red; font-size:20px">Информация о клиенте не обновлена</div>'.mysqli_error($link);
            else // если все прошло нормально – выводим сообщение
            echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о клиенте обновлена</div>';
            // echo "<script>window.location = 'list-clients.php'</script>";
        } else {
            echo '<hr><div class="error col" style="color:red; font-size:20px; margin: 20px;">Введеные пароли не совпадают</div>'.mysqli_error($link);
        }
    }
            
    }
 } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
 }

?>
</body>
</html>