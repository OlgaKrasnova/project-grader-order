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
            
            $sql_res = mysqli_query($link, "SELECT * FROM `graders` WHERE `id_grader`={$_POST['edid']}");

            $row = mysqli_fetch_assoc($sql_res);
            $id_grader = $row['id_grader']; 
            $name_grader = $row['name_grader']; 
            $engine_power = $row['engine_power'];
            $weight = $row['weight'];
            $blade_height = $row['blade_height'];
            $blade_width = $row['blade_width'];
            $price = $row['price'];
            $image = $row['image'];
            $id_owner = $row['id_owner'];
            echo '
            <div class="back">
                <a href="catalog.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться в каталог</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Редактирование информации об автогрейдере</h2>
                <form action="edit-grader.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <input type="hidden" name="edid" value="'.$id_grader.'"/>
                      <label for="name_grader">Наименование автогрейдера:</label><br>
                        <input id="name_grader" name="name_grader" type="text" value="'.$name_grader.'" required><br>
                      <label for="engine_power">Мощность двигателя:</label><br>
                        <input name="engine_power" type="number" min="1" id="engine_power" value="'.$engine_power.'" required><br>
                      <label for="weight">Масса:</label><br>
                        <input name="weight" type="number" min="1" id="weight" value="'.$weight.'" required><br>
                      <label for="blade_height">Длина лезвия:</label><br>
                        <input name="blade_height" type="number" min="1" id="blade_height" value="'.$blade_height.'" required><br>
                      <label for="blade_width">Ширина лезвия:</label><br>
                        <input name="blade_width" type="number" min="1" id="blade_width" value="'.$blade_width.'" required><br>
                      <label for="price">Стоимость:</label><br>
                        <input name="price" type="number" min="1" id="price" value="'.$price.'" required><br>
                      <label for="image">Фотография автогрейдера:</label><br>
                        <input name="image" type="file" id="image" accept=".png, .jpg, .jpeg" value="'.$image.'" required><br>
                      <label for="image">Владелец автогрейдера:</label><br>
                      <select name="id_owner" value="'.$id_owner.'">
                      ';

                      require_once 'connection.php'; // подключаем скрипт
                        // подключаемся к серверу
                        $link = mysqli_connect($host, $user, $password, $database) 
                          or die("Ошибка " . mysqli_error($link));
                        
                        $sql_res = mysqli_query($link, "SELECT * FROM owners");
                        
                        while($row = mysqli_fetch_assoc($sql_res)){
                            $id = $row['id_owner']; // идентификатор
                            $name1 = $row['name_owner']; // фамилия
                            if($id == $id_owner){
                                echo "<option value=\"$id\" selected>$name1</option>"; // выводим
                            } else {
                                echo "<option value=\"$id\">$name1</option>"; // выводим
                            }
                        }
                    echo '</select><br>
                      <input class="btn-success" type="submit" name="update" value="Сохранить">
                  </form>
                  <button href="catalog.php" class="btn-danger">Вернуться в каталог</button>
                </div>';
            
    /* ----------------Создаем путь для загрузки картинки и добавления в БД ------------------------*/
    $path = 'img/'; // Путь к дериктории
    // Придумать имя для фалов, можно по имени директории или имени пользователя, который аплодит, 
    // что бы потом можно было легко ориентироваться
    $fileNamePattern = 'image';
    //Получить количество уже существующих файлов
    if ($handle = opendir($path)) { 
        $counter = 0;
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $counter++;
            }
        }
        closedir($handle);
        // получить новое имя
        $newFileName  = $fileNamePattern.'-'.$counter;
    }

    $file = "img/".$_FILES['image']['name'];
    $ext = end(explode('.', $file));
    $filename = "img/".$newFileName.".".$ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $filename);
    /* ----------------------------------------*/

    require_once 'connection.php'; // подключаем скрипт
                    
    // подключаемся к серверу
    $link = mysqli_connect($host, $user, $password, $database) 
        or die("Ошибка " . mysqli_error($link));

    // если были переданы данные для обновления информации
    if( isset($_POST['update'])) {
        $id_grader = $_POST['edid'];
        $name_grader = htmlspecialchars($_POST['name_grader']);
        $engine_power = htmlspecialchars($_POST['engine_power']);
        $weight = htmlspecialchars($_POST['weight']);
        $blade_height = htmlspecialchars($_POST['blade_height']);
        $blade_width = htmlspecialchars($_POST['blade_width']);
        $price = htmlspecialchars($_POST['price']);
        $image = htmlspecialchars($filename);
        $id_owner = htmlspecialchars($_POST['id_owner']);

        $sql_res_prod=mysqli_query($link, "UPDATE graders SET 
            name_grader='$name_grader', engine_power='$engine_power', weight='$weight', 
            blade_height='$blade_height', blade_width='$blade_width', price='$price',
            image='$image', id_owner='$id_owner' WHERE id_grader='$id_grader'")
                or die("Ошибка " . mysqli_error($link));

            // если при выполнении запроса произошла ошибка – выводим сообщение
        if( mysqli_errno($link) )
        echo '<hr><div class="error col" style="color:red; font-size:20px">Информация об автогрейдере не обновлена</div>'.mysqli_error($link);
        else // если все прошло нормально – выводим сообщение
        echo '<hr><div class="ok col" style="color:green; font-size:20px">Информация об автогрейдере обновлена</div>';
        echo "<script>window.location = 'catalog.php'</script>";
    }
    } else {
        echo 'Авторизуйтесь как администратор';
    }
    }
    ?>
</body>
</html>