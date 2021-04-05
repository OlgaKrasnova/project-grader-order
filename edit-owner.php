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
    if(isset($_SESSION['id'])) {
        if(isset($_POST['edid'])){
            require_once 'connection.php'; // подключаем скрипт
            // подключаемся к серверу
            $link = mysqli_connect($host, $user, $password, $database) 
                or die("Ошибка " . mysqli_error($link)); 
            
            $sql_res = mysqli_query($link, "SELECT * FROM `owners` WHERE `id_owner`={$_POST['edid']}");

            $row = mysqli_fetch_assoc($sql_res);
            $id = $row['id_owner'];
            $name = $row['name_owner']; 
            $inn = $row['INN'];
            $address = $row['address'];
            $phone = $row['phone'];
            $email = $row['email'];
            echo '
            <div class="back">
                <a href="owners.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в список владельцев</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Редактирование информации о владельце автогрейдера</h2>
                <form action="edit-owner.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <input type="hidden" name="edid" value="'.$id.'"/>
                      <label for="name_owner">ФИО владельца:</label><br>
                        <input id="name_owner" name="name_owner" type="text" value="'.$name.'" required><br>
                      <label for="inn">ИНН:</label><br>
                        <input name="inn" type="number" id="inn" value="'.$inn.'" required><br>
                      <label for="address">Адрес:</label><br>
                        <input name="address" type="text" id="address" value="'.$address.'" required><br>
                      <label for="phone">Телефон:</label><br>
                        <input name="phone" type="text" id="phone" value="'.$phone.'" required><br>
                      <label for="email">Email:</label><br>
                        <input name="email" type="email" id="email" value="'.$email.'" required><br>
                      <input class="btn-success" type="submit" name="update" value="Сохранить">
                  </form>
                </div>';
        }
        
        // если были переданы данные для обновления информации
        if( isset($_POST['update'])) {
            $id = $_POST['edid'];
            $name_owner = htmlspecialchars($_POST['name_owner']);
            $inn = htmlspecialchars($_POST['inn']);
            $address = htmlspecialchars($_POST['address']);
            $phone = htmlspecialchars($_POST['phone']);
            $email = htmlspecialchars($_POST['email']);

            $sql_res_prod=mysqli_query($link, "UPDATE owners SET 
                name_owner='$name_owner', INN='$inn', address='$address', 
                phone='$phone', email='$email' WHERE id_owner='$id'")
                    or die("Ошибка " . mysqli_error($link));

                // если при выполнении запроса произошла ошибка – выводим сообщение
            if( mysqli_errno($link) )
            echo '<hr><div class="error col" style="color:red; font-size:20px">Информация о владельце не обновлена</div>'.mysqli_error($link);
            else // если все прошло нормально – выводим сообщение
            echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация о владельце обновлена</div>';
            echo "<script>window.location = 'owners.php'</script>";
        }
            
    } else {
        echo 'Авторизуйтесь как администратор';
    }

?>
</body>
</html>