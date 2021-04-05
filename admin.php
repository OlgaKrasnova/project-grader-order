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
    <script type="text/javascript" src="js/loader.js"></script>
    <?php
        echo '
        <script type="text/javascript">
          google.charts.load("current", {packages:["corechart"]});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Автогрейдер", "Количество заказов"],';
            //   ["Work",     11],
            //   ["Eat",      2],
            //   ["Commute",  2],
            //   ["Watch TV", 2],
            //   ["Sleep",    7]
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
              or die("Ошибка " . mysqli_error($link));
            
            $sql_res_grad = mysqli_query($link, "SELECT * FROM graders");
            
            while($row = mysqli_fetch_assoc($sql_res_grad)){
               $id = $row['id_grader']; // идентификатор
               $name_grader = $row['name_grader'];
               $sql = mysqli_query($link, "SELECT COUNT(*) FROM orders WHERE id_grader='$id'");
               $count = mysqli_fetch_row($sql)[0];
               echo '["'.$name_grader.'", '.$count.'],';
            }

        echo']);
    
            var options = {
              title: "Популярность автогрейдеров среди клиентов",
              pieHole: 0.4,
            };
    
            var chart = new google.visualization.PieChart(document.getElementById("donutchart"));
            chart.draw(data, options);
          }
        </script>
        ';
    ?>
</head>
<body>
            <?php 
            if (!isset($_SESSION['id'])) {
                echo '
                <div class="admin" 
                    style="
                        height: 98.2vh; 
                        background: url(img/12.jpg) 0 0 no-repeat;
                        background-size: cover;
                        color: black;
                ">
                    <div style="
                        background: #fcfcfcc2; 
                        border-radius: 10px;
                        text-align: center;
                        padding: 10px;   
                    ">
                        <h3>Вход (Администратор)</h3>
                        <form action="admin.php" method="POST">
                            <label for="email">Введите Email</label><br>
                                <input name="email" type="text" required><br>
                            <label for="password">Введите пароль</label><br>
                                <input name="password" type="password" id="password" required> <br>
                            <input name="submit" type="submit" class="btn-success" value="Войти"></input><br><br>
                            <a href="index.php" style="color: black;">На главную</a>
                        </form>

                ';

                require_once 'connection.php'; // подключаем скрипт
                    
                    // подключаемся к серверу
                    $link = mysqli_connect($host, $user, $password, $database) 
                        or die("Ошибка " . mysqli_error($link));
                    
                    if(isset($_POST['submit'])){
                        $pass_hash = $_POST['password'];
                        
                        $sql_res = mysqli_query($link, "SELECT * FROM users WHERE role = 'admin'");
                        for ($i=0; $i < mysqli_num_rows($sql_res); $i++) {
                            $row = mysqli_fetch_array($sql_res, MYSQLI_ASSOC);
                            // print_r($row);
                            $adm_id = $row['id_user'];
                            $adm_fio = $row['FIO'];
                            $adm_log = $row['email'];
                            $adm_pass = $row['password'];
                            //echo '<br>'.$adm_log.' '.$adm_pass;
                        }
                        
                        if((password_verify($pass_hash, $adm_pass) == true) && ($_POST['email'] == $adm_log)) {
                            $_SESSION['id'] = $adm_id;
                            $_SESSION['id_FIO'] = $adm_fio;
                            echo "<script>window.location = 'admin.php'</script>";
                        } else {
                            print "
                            <p style='text-align: center; color: red;'>Неправильный логин или пароль!</p>
                            </div>
                            </div>";
                        }

                    }    

                }  else {
                    echo '
                        <header>
                            <a href="index.php">
                                <img src="img/logo.png" alt="Логотип" class="logo">
                            </a>
                            <span class="text-logo">
                                <a href="index.php">
                                    Аренда автогрейдеров
                                </a>    
                            </span>
                            <form method="POST">
                            <button name="exit" class="btn-danger">Выйти</button>
                            </form>
                        </header>
                    <div class="container-admin">
                        <div class="admin admin-admin">
                            <h2>Панель администратора</h2>
                            <div class="pan-item">
                                <a href="index.php">Главная</a>
                            </div>
                            <div class="pan-item">
                                <a href="catalog.php">Каталог автогрейдеров</a>
                            </div>
                            <div class="pan-item">
                                <a href="add-grader.php">Добавить автогрейдер</a>
                            </div>
                            <div class="pan-item">
                                <a href="owners.php">Список владельцев автогрейдеров</a>
                            </div>
                            <div class="pan-item">
                                <a href="add-owner.php">Добавить владельца автогрейдера</a>
                            </div>
                        </div>
                        <div>
                            <h2>Отчет</h2>
                            <div id="donutchart" style="width: 900px; height: 500px;"></div>

                            <p style="text-weight: bold;">Количество автогрейдеров: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM graders");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;">Количество владельцев: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM owners");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;">Количество клиентов: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM users WHERE `role`='client'");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;">Количество заявок на обратный звонок (всего): ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM requests");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo'</p>
                            <p style="text-weight: bold;" style="text-indent: 60px !important;">Количество обработанных звонков: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM requests WHERE status='Обработан'");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo'</p>
                            <p style="text-weight: bold;" style="text-indent: 60px !important;">Количество немых звонков: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM requests WHERE status='Немой звонок'");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;">Количество заявок на аренду (всего): ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM orders");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;" style="text-indent: 60px !important;">Количество оформленных заявок на аренду: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM orders WHERE status='Оформлено'");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                            <p style="text-weight: bold;" style="text-indent: 60px !important;">Количество отклоненных заявок на аренду: ';
                            require_once 'connection.php'; // подключаем скрипт
                            $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                            $sql = mysqli_query($link, "SELECT COUNT(*) FROM orders WHERE status='Отказано'");
                            $count = mysqli_fetch_row($sql)[0];
                            echo $count;
                    echo '</p>
                        </div>
                    </div>
                    ';
                if (isset($_POST["exit"])) {
                    session_destroy();
                    echo "<script>window.location = 'admin.php'</script>";
                }

                if ($_GET["a"] == "add") require 'trip-add.php';
                }
            ?> 
</body>
</html>