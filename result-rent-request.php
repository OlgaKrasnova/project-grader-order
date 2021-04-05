<?php
    require_once 'connection.php'; // подключаем скрипт
    // подключаемся к серверу
    $link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
    // если была нажата кнопка Подать заявку на аренду
    if (isset($_POST['rent_request'])) { 
        // print_r($_POST);
            //Вставляем строку
            $sql=mysqli_query($link, 'INSERT INTO orders (`id_grader`, `id_user`, `status`) VALUES (
                "'.htmlspecialchars($_POST['id_grader']).'",
                "'.htmlspecialchars($_POST['id_user']).'",
                "Принято к исполнению")');
            if ($sql) {
                echo "<p style='color: green; text-alignt: center !important; font-family: !important'>Спасибо! Заявка принята к исполнению.<br>Через 3 секунды вы вернетесь в личный кабинет</p>";
            } else {
                echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
            }
            echo '<script>setTimeout(\'location="client.php"\', 3000)</script>';
        }
?>