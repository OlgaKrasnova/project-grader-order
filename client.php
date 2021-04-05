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
            <?php 
            if (!isset($_SESSION['id_manager']) && !isset($_SESSION['id_client'])) {
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
                        <h3>Вход</h3>
                        <form action="client.php" method="POST">
                            <label for="email">Введите Email</label><br>
                                <input name="email" type="text" required><br>
                            <label for="password">Введите пароль</label><br>
                                <input name="password" type="password" id="password" required> <br>
                            <input style="margin-right: 20px !important;" name="submit" type="submit" class="btn-success" value="Войти"></input><br><br>
                            <a href="index.php" style="color: black;">На главную</a>
                        </form>
                    ';

                require_once 'connection.php'; // подключаем скрипт
                    
                    // подключаемся к серверу
                    $link = mysqli_connect($host, $user, $password, $database) 
                        or die("Ошибка " . mysqli_error($link));
                    
                    if(isset($_POST['submit'])){
                        $pass_hash = $_POST['password']; 
                        
                        $sql_res = mysqli_query($link, "SELECT * FROM users WHERE role = 'client' OR role='manager'");
                        for ($i=0; $i < mysqli_num_rows($sql_res); $i++) {
                            $row = mysqli_fetch_array($sql_res, MYSQLI_ASSOC);
                            $client_id = $row['id_user'];
                            $client_fio = $row['FIO'];
                            $client_email = $row['email'];
                            $client_password = $row['password'];
                            $client_role = $row['role'];

                            if($client_role == 'manager'){
                              if((password_verify($pass_hash, $client_password) == true) && ($_POST['email'] == $client_email)) {
                                $_SESSION['id_manager'] = $client_id;
                                $_SESSION['id_FIO'] = $client_fio;
                                echo "<script>window.location = 'client.php'</script>
                                </div>
                                </div>";
                              break;
                              } else {
                                print "
                                <p style='text-align: center; color: red;'>Неправильный логин или пароль!</p>
                                </div>
                                </div>";
                              }      
                            } else if($client_role == 'client') {
                              if((password_verify($pass_hash, $client_password) == true) && ($_POST['email'] == $client_email)) {
                                $_SESSION['id_client'] = $client_id;
                                $_SESSION['id_FIO'] = $client_fio;
                                echo "<script>window.location = 'client.php'</script>";
                              break;
                              } else {
                                print "
                                <p style='text-align: center; color: red;'>Неправильный логин или пароль!</p>
                                </div>
                                </div>";
                              }
                            }  
                        }
                        

                    }    

                }  else if (isset($_SESSION['id_client'])) {
                  // print_r($_SESSION);
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
                        <p class="privet">Добро пожаловать,<br>'.$_SESSION['id_FIO'].'!</p>
                        <form method="POST">
                        <button name="exit" class="btn-danger">Выйти</button>
                        </form>
                    </header>
                    <div class="container-admin">
                        <div class="admin admin-admin admin-client">
                            <h2>Личный кабинет клиента</h2>
                            <div class="pan-item">
                                <a href="index.php">Главная</a>
                            </div>
                            
                            <div class="pan-item">
                                <a href="catalog.php">Каталог автогрейдеров</a>
                            </div>

                        </div>
                            <div class="pan-client">
                                <h2>Ваши заявки</h2>';

                                require_once 'connection.php'; // подключаем скрипт
                                // подключаемся к серверу
                                $link = mysqli_connect($host, $user, $password, $database) 
                                or die("Ошибка " . mysqli_error($link));

                                $id = $_SESSION['id_client'];
                                $sql_res = mysqli_query($link, "SELECT * from orders natural join graders WHERE id_user = $id");
                    
                                echo '
                                    <table>
                                    <tr><th>Наименование автогрейдера</th><th>Статус заявки</th></tr>
                                ';
                                while($row = mysqli_fetch_assoc($sql_res)){
                                    $name_grader= $row['name_grader'];
                                    $status= $row['status']; 
                                    echo '
                                        <tr><td>'.$name_grader.'</td><td>'.$status.'</td></tr>
                                        ';
                                }
                                echo '
                                </table>
                                ';

                            echo '<h2>Действия</h2>
                                <div>
                                    <h3>Заказать обратный звонок: </h3>
                                    <form action="client.php" method="POST" >
                                        <label>Фамилия Имя Отчество:</label><i> *</i><br>
                                        <input type="text" name="FIO" required placeholder="Фамилия Имя Отчество"><br>
                                        <label>Номер телефона:</label><i> *</i><br>
                                        <input type="number" name="phone" required placeholder="+7 (916) 123-45-67" value="+7" minlength="12"><br>
                                        <label for="agree" style="font-size: 12px;">Я даю свое согласие на обработку персональных данных:</label><i> *</i>
                                                        <input name="agree" type="checkbox" id="agree" required><br>
                                                    <sub><i>* Поля, отмеченные звездочкой, обязательны для заполнения</i></sub><br><br>
                                        <input type="submit" class="call" name="call" value="Заказать звонок"></input>
                                    </form>
                                </div>';
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
                                    echo '<hr><div class="error col" style="color:red; font-size:20px">Заявка не создана. Повторите.</div>'.mysqli_error($link);
                                else // если все прошло нормально – выводим сообщение
                                    echo '<hr><div class="ok col" style="color:green; font-size:20px">Заявка на обратный звонок успешно создана! Ожидайте звонка.</div>';
                                }
                            echo '<div>
                                    <h3>Подобрать автогрейдер по параметрам: </h3>
                                    
                                    <form class="form-call" action="client.php" method="POST" enctype="multipart/form-data" >
                                    
                                    <label for="weight">Введите желаемую массу:</label><br>
                                    <span>ОТ</span>
                                    <input name="weight_ot" type="number" min="1" id="weight" required>
                                    <span>ДО</span>
                                    <input name="weight_do" type="number" min="1" id="weight" required><br>
                                    
                                    <label for="engine_power">Введите желаемую мощность двигателя:</label><br>
                                    <span>ОТ</span>
                                    <input name="engine_power_ot" type="number" min="1" id="engine_power" required>
                                    <span>ДО</span>
                                    <input name="engine_power_do" type="number" min="1" id="engine_power" required><br>
                                    
                                    <label for="blade_height">Введите желаемую длину лезвия:</label><br>
                                    <span>ОТ</span>
                                    <input name="blade_height_ot" type="number" min="1" id="blade_height" required>
                                    <span>ДО</span>
                                    <input name="blade_height_do" type="number" min="1" id="blade_height" required><br>

                                    <label for="blade_width">Введите желаемую ширину лезвия:</label><br>
                                    <span>ОТ</span>
                                    <input name="blade_width_ot" type="number" min="1" id="blade_width" required>
                                    <span>ДО</span>
                                    <input name="blade_width_do" type="number" min="1" id="blade_width" required><br>

                                    <label for="days">Введите кол-во дней аренды:</label>
                                    <input name="days" type="number" min="1" id="days" required><br>
                                    <input class="btn-success" type="submit" name="take" value="Подобрать">
                                </form>
                            </div>
                        </div>
                    ';
                    
                    // если была нажата кнопка Подобрать
                    if( isset($_POST['take'])) {
                        
                        // print_r($_POST);

                        $engine_power_ot = htmlspecialchars($_POST['engine_power_ot']);
                        $engine_power_do = htmlspecialchars($_POST['engine_power_do']);
                        
                        $weight_ot = htmlspecialchars($_POST['weight_ot']);
                        $weight_do = htmlspecialchars($_POST['weight_do']);
                        
                        $blade_height_ot = htmlspecialchars($_POST['blade_height_ot']);
                        $blade_height_do = htmlspecialchars($_POST['blade_height_do']);
                        
                        $blade_width_ot = htmlspecialchars($_POST['blade_width_ot']);
                        $blade_width_do = htmlspecialchars($_POST['blade_width_do']);
                        $days = htmlspecialchars($_POST['days']);
                        
                        require_once 'connection.php'; // подключаем скрипт
                        
                        // подключаемся к серверу
                        $link = mysqli_connect($host, $user, $password, $database) 
                            or die("Ошибка " . mysqli_error($link));

                        $sql=mysqli_query($link, "SELECT * FROM graders WHERE 
                            (`weight` BETWEEN $weight_ot AND $weight_do) AND
                            (`engine_power` BETWEEN $engine_power_ot AND $engine_power_do) AND 
                            (`blade_height` BETWEEN $blade_height_ot AND $blade_height_do) AND
                            (`blade_width` BETWEEN $blade_width_ot AND $blade_width_do)")
                                or die("Ошибка " . mysqli_error($link));
                        
                        echo '<div style="grid-column:2">';
                        while($row = mysqli_fetch_assoc($sql)){
                            $id_grader = $row['id_grader']; 
                            $name_grader = $row['name_grader'];  
                            $engine_power = $row['engine_power'];
                            $weight = $row['weight'];
                            $blade_height = $row['blade_height'];
                            $blade_width = $row['blade_width'];
                            $price = $row['price'];
                            $image = $row['image'];
                            $id = $_SESSION['id_client'];

                            echo '<div class="catalog-card" style="float: left">
                            <img src="'.$image.'" alt="автогрейдер" class="photo-grader">
                            <p>Наименование: '.$name_grader.'</p>
                            <p>Мощность двигателя: '.$engine_power.' л.с.</p>
                            <p>Масса: '.$weight.' тонн</p>
                            <p>Длина лезвия: '.$blade_height.' м.</p>
                            <p>Ширина лезвия: '.$blade_width.' м.</p>
                            <p>Итоговая стоимость: '.$price * $days.' руб.</p>
                            <form action="result-rent-request.php" class="form-client-catalog" method="POST">
                                <input type="hidden" name="id_user" value="'.$id.'"/>
                                <input type="hidden" name="id_grader" value="'.$id_grader.'"/>
                                <input type="submit" class="btn-success" name="rent_request" value="Подать заявку на аренду">
                            </form>';
                            echo '</div>';
                            require_once 'result-rent-request.php'; // подключаем скрипт
                            }
                            echo '<div>';
                        // $count = mysqli_fetch_row($sql)[0];
                        // echo $count.'AAAAAAAAAAAAAAAA';

                        // если при выполнении запроса произошла ошибка – выводим сообщение
                        // if( mysqli_errno($link) )
                        // echo 'Такой есть'.mysqli_error($link);
                        // else // если все прошло нормально – выводим сообщение
                        // echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о владельце обновлена</div>';
                        // echo "<script>window.location = 'client.php'</script>";
                        }
                    


                  if (isset($_POST["exit"])) {
                      session_destroy();
                      echo "<script>window.location = 'client.php'</script>";
                  }
                } else if (isset($_SESSION['id_manager'])) {
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
                    <p class="privet">Добро пожаловать,<br>'.$_SESSION['id_FIO'].'!</p>
                    <form method="POST">
                        <button name="exit" class="btn-danger">Выйти</button>
                    </form>
                  </header>
                  
                <div class="container-admin">
                    <div class="admin admin-admin admin-manager">
                        <h2>Личный кабинет менеджера</h2>
                        <div class="pan-item">
                            <a href="index.php">Главная</a>
                        </div>
                        <div class="pan-item">
                            <a href="list-clients.php">Управление пользователями</a>
                        </div>
                        <div class="pan-item">
                        <a href="list-rents.php">Управление арендами</a>
                        </div>
                        <div class="pan-item">
                            <a href="list-requests.php">Управление заявками</a>
                        </div>
                    </div>
                    <div class="pan-client">
                        <h3 style="margin-top: 5px;">Список необработанных заявок на обратный звонок</h3>';
                        require_once 'connection.php'; // подключаем скрипт
                        // подключаемся к серверу
                        $link = mysqli_connect($host, $user, $password, $database) 
                        or die("Ошибка " . mysqli_error($link));

                        $sql_res = mysqli_query($link, "SELECT * from requests WHERE status = 'Ожидает звонка'");
            
                        echo '
                            <table>
                            <tr><th>ФИО заявителя</th><th>Телефон</th><th>Статус</th></tr>
                        ';
                        while($row = mysqli_fetch_assoc($sql_res)){
                            $fio= $row['FIO'];
                            $phone= $row['phone']; 
                            $status= $row['status']; 
                            echo '
                                <tr><td>'.$fio.'</td><td>'.$phone.'</td><td>'.$status.'</td></tr>
                                ';
                        }
                        echo '
                        </table>
                        <a href="list-requests.php" style="display: block; color: black; margin-top: 20px;">Перейти к управлению заявками на обратный звонок ></a>
                        ';
                        
                echo '
                        <h3>Список необработанных заявок на аренду</h3>';
                        require_once 'connection.php'; // подключаем скрипт
                        // подключаемся к серверу
                        $link = mysqli_connect($host, $user, $password, $database) 
                        or die("Ошибка " . mysqli_error($link));

                        $sql_res = mysqli_query($link, "SELECT * from orders NATURAL JOIN graders NATURAL JOIN users WHERE status = 'Принято к исполнению'");
            
                        echo '
                            <table>
                            <tr><th>Наименование автогрейдера</th><th>ФИО заявителя</th><th>Статус</th></tr>
                        ';
                        while($row = mysqli_fetch_assoc($sql_res)){
                            $name_grader = $row['name_grader'];
                            $fio= $row['FIO'];
                            $status= $row['status']; 
                            echo '
                                <tr><td>'.$name_grader.'</td><td>'.$fio.'</td><td>'.$status.'</td></tr>
                                ';
                        }
                        echo '
                        </table>
                        <a href="list-rents.php" style="display: block; color: black; margin-top: 20px;">Перейти к управлению заявками на аренду ></a>
                        ';
                echo '
                    </div>
                </div>


                  ';
                if (isset($_POST["exit"])) {
                    session_destroy();
                    echo "<script>window.location = 'client.php'</script>";
                }
              }
            ?> 
</body>
</html>