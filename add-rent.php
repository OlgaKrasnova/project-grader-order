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
                <a href="list-rents.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список аренд</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Оформление аренды</h2>
                <form action="add-rent.php" method="POST" enctype="multipart/form-data" class="big-form">
                    <label for="id_client">Клиент:</label><i> *</i><br>
                    <select name="id_client" id="id_client">';
                    require_once 'connection.php'; // подключаем скрипт
                    // подключаемся к серверу
                    $link = mysqli_connect($host, $user, $password, $database) 
                       or die("Ошибка " . mysqli_error($link));
                     
                    $sql_res = mysqli_query($link, "SELECT * FROM users WHERE role='client'");
                     
                    while($row = mysqli_fetch_assoc($sql_res)){
                        $id = $row['id_user']; // идентификатор
                        $fio = $row['FIO']; // фамилия
                        echo "<option value=".$id.">$fio</option>"; // выводим
                    }
            echo'</select><br>
                     <label for="id_grader">Наименование автогрейдера:</label><i> *</i><br>
                     <select name="id_grader" id="id_grader">';
                     require_once 'connection.php'; // подключаем скрипт
                     // подключаемся к серверу
                     $link = mysqli_connect($host, $user, $password, $database) 
                       or die("Ошибка " . mysqli_error($link));
                     
                     $sql_res = mysqli_query($link, "SELECT * FROM graders");
                     
                     while($row = mysqli_fetch_assoc($sql_res)){
                        $id = $row['id_grader']; // идентификатор
                        $name = $row['name_grader']; // фамилия
                        echo "<option value=".$id.">$name</option>"; // выводим
                     }
            echo'</select><br>
                      <label for="status">Статус:</label><i> *</i><br>
                        <select name="status">
                            <option value="Принято к исполнению">Принято к исполнению</option>
                            <option value="Оформлено">Оформлено</option>
                            <option value="Закрыто">Закрыто</option>
                            <option value="Отказано">Отказано</option>
                        </select><br>
                        <label for="start_rent">Начало аренды:</label><br>
                            <input name="start_rent" type="date" id="start_rent" value="'.$start_rent.'"><br>
                        <label for="end_rent">Конец аренды:</label><br>
                            <input name="end_rent" type="date" id="end_rent" value="'.$end_rent.'"><br>
                        
                      <input class="btn-success" type="submit" name="save" value="Оформить"></input>
                  </form>
                </div>';

        require_once 'connection.php'; // подключаем скрипт
        
        // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
            or die("Ошибка ".mysqli_error($link));
        
        //если были переданы данные для обновления информации
        if( isset($_POST['save'])) {
            // print_r($_POST);
            $id_grader = htmlspecialchars($_POST['id_grader']);
            $id_user = htmlspecialchars($_POST['id_client']);
            
            $status = htmlspecialchars($_POST['status']);
            $start_rent = htmlspecialchars($_POST['start_rent']);
            $end_rent = htmlspecialchars($_POST['end_rent']);

            if($start_rent != NULL && $end_rent != NULL) {
                $sql_res_prod=mysqli_query($link, "INSERT INTO orders 
                (`id_grader`, `id_user`, `status`, `start_rent`, `end_rent`) 
                VALUES 
                ('$id_grader', '$id_user', '$status', '$start_rent', '$end_rent')")
                    or die("Ошибка ". mysqli_error($link));

            } else if($start_rent == NULL && $end_rent == NULL) {
                $sql_res_prod=mysqli_query($link, "INSERT INTO orders 
                (`id_grader`, `id_user`, `status`) 
                VALUES 
                ('$id_grader', '$id_user', '$status')")
                    or die("Ошибка " . mysqli_error($link));
            }
            

                // если при выполнении запроса произошла ошибка – выводим сообщение
            if( mysqli_errno($link) )
            echo '<hr><div class="error col" style="color:red; font-size:20px">Аренда оформлена</div>'.mysqli_error($link);
            else // если все прошло нормально – выводим сообщение
            echo '<hr><div class="ok col" style="color:green; font-size:20px">Аренда оформлена</div>';
            // echo "<script>window.location = 'list-rents.php'</script>";
        }
 } else {
        echo 'Авторизуйтесь как менеджер, чтобы просматривать эту страницу';
 }

?>
</body>
</html>