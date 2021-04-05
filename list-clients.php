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
                <a href="client.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в личный кабинет</span>
                </a>
            </div>
            <h2>Клиенты</h2>';
            echo '
            <div class="list-clients">
            <table style="border: 0 !important;">
            <tr><th>ФИО</th><th>Email</th><th>Телефон</th><th colspan="2">Управление</th></tr>';

            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            
            $sql_res = mysqli_query($link, "SELECT * FROM users WHERE role='client'");
            
            while($row = mysqli_fetch_assoc($sql_res)){
                $id = $row['id_user']; 
                $fio = $row['FIO']; 
                $email = $row['email'];
                $phone = $row['phone'];
                echo "<tr><td>$fio</td><td>$email</td><td>$phone</td>";
                if (isset($_SESSION['id_manager'])) {
                    echo "
                    <td>
                        <form action='edit-client.php' method='POST'>
                            <input type='hidden' name='edid' value='$id'/>
                            <input type='submit' class='btn-success' value='Редактировать'></input>
                        </form>
                    </td>
                    <td>
                        <form action='list-clients.php' method='POST'>
                            <input type='hidden' name='delid' value='$id' />
                            <input type='submit' class='btn-danger' value='Удалить'></input>
                    </form>
                    </td></tr>
                    <tr>
                    </tr>";
                }
            }
            echo '
                <tr style="border: 0 !important;">
                <td colspan="5" style="border: 0 !important; padding: 0;">
                    <a href="add-client.php" class="btn-success" style="width: 200px !important;">Добавить нового клиента</a>
                </td>
                </tr>
            </table>
            </div>';

            // если былы нажата кнопка удалить

            if (isset($_POST['delid'])) { //проверяем, есть ли переменная
                //удаляем строку из таблицы
                $sql = mysqli_query($link, "DELETE FROM `users` WHERE `id_user` = {$_POST['delid']}");
                if ($sql) {
                    echo "<p>Владелец удален.</p>";
                } else {
                  echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
                }
                echo "<script>window.location = 'list-clients.php'</script>";
            }
    } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
    }
?>
</body>
</html>