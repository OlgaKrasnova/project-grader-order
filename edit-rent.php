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
            
            $sql_res = mysqli_query($link, "SELECT * FROM orders NATURAL JOIN graders NATURAL JOIN users WHERE id_order={$_POST['edid']}");
        
            $row = mysqli_fetch_assoc($sql_res);
            $id = $row['id_order'];
            $id_grader = $row['id_grader'];
            $fio = $row['FIO'];
            $id_user = $row['id_user'];
            $status = $row['status'];
            $start_rent = $row['start_rent'];
            $end_rent = $row['end_rent'];

            echo '
            <div class="back">
                <a href="list-rents.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список аренд</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Изменение параметров аренды</h2>
                <form action="edit-rent.php" method="POST" enctype="multipart/form-data" class="big-form">
                     <input type="hidden" name="edid" value="'.$id.'"/>
                     <input type="hidden" name="id_user" value="'.$id_user.'"/>
                     <label for="fio">ФИО заявителя:</label><i> *</i><br>
                     <input name="fio" type="text" id="fio" value="'.$fio.'" value="+7" required><br>
                     <label for="fio">Наименование автогрейдера:</label><i> *</i><br>
                     <select name="id_grader">';

                     require_once 'connection.php'; // подключаем скрипт
                     // подключаемся к серверу
                     $link = mysqli_connect($host, $user, $password, $database) 
                       or die("Ошибка " . mysqli_error($link));
                     
                     $sql_res = mysqli_query($link, "SELECT * FROM graders");
                     
                     while($row = mysqli_fetch_assoc($sql_res)){
                         $id = $row['id_grader']; // идентификатор
                         $name = $row['name_grader']; // фамилия
                         if($id == $id_grader){
                             echo "<option value=\"$id\" selected>$name</option>"; // выводим
                         } else {
                             echo "<option value=\"$id\">$name</option>"; // выводим
                         }
                     }
            echo'</select><br>
                      <label for="status">Статус:</label><i> *</i><br>
                        <select name="status">';
                            if($status == 'Принято к исполнению') {
                                echo '<option value="Принято к исполнению" selected>Принято к исполнению</option>
                                      <option value="Оформлено">Оформлено</option>
                                      <option value="Закрыто">Закрыто</option>
                                      <option value="Отказано">Отказано</option>';
                            } else if($status == 'Оформлено') {
                                echo '<option value="Принято к исполнению">Принято к исполнению</option>
                                      <option value="Оформлено" selected>Оформлено</option>
                                      <option value="Закрыто">Закрыто</option>
                                      <option value="Отказано">Отказано</option>';
                            } else if($status == 'Закрыто') {
                                echo '<option value="Принято к исполнению">Принято к исполнению</option>
                                      <option value="Оформлено">Оформлено</option>
                                      <option value="Закрыто" selected>Закрыто</option>
                                      <option value="Отказано">Отказано</option>';
                            } else if($status == 'Отказано') {
                                echo '<option value="Принято к исполнению">Принято к исполнению</option>
                                      <option value="Оформлено">Оформлено</option>
                                      <option value="Закрыто">Закрыто</option>
                                      <option value="Отказано" selected>Отказано</option>';
                            }
                        echo '
                        </select><br>
                        <label for="start_rent">Начало аренды:</label><br>
                            <input name="start_rent" type="date" id="start_rent" value="'.$start_rent.'"><br>
                        <label for="end_rent">Конец аренды:</label><br>
                            <input name="end_rent" type="date" id="end_rent" value="'.$end_rent.'"><br>
                        
                      <input class="btn-success" type="submit" name="update" value="Сохранить"></input>
                  </form>
                </div>';

        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        //если были переданы данные для обновления информации
        if( isset($_POST['update'])) {
            // print_r($_POST);
            $id_order = htmlspecialchars($_POST['edid']);
            $id_grader = htmlspecialchars($_POST['id_grader']);
            $id_user = htmlspecialchars($_POST['id_user']);
            // $fio = htmlspecialchars($_POST['FIO']);
            $status = htmlspecialchars($_POST['status']);
            $start_rent = htmlspecialchars($_POST['start_rent']);
            $end_rent = htmlspecialchars($_POST['end_rent']);

            if($start_rent != NULL && $end_rent != NULL) {
                $sql_res_prod=mysqli_query($link, "UPDATE orders SET 
                    id_grader='$id_grader', id_user='$id_user', status='$status', 
                    start_rent='$start_rent', end_rent='$end_rent' WHERE id_order='$id_order'")
                        or die("Ошибка " . mysqli_error($link));
            } else if($start_rent == NULL && $end_rent == NULL) {
                $sql_res_prod=mysqli_query($link, "UPDATE orders SET 
                    id_grader='$id_grader', id_user='$id_user', status='$status'
                     WHERE id_order='$id_order'")
                        or die("Ошибка " . mysqli_error($link));
            }

                // если при выполнении запроса произошла ошибка – выводим сообщение
            if( mysqli_errno($link) )
            echo '<hr><div class="error col" style="color:red; font-size:20px">Информация об аренде не обновлена</div>'.mysqli_error($link);
            else // если все прошло нормально – выводим сообщение
            echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о аренде обновлена</div>';
            // echo "<script>window.location = 'list-rents.php'</script>";
        }
            
    }
 } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
 }

?>
</body>
</html>