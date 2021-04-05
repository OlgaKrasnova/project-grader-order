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
            
            $sql_res = mysqli_query($link, "SELECT * FROM `requests` WHERE `id_request`={$_POST['edid']}");

            $row = mysqli_fetch_assoc($sql_res);
            $id = $row['id_request'];
            $fio = $row['FIO']; 
            $phone = $row['phone'];
            $status = $row['status'];
            $purpose = $row['purpose'];

            echo '
            <div class="back">
                <a href="list-requests.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список заявок на обратный звонок</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Обработка заявки</h2>
                <form action="edit-request.php" method="POST" enctype="multipart/form-data" class="big-form">
                     <input type="hidden" name="edid" value="'.$id.'"/>
                     <label for="fio">ФИО заявителя:</label><i> *</i><br>
                       <p>'.$fio.'</p>
                     <label for="email">Email:</label><i> *</i><br>
                        <p>'.$phone.'</p>
                      <label for="status">Статус:</label><i> *</i><br>
                        <select name="status">';
                            if($status == 'Ожидает звонка') {
                                echo '<option value="Ожидает звонка" selected>Ожидает звонка</option>
                                      <option value="Обработан">Обработан</option>
                                      <option value="Немой звонок">Немой звонок</option>';
                            } else if($status == 'Обработан') {
                                echo '<option value="Ожидает звонка">Ожидает звонка</option>
                                      <option value="Обработан" selected>Обработан</option>
                                      <option value="Немой звонок">Немой звонок</option>';
                            } else if($status == 'Немой звонок') {
                                echo '<option value="Ожидает звонка">Ожидает звонка</option>
                                      <option value="Обработан">Обработан</option>
                                      <option value="Немой звонок" selected>Немой звонок</option>';
                            }
                        echo '
                        </select><br>
                        <label for="purpose">Цель звонка:</label><i> *</i><br>
                        <input name="purpose" type="text" id="purpose" value="'.$purpose.'" value="+7" required><br>
                      <input class="btn-success" type="submit" name="update" value="Сохранить"></input>
                  </form>
                </div>';

        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        // если были переданы данные для обновления информации
        if( isset($_POST['update'])) {
            // echo $_POST['status'];
            $id = $_POST['edid'];
            $status = htmlspecialchars($_POST['status']);
            $purpose = htmlspecialchars($_POST['purpose']);

            $sql_res_prod=mysqli_query($link, "UPDATE requests SET 
                status='$status', purpose='$purpose' WHERE id_request='$id'")
                    or die("Ошибка " . mysqli_error($link));

                // если при выполнении запроса произошла ошибка – выводим сообщение
            // if( mysqli_errno($link) )
            // echo '<hr><div class="error col" style="color:red; font-size:20px">Информация о пользователе не обновлена</div>'.mysqli_error($link);
            // else // если все прошло нормально – выводим сообщение
            // echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о пользователе обновлена</div>';
            echo "<script>window.location = 'list-requests.php'</script>";
        }
            
    }
 } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
 }

?>
</body>
</html>