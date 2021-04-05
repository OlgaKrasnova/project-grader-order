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
    
    <div class="back">
        <a href="admin.php" style="color: black;">
            <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
            <span>Вернуться на панель администратора</span>
        </a>
    </div>
    <h2>Список владельцев автогрейдеров</h2>
    <div class="owners-list">
        <?php
            echo '
            <table>
            <tr><th>ФИО</th><th>ИНН</th><th>Адрес</th><th>Телефон</th><th>Email</th>';
            
            if (isset($_SESSION['id'])) {
                echo '<th colspan="2">Управление</th></tr>';
            }
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка " . mysqli_error($link));
            
            $sql_res = mysqli_query($link, "SELECT * FROM owners");
            
            while($row = mysqli_fetch_assoc($sql_res)){
                $id = $row['id_owner']; // иднтификатор
                $name = $row['name_owner']; // 
                $inn = $row['INN'];
                $address = $row['address'];
                $phone = $row['phone'];
                $email = $row['email'];
                echo "<tr><td>$name</td><td>$inn</td><td>$address</td><td>$phone</td><td>$email</td>";
                if (isset($_SESSION['id'])) {
                    echo "
                    <td>
                        <form action='edit-owner.php' method='POST'>
                            <input type='hidden' name='edid' value='$id'/>
                            <input type='submit' class='btn-success' value='Редактировать'></input>
                        </form>
                    </td>
                    <!--<td>
                        <form action='owners.php' method='POST'>
                            <input type='hidden' name='delid' value='$id' />
                            <input type='submit' class='btn-danger' value='Удалить'></input>
                        </form>
                    </td>-->
                    </tr>";
                }
            }
            echo '</table>';

            // // если былы нажата кнопка удалить
            // if (isset($_POST['delid'])) { //проверяем, есть ли переменная

            // //     Удаление автогрейдеры, связанные с владельцем

            //     require_once 'connection.php'; // подключаем скрипт
            //     $link = mysqli_connect($host, $user, $password, $database) 
            //         or die("Ошибка " . mysqli_error($link));

                
            //     $sql = mysqli_query($link, "SELECT COUNT(*) 
            //         FROM owners NATURAL JOIN graders NATURAL JOIN orders WHERE id_owner={$_POST['delid']}");
            //     $count = mysqli_fetch_row($sql)[0];
            //     if($count > 1) {
            // //         если у владельца есть автогрейдер и он участвует в заказах
            //         $sql_grad = mysqli_query($link, "SELECT * FROM graders WHERE id_owner={$_POST['delid']}");
            //         while($row = mysqli_fetch_assoc($sql_grad)){
            //             $id_grader = $row['id_grader']; 
            //             $sql = mysqli_query($link, "DELETE FROM `orders` WHERE `id_grader` = {$_POST['delid']}");
            //         }
            //         $sql = mysqli_query($link, "DELETE FROM `graders` WHERE `id_owner` = {$_POST['delid']}");
            //         $sql = mysqli_query($link, "DELETE FROM `owners` WHERE `id_owner` = {$_POST['delid']}");
            //         if ($sql) {
            //             print("<script type='text/javascript'>
            //                     window.alert('Владелец удален!'');
            //                     </script>");
            //                     echo "<script>window.location = 'owners.php'</script>";
            //         } else {
            //             echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
            //         }
            //     } else if ($count == 0){
            // //         если у владельца есть автогрейдер и он не участвует в заказах
            //         $sql = mysqli_query($link, "SELECT COUNT(*) 
            //         FROM owners NATURAL JOIN graders WHERE id_owner={$_POST['delid']}");
            //         if($count > 1) {
            //             $sql = mysqli_query($link, "DELETE FROM `graders` WHERE `id_owner` = {$_POST['delid']}");
            //             $sql = mysqli_query($link, "DELETE FROM `owners` WHERE `id_owner` = {$_POST['delid']}");
            //             if ($sql) {
            //                 print("<script type='text/javascript'>
            //                         window.alert('Автогрейдер удален!');
            //                         </script>");
            //                         echo "<script>window.location = 'owners.php'</script>";
            //             } else {
            //                 echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
            //             }
            //         } else if($count == 1) {
            //             $sql = mysqli_query($link, "DELETE FROM `owners` WHERE `id_owner` = {$_POST['delid']}");
            //             if ($sql) {
            //                 print("<script type='text/javascript'>
            //                         window.alert('Автогрейдер удален!');
            //                         </script>");
            //                         echo "<script>window.location = 'owners.php'</script>";
            //             } else {
            //                 echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
            //             }
            //         }
            //     }
            // }
            
        ?>
        <a href="add-owner.php" class="btn-success">Добавить владельца автогрейдера</a>
    </div>
        
</body>
</html>