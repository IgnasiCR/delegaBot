<?php

// SISTEMA DE ESTADOS DEL USUARIO PARA SABER QUE SE VA A HACER CON SU PRÓXIMO MENSAJE.

include_once 'conexion.php';

$consulta="SELECT * FROM `usuario` WHERE id='$userId';";
$datos=mysqli_query($conexion,$consulta);

if(mysqli_num_rows($datos)>0){
  $fila=mysqli_fetch_array($datos,MYSQLI_ASSOC);

  if($fila['estado']==1){

    $tipoUsuario = getStatus($chatId, $userId);

    if($tipoUsuario == 'creator' || $tipoUsuario == 'administrator'){

      $photo = end($update["message"]["photo"]);
      $photoId = $photo["file_id"];

      $escritura='<?php $idPhoto="'.$photoId.'"; ?>';
      $file = fopen("grupos/$chatId/$chatId-horario.php","w");
      fwrite($file, $escritura);
      fclose($file);

      $response = "✅ El horario del grupo se ha añadido sin problemas en el sistema.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);

      $consulta="UPDATE usuario SET estado='0' WHERE id='$userId';";
      mysqli_query($conexion, $consulta);

      exit;

    }else{
      $response = "⛔ No tienes suficientes permisos en este grupo para realizar tal acción.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);

      $consulta="UPDATE usuario SET estado='0' WHERE id='$userId';";
      mysqli_query($conexion, $consulta);

      exit;
    }

  }

}

 ?>
