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
            <h2>Список аренд</h2>';
            echo '
            <div class="list-clients">
            <a href="add-rent.php" class="btn-success" style="width: 160px !important; margin: 10px;">Регистрация аренды</a>';

            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            
            $sql_res = mysqli_query($link, "SELECT * FROM orders NATURAL JOIN graders NATURAL JOIN users");
            
            while($row = mysqli_fetch_assoc($sql_res)){
                $id_order = $row['id_order']; 
                $id_grader = $row['id_grader'];
                $name_grader = $row['name_grader'];
                $fio = $row['FIO'];
                $id_user = $row['id_user'];
                $status = $row['status'];
                $start_rent = $row['start_rent'];
                $end_rent = $row['end_rent'];
                $price = $row['price'];

                echo "
                <table style='border: 0 !important; float: left; margin: 10px; max-width: 400px; height: 430px;'>
                    <tr><th>Номер заявки</th><td>$id_order</td></tr>
                    <tr><th>ФИО заявителя</th><td>$fio</td></tr>
                    <tr><th>Наименование автогрейдера</th><td>$name_grader</td></tr>
                    <tr><th>Статус</th>";
                    if($status == 'Принято к исполнению') {
                        echo '<td style="color: #f0a33e">'.$status.'</td>';
                    } else if($status == 'Оформлено') {
                        echo '<td style="color: #28a745">'.$status.'</td>';
                    } else if($status == 'Отказано') {
                        echo '<td style="color: red">'.$status.'</td>';
                    } else if($status == 'Закрыто') {
                        echo '<td style="color: #28a745">'.$status.'</td>';
                    }
                echo "</tr>
                    <tr><th>Начало аренды</th><td>$start_rent</td></tr>
                    <th>Конец аренды</th><td>$end_rent</td></tr>
                    <tr><th>Итоговая стоимость</th><td>";
                    $difference = intval(abs(
                        strtotime($start_rent) - strtotime($end_rent)
                    ));
                    // Количество дней
                    if($start_rent != NULL && $end_rent != NULL){
                        echo ($difference / (3600 * 24) + 1) * $price;
                    } else if($start_rent == NULL && $end_rent == NULL) {
                        echo '0';
                    }
                echo " руб.</td></tr>

                    <tr><td colspan=2 style='text-align: center'>
                        <form action='edit-rent.php' method='POST'>
                            <input type='hidden' name='edid' value='$id_order'/>
                            <input type='submit' class='btn-success' style='width: 200px' value='Обработка заявки'></input>
                        </form>
                    </td></tr>
                </table>";
            }
            echo '
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