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
            echo '
            <div class="back">
                <a href="admin.php" style="color: black;">
                    <img src="img/arrow.png" alt="стрелка назад" class="arrow-back">
                    <span>Вернуться на панель администратора</span>
                </a>
            </div>
              <div class="add-grader">
              <h2>Добавление автогрейдера</h2>
                <form action="add-grader.php" method="POST" enctype="multipart/form-data" class="big-form">
                      <label for="name_grader">Наименование автогрейдера:</label><br>
                        <input id="name_grader" name="name_grader" type="text" required><br>
                      <label for="engine_power">Мощность двигателя:</label><br>
                        <input name="engine_power" type="number" min="1" id="engine_power" required><br>
                      <label for="weight">Масса:</label><br>
                        <input name="weight" type="number" min="1" id="weight" required><br>
                      <label for="blade_height">Длина лезвия:</label><br>
                        <input name="blade_height" type="number" min="1" id="blade_height" required><br>
                      <label for="blade_width">Ширина лезвия:</label><br>
                        <input name="blade_width" type="number" min="1" id="blade_width" required><br>
                      <label for="price">Стоимость:</label><br>
                        <input name="price" type="number" min="1" id="price" required><br>
                      <label for="image">Фотография автогрейдера:</label><br>
                        <input name="image" type="file" accept=".png, .jpg, .jpeg" id="image" required><br>
                      <label for="image">Владелец автогрейдера:</label><br>
                      <select name="id_owner">
                      ';

                      require_once 'connection.php'; // подключаем скрипт
                        // подключаемся к серверу
                        $link = mysqli_connect($host, $user, $password, $database) 
                          or die("Ошибка " . mysqli_error($link));
                        
                        $sql_res = mysqli_query($link, "SELECT * FROM owners");
                        
                        while($row = mysqli_fetch_assoc($sql_res)){
                            $id = $row['id_owner']; // идентификатор
                            $name1 = $row['name_owner']; // фамилия
                            echo "<option value=\"$id\">$name1</option>"; // выводим
                        }
                    echo '</select><br>
                      <input class="btn-success" type="submit" name="save" value="Сохранить">
                  </form>
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

    // если были переданы данные для добавления в БД
    if( isset($_POST['save'])) {

      $sql_res_prod=mysqli_query($link, 'INSERT INTO graders (`name_grader`, `weight`, `engine_power`, `blade_height`,
        `blade_width`, `price`, `image`,`id_owner`) VALUES (
          "'.htmlspecialchars($_POST['name_grader']).'",
          "'.htmlspecialchars($_POST['weight']).'",
          "'.htmlspecialchars($_POST['engine_power']).'",
          "'.htmlspecialchars($_POST['blade_height']).'",
          "'.htmlspecialchars($_POST['blade_width']).'",
          "'.htmlspecialchars($_POST['price']).'",
          "'.htmlspecialchars($filename).'",
          "'.htmlspecialchars($_POST['id_owner']).'")');

      // если при выполнении запроса произошла ошибка – выводим сообщение
      if( mysqli_errno($link))
        echo '<hr><div class="error col" style="color:red; font-size:20px">Запись не добавлена</div>'.mysqli_error($link);
      else // если все прошло нормально – выводим сообщение
        echo '<hr><div class="ok col" style="color:green; font-size:20px">Запись добавлена</div>';
    }
    } else {
        echo 'Авторизуйтесь как администратор';
    }
    ?>
</body>
</html>