<?php
//to read the files
$folder = './static/files/*';

//get all the files in folder
$files  = glob($folder);

//define main path
$path = './static/files/'
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./static/styles/styles.css" />
  <link rel="shortcut icon" href="https://findicons.com/files/icons/2813/flat_jewels/512/file.png" sizes="16x16 32x32" type="image/png">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
  </script>
  <script defer src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script async src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <title>Tarea 3</title>
</head>

<body>
  <main class="main">
    <section class="main-title">
      <h1>Manipulación de archivos con <span class="main-title--accent">PHP</span></h1>
      <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_6gadk1by.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
      <p class="main-title__assignment">Tarea 3 - Desarrollo Web con Software Libre</p>
      <p class="main-title__details"><span>Alumno:</span> Julio Eduardo Canizalez Salinas</p>
    </section>
    <section class="main-content">
      <div class="main-content__files">
        <h2>Gestión de archivos</h2>
        <form action="index.php" method="POST" class="files">
          <select name="files_list" class='files-list'>
            <option selected disabled>Selecciona un archivo</option>
            <?php
            foreach ($files as $file) {
              if (is_file($file)) {
                $file = substr($file, 15);
                echo "<option value='$path$file'>$file</option>";
              }
            }
            ?>
          </select>
          <button type="submit" name="open" class="button">Abrir</button>
          <button type="submit" name="add_to_file" class="button">Agregar al archivo</button>
          <button type="submit" name="rewrite" class="button">Sobrescribir archivo</button>
          <button type="submit" name="delete" class="button">Eliminar</button>
          <div class="main-content__editor" id="editor">
            <h2>Editor</h2>
            <?php
            if (isset($_POST['add_to_file'])) {
              $file_to_add = fopen($_POST['files_list'], "a") or die("No se puede abrir el archivo");
              $path_to_add = $_POST['files_list'];
              $file_content = $_POST['file_content'];
              fwrite($file_to_add, $file_content);
              $filename = substr($path_to_add, 15);
              fclose($file_to_add);
              echo "
                  <script type='text/javascript'>
                  $(document).ready(function(){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Se añadió el nuevo texto',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    setTimeout(()=> { location.replace('index.php');}, 1300);
                  });
                  </script>
                  ";
            }

            if (isset($_POST['rewrite'])) {
              $file_to_rewrite = fopen($_POST['files_list'], "w+") or die("No se puede abrir el archivo");
              $path_to_rewrite = $_POST['files_list'];
              $file_content = $_POST['file_content'];
              fwrite($file_to_rewrite, $file_content);
              $filename = substr($path_to_rewrite, 15);
              fclose($file_to_rewrite);
              echo "
                  <script type='text/javascript'>
                  $(document).ready(function(){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: '$filename sobrescrito',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    setTimeout(()=> { location.replace('index.php');}, 1300);
                  });
                  </script>
                  ";
            }

            if (isset($_POST['delete'])) {
              $file_to_delete = $_POST['files_list'];
              try {
                unlink($file_to_delete);
                echo "
                  <script type='text/javascript'>
                  $(document).ready(function(){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Archivo borrado',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    setTimeout(()=> { location.replace('index.php');}, 1300);
                  });
                  </script>
                  ";
              } catch (Exception $e) {
              }
            }

            if (isset($_POST['save_file'])) {
              $name = $_POST['new_file_name'];
              $new_file = fopen($path . $name, "w") or die("No se puede crear el nuevo archivo, intenta con otro nombre");
              $file_content = $_POST['file_content'];
              try {
                fwrite($new_file, $file_content);
                fclose($new_file);
                echo "
                  <script type='text/javascript'>
                  $(document).ready(function(){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Archivo guardado correctamente!',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    setTimeout(()=> { location.replace('index.php');}, 1300);
                  });
                  </script>
                  ";
              } catch (Exception $e) {
              }
            }
            ?>
            <textarea type='text' name='file_content' placeholder='Agrega aquí el texto al archivo' class='input__content'></textarea>
            <div class='new-file'>
              <input type='text' name='new_file_name' placeholder='Ingresa el nombre del archivo' class='input'>
              <button type='submit' name='save_file' class="button">Nuevo archivo</button>
            </div>

            <?php
            if (isset($_POST['open'])) {
              $path_to_open = $_POST['files_list'];
              $filename = substr($path_to_open, 15);
              $file_text = file_get_contents($path_to_open);
              echo "
                  <script type='text/javascript'>
                  $(document).ready(function(){
                    Swal.fire('$file_text')
                  });
                  </script>
                  ";
            }
            ?>
          </div>
        </form>
      </div>
    </section>
  </main>
</body>

</html>