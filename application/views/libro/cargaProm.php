<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Subir una o varias imagenes al servidor</title>
</head>
 
<body>
    <?php
   ini_set('post_max_size','1000M');
ini_set('upload_max_filesize','1000M');
ini_set('max_execution_time','6000');
ini_set('max_input_time','6000');
    # definimos la carpeta destino
    $carpetaDestino= getcwd().'/csv/';
 
    # si hay algun archivo que subir
    if(isset($_FILES["archivo"]["name"][0]))
    {
 
        # recorremos todos los arhivos que se han subido
        for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
        {
            
            # si es un formato de imagen
            if($_FILES["archivo"]["type"][$i]=="image/jpeg" || $_FILES["archivo"]["type"][$i]=="image/pjpeg" || $_FILES["archivo"]["type"][$i]=="image/gif" || $_FILES["archivo"]["type"][$i]=="image/png")
            {
 
                # si exsite la carpeta o se ha creado
                if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
                {
                    $origen=$_FILES["archivo"]["tmp_name"][$i];
                    $destino=$carpetaDestino.$_FILES["archivo"]["name"][$i];
 
                    # movemos el archivo
                    if(@move_uploaded_file($origen, $destino))
                    {
                        echo "<br>".$_FILES["archivo"]["name"][$i]." movido correctamente";
                    }else{
                        echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo"]["name"][$i];
                    }
                }else{
                    echo "<br>No se ha podido crear la carpeta: up/".$user;
                }
            }else{
                echo "<br>".$_FILES["archivo"]["type"][$i]." - NO es imagen jpg";
            }
        }
    }else{
        echo "<br>No se ha subido ninguna imagen";
    }
    ?>
 
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" name="inscripcion">
        <input type="file" name="archivo[]" multiple="multiple">
        <input type="submit" value="Enviar"  class="trig">
    </form>
</body>
</html>