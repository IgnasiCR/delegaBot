<?php

include_once 'funciones.php';
include_once 'variables.php';
include_once 'estados.php';

// CON EL EXPLODE TOMAMOS EL PRIMER VALOR DEL MENSAJE ASÍ VEMOS SI ESTÁ USANDO EL COMANDO O NO.
$arr = explode(' ',trim($message));
$command = $arr[0];

$message = substr(strstr($message," "), 1);

if($chatType == 'group' || $chatType == 'supergroup'){

  $tipoUsuario = getStatus($chatId, $userId);

  if(commandAdmin($command, $tipoUsuario, $chatId, $messageId)){

    if(commandGrupo($command, $chatId, $messageId)){

      switch($command){

        case '/añadirExamen': case '/añadirExamen@Delega_Bot':

          // AÑADIR UN EXAMEN,

          $div = explode('#', trim($message));

          $asignatura = $div[0];
          $temas = $div[1];
          $fecha = $div[2];
          $grupo = $div[3];

          if($asignatura == '' || $temas == '' || $fecha = '' || $grupo = ''){
            $response = "⛔ Imposible añadir examen a la base de datos. Hay algún campo que has dejado vacío. Si no recuerda cuál era la estructura utilice /ayudaAdmin para más información.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);
          }else{

            include 'conexion.php';

            $consulta="INSERT INTO examen (idGrupo, asignatura, temas, fecha, grupo) VALUES ('$chatId', '$div[0]', '$div[1]', '$div[2]', '$div[3]');";
            mysqli_query($conexion, $consulta);

            $response = "✅ El examen se ha introducido con éxito en la base de datos.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }

          mysqli_close($conexion);
          exit;

        break;

        case '/eliminarExamen': case '/eliminarExamen@Delega_Bot':

          // ELIMINAR UN EXAMEN EN CONCRETO.

          if(is_numeric($message)){

            include 'conexion.php';

            $consulta = "SELECT * FROM examen WHERE idGrupo='$chatId' AND id='$message'";
            $datos = mysqli_query($conexion, $consulta);

            if(mysqli_num_rows($datos)>0){

            $consulta="DELETE FROM examen WHERE id='$message'";
            mysqli_query($conexion, $consulta);

            $response = "✅ $firstname examen eliminado de la base de datos con éxito.";

            }else{
              $response = "⛔ $firstname el identificador que has seleccionado no corresponde con ninguno que tenga asociado el grupo. Revisa bien.";
            }

            sendDeleteMessage($chatId, $messageId, $response, FALSE);
            mysqli_close($conexion);
            exit;

          }else{

            include 'conexion.php';

            $consulta="SELECT * FROM `examen` WHERE idGrupo = '$chatId' ORDER BY `fecha` ASC;";
            $datos = mysqli_query($conexion, $consulta);

            if(mysqli_num_rows($datos)>0){
              $response = "$firstname los exámenes que se encuentran en la base de datos son los siguientes: \n";

              while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
                $response .= "\nIdentificador: $fila[id]\nAsignatura: $fila[asignatura]\nMateria: $fila[temas]\nFecha: $fila[fecha]\nGrupo: $fila[grupo]\n";
              }

              $response .= "\n\nUtilice el comando /eliminarExamen identificador para eliminar el examen que deseees.";

              sendDeleteMessage($chatId, $messageId, $response, TRUE);
              mysqli_close($conexion);

            }else{
              $response = "⛔ ¡$firstname ahora mismo no hay exámenes en la base de datos!";
            }
        }

        break;

        case '/añadirEntrega': case '/añadirEntrega@Delega_Bot':

          // AÑADIR UNA ENTREGA.

          $div = explode('#', trim($message));

          $asignatura = $div[0];
          $descripcion = $div[1];
          $fecha = $div[2];
          $enlace = $div[3];
          $grupo = $div[4];

          if($asignatura == '' || $descripcion == '' || $fecha = '' || $enlace == '' || $grupo = ''){
            $response = "⛔ Imposible añadir entrega a la base de datos. Hay algún campo que has dejado vacío. Si no recuerda cuál era la estructura utilice /ayudaAdmin para más información.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);
          }else{

            include 'conexion.php';

            $consulta="INSERT INTO entregas (idGrupo, asignatura, descripcion, fecha, enlace, grupo) VALUES ('$chatId', '$div[0]', '$div[1]', '$div[2]', '$div[3]', '$div[4]');";
            mysqli_query($conexion, $consulta);

            $response = "✅ La entrega se ha introducido con éxito en la base de datos.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }

          mysqli_close($conexion);
          exit;

        break;

        case '/eliminarEntrega': case '/eliminarEntrega@Delega_Bot':

          // ELIMINAR UNA ENTREGA EN CONCRETO.

          if(is_numeric($message)){

            include 'conexion.php';

            $consulta = "SELECT * FROM entregas WHERE idGrupo='$chatId' AND id='$message'";
            $datos = mysqli_query($conexion, $consulta);

            if(mysqli_num_rows($datos)>0){

            $consulta="DELETE FROM entregas WHERE id='$message'";
            mysqli_query($conexion, $consulta);

            $response = "✅ $firstname entrega eliminada de la base de datos con éxito.";

            }else{
              $response = "⛔ $firstname el identificador que has seleccionado no corresponde con ninguno que tenga asociado el grupo. Revisa bien.";
            }

            sendDeleteMessage($chatId, $messageId, $response, FALSE);
            mysqli_close($conexion);
            exit;

          }else{

            include 'conexion.php';

            $consulta="SELECT * FROM `entregas` WHERE idGrupo = '$chatId' ORDER BY `fecha` ASC;";
            $datos = mysqli_query($conexion, $consulta);

            if(mysqli_num_rows($datos)>0){
              $response = "$firstname las entregas que se encuentran en la base de datos son las siguientes: \n";

              while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
                $response .= "\nIdentificador: $fila[id]\nAsignatura: $fila[asignatura]\nDescripción: $fila[descripcion]\nFecha: $fila[fecha]\nEnlace: $fila[enlace]\nGrupo: $fila[grupo]\n";
              }

              $response .= "\n\nUtilice el comando /eliminarEntrega identificador para eliminar la entrega que deseees.";

              sendDeleteMessage($chatId, $messageId, $response, TRUE);
              mysqli_close($conexion);

            }else{
              $response = "⛔ ¡$firstname ahora mismo no hay entregas en la base de datos!";
            }
        }

        break;

        case '/añadirGuia': case '/añadirGuia@Delega_Bot':

          // AÑADIR UNA GUIA

          $div = explode('#', trim($message));

          $asignatura = $div[0];
          $enlace = $div[1];

          if($asignatura == '' || $enlace == '' ){
            $response = "⛔ Imposible añadir la guía a la base de datos. Hay algún campo que has dejado vacío. Si no recuerda cuál era la estructura utilice /ayudaAdmin para más información.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);
          }else{

            include 'conexion.php';

            $consulta="INSERT INTO guias (idGrupo, asignatura, enlace) VALUES ('$chatId', '$div[0]', '$div[1]');";
            mysqli_query($conexion, $consulta);

            $response = "✅ La guía se ha introducido con éxito en la base de datos.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }

          mysqli_close($conexion);
          exit;

        break;

        case '/eliminarGuia': case '/eliminarGuia@Delega_Bot':

        // ELIMINAR UNA GUÍA EN CONCRETO.

        if(is_numeric($message)){

          include 'conexion.php';

          $consulta = "SELECT * FROM guias WHERE idGrupo='$chatId' AND id='$message'";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){

          $consulta="DELETE FROM guias WHERE id='$message'";
          mysqli_query($conexion, $consulta);

          $response = "✅ $firstname guía eliminada de la base de datos con éxito.";

          }else{
            $response = "⛔ $firstname el identificador que has seleccionado no corresponde con ninguno que tenga asociado el grupo. Revisa bien.";
          }

          sendDeleteMessage($chatId, $messageId, $response, FALSE);
          mysqli_close($conexion);
          exit;

        }else{

          include 'conexion.php';

          $consulta="SELECT * FROM `guias` WHERE idGrupo = '$chatId' ORDER BY `id` ASC;";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){
            $response = "$firstname las guías que se encuentran en la base de datos son las siguientes: \n";

            while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
              $response .= "\nIdentificador: $fila[id]\nAsignatura: $fila[asignatura]\nEnlace: $fila[enlace]\n";
            }

            $response .= "\n\nUtilice el comando /eliminarGuia identificador para eliminar la guía que deseees.";

            sendDeleteMessage($chatId, $messageId, $response, TRUE);
            mysqli_close($conexion);
            exit;

          }else{
            $response = "⛔ ¡$firstname ahora mismo no hay guías en la base de datos!";
          }
      }

        break;

        case '/añadirEnlace': case '/añadirEnlace@Delega_Bot':

          // AÑADIR UN REPOSITORIO

          $div = explode('#', trim($message));

          $nombre = $div[0];
          $enlace = $div[1];

          if($nombre == '' || $enlace == '' ){
            $response = "⛔ Imposible añadir el enlace a la base de datos. Hay algún campo que has dejado vacío. Si no recuerda cuál era la estructura utilice /ayudaAdmin para más información.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);
          }else{

            include 'conexion.php';

            $consulta="INSERT INTO repositorios (idGrupo, nombre, enlace) VALUES ('$chatId', '$div[0]', '$div[1]');";
            mysqli_query($conexion, $consulta);

            $response = "✅ El enlace se ha introducido con éxito en la base de datos.";
            sendDeleteMessage($chatId, $messageId, $response, TRUE);

          }

          mysqli_close($conexion);
          exit;

        break;

        case '/eliminarEnlace': case '/eliminarEnlace@Delega_Bot':

        // ELIMINAR UN REPOSITORIO EN CONCRETO.

        if(is_numeric($message)){

          include 'conexion.php';

          $consulta = "SELECT * FROM repositorios WHERE idGrupo='$chatId' AND id='$message'";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){

          $consulta="DELETE FROM repositorios WHERE id='$message'";
          mysqli_query($conexion, $consulta);

          $response = "✅ $firstname enlace eliminado de la base de datos con éxito.";

          }else{
            $response = "⛔ $firstname el identificador que has seleccionado no corresponde con ninguno que tenga asociado el grupo. Revisa bien.";
          }

          sendDeleteMessage($chatId, $messageId, $response, FALSE);
          mysqli_close($conexion);
          exit;

        }else{

          include 'conexion.php';

          $consulta="SELECT * FROM `repositorios` WHERE idGrupo = '$chatId' ORDER BY `id` ASC;";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){
            $response = "$firstname los enlaces que se encuentran en la base de datos son las siguientes: \n";

            while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
              $response .= "\nIdentificador: $fila[id]\nNombre: $fila[nombre]\nEnlace: $fila[enlace]\n";
            }

            $response .= "\n\nUtilice el comando /eliminarEnlaces identificador para eliminar la guía que deseees.";

            sendDeleteMessage($chatId, $messageId, $response, TRUE);
            mysqli_close($conexion);
            exit;

          }else{
            $response = "⛔ ¡$firstname ahora mismo no hay guías en la base de datos!";
          }
      }

        break;

        case '/añadirProfesor': case '/añadirProfesor@Delega_Bot':

        // AÑADIR UN PROFESOR

        $div = explode('#', trim($message));

        $asignatura = $div[0];
        $nombre = $div[1];
        $correo = $div[2];
        $despacho = $div[3];
        $horario = $div[4];

        if($asignatura == '' || $nombre == '' || $despacho = '' || $correo = '' || $horario = ''){
          $response = "⛔ Imposible añadir al profesor en la base de datos. Hay algún campo que has dejado vacío. Si no recuerda cuál era la estructura utilice /ayudaAdmin para más información.";
          sendDeleteMessage($chatId, $messageId, $response, FALSE);
        }else{

          include 'conexion.php';

          $consulta="INSERT INTO profesores (idGrupo, asignatura, nombre, correo, despacho, horario) VALUES ('$chatId', '$div[0]', '$div[1]', '$div[2]', '$div[3]', '$div[4]');";
          mysqli_query($conexion, $consulta);

          $response = "✅ El profesor se ha introducido con éxito en la base de datos.";
          sendDeleteMessage($chatId, $messageId, $response, FALSE);

        }

        mysqli_close($conexion);
        exit;

        break;

        case '/eliminarProfesor': case '/eliminarProfesor@Delega_Bot':

        // ELIMINAR UN PROFESOR EN CONCRETO.

        if(is_numeric($message)){

          include 'conexion.php';

          $consulta = "SELECT * FROM profesores WHERE idGrupo='$chatId' AND id='$message'";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){

          $consulta="DELETE FROM profesores WHERE id='$message'";
          mysqli_query($conexion, $consulta);

          $response = "✅ $firstname profesor eliminado de la base de datos con éxito.";

          }else{
            $response = "⛔ $firstname el identificador que has seleccionado no corresponde con ninguno que tenga asociado el grupo. Revisa bien.";
          }

          sendDeleteMessage($chatId, $messageId, $response, FALSE);
          mysqli_close($conexion);
          exit;

        }else{

          include 'conexion.php';

          $consulta="SELECT * FROM `profesores` WHERE idGrupo = '$chatId' ORDER BY `id` ASC;";
          $datos = mysqli_query($conexion, $consulta);

          if(mysqli_num_rows($datos)>0){
            $response = "$firstname los profesores que se encuentran en la base de datos son las siguientes: \n";

            while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
              $response .= "\nIdentificador: $fila[id]\nAsignatura: $fila[asignatura]\nProfesor/a: $fila[nombre]\nCorreo: $fila[correo]\nDespacho: $fila[despacho]\nHorario:\n$fila[horario]\n";
            }

            $response .= "\n\nUtilice el comando /eliminarProfesor identificador para eliminar al profesor que deseees.";

            sendDeleteMessage($chatId, $messageId, $response, TRUE);
            mysqli_close($conexion);
            exit;

          }else{
            $response = "⛔ ¡$firstname ahora mismo no hay profesores en la base de datos!";
          }
      }

        break;

        case '/añadirHorario': case '/añadirHorario@Delega_Bot':

          // AÑADIR UN HORARIO CON UNA IMAGEN.

          include 'conexion.php';

          $consulta="SELECT * FROM usuario WHERE id='$userId';";
          $datos=mysqli_query($conexion,$consulta);

          if(mysqli_num_rows($datos)>0){
            $consulta="UPDATE usuario SET estado='1' WHERE id='$userId';";
            mysqli_query($conexion, $consulta);
          }else{
            $consulta="INSERT INTO `usuario` (id, nombre, estado) VALUES ('$userId', '$firstname', '1');";
            mysqli_query($conexion, $consulta);
          }

          $response = "De acuerdo, $firstname. A continuación deberás enviarme la imagen que quieres poner como horario.";
          sendDeleteMessage($chatId, $messageId, $response, FALSE);
          mysqli_close($conexion);
          exit;

        break;

        case '/eliminarHorario': case '/eliminarHorario@Delega_Bot':

          // ELIMINAR EL HORARIO.

          $escritura='<?php $idPhoto=""; ?>';
          $file = fopen("grupos/$chatId/$chatId-horario.php","w");
          fwrite($file, $escritura);
          fclose($file);

          $response = "✅ Horario eliminado con éxito del sistema.";
          sendDeleteMessage($chatId, $messageId, $response, FALSE);

        break;

        case '/elegirDelegado': case '/elegirDelegado@Delega_Bot':

          if(is_numeric($message)){

            $escritura='<?php $idDelegado='.$message.'; ?>';
            $file = fopen("grupos/$chatId/$chatId-delegado.php","w");
            fwrite($file, $escritura);
            fclose($file);

            $response = "✅ Delegado con identificador: $message, insertado con éxito.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }else{
            $response = "⛔ No está indicando ningún identificador de usuario. Inténtelo más tarde.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);
          }

        break;

        case '/eliminarDelegado': case '/eliminarDelegado@Delega_Bot':

          include "grupos/$chatId/$chatId-delegado.php";

          if($idDelegado != ""){

            $response = "✅ Delegado con identificador: $idDelegado, eliminado con éxito.";

            $escritura='<?php $idDelegado=""; ?>';
            $file = fopen("grupos/$chatId/$chatId-delegado.php","w");
            fwrite($file, $escritura);
            fclose($file);

            sendDeleteMessage($chatId, $messageId, $response, FALSE);

        }else{
          $response = "⛔ No existe actualmente ningún delegado en el sistema.";
          sendDeleteMessage($chatId, $messageId, $response, FALSE);
        }

        break;

      }

    }else{

      switch($command){

        case '/config': case '/config@Delega_Bot':

          // COMPROBAR SI NO EXISTE EL GRUPO CONFIGURADO EN LA BASE DE DATOS.
          // DE SER ASÍ CREAR EL GRUPO EN LA BD, Y EN CONTRARIO INFORMAR SOBRE ELLO.

          include 'conexion.php';

          $consulta="SELECT * FROM grupo WHERE idGrupo='$chatId';";
          $datos=mysqli_query($conexion,$consulta);

          if(mysqli_num_rows($datos)==0){

            $consulta="INSERT INTO `grupo` VALUES ('$chatId', '$chatUsername','$chatTitle');";
            mysqli_query($conexion, $consulta);

            mkdir("grupos/$chatId", 0755);
            $file = fopen("grupos/$chatId/$chatId-estados.php","w");
            fclose($file);

            $escritura='<?php $idDelegado=""; ?>';
            $file = fopen("grupos/$chatId/$chatId-delegado.php","w");
            fwrite($file, $escritura);
            fclose($file);

            $escritura='<?php $idPhoto=""; ?>';
            $file = fopen("grupos/$chatId/$chatId-horario.php","w");
            fwrite($file, $escritura);
            fclose($file);

            $response = "✅ El grupo ha sido configurado en la base de datos con éxito.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }else{

            $response = "⛔ El grupo que quieres configurar ya está configurado en la base de datos.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }

          mysqli_close($conexion);
          exit;

        break;

        case '/desconfig': case '/desconfig@Delega_Bot':

          // COMPROBAR SI EXISTE EL GRUPO CONFIGURADO EN LA BASE DE DATOS.
          // DE SER ASÍ ELIMINAR EL GRUPO EN LA BD, Y EN CONTRARIO INFORMAR SOBRE ELLO.

          include 'conexion.php';

          $consulta="SELECT * FROM grupo WHERE idGrupo='$chatId';";
          $datos=mysqli_query($conexion,$consulta);

          if(mysqli_num_rows($datos)>0){

            $consulta="DELETE FROM grupo WHERE idGrupo='$chatId';";
            mysqli_query($conexion, $consulta);

            unlink("grupos/$chatId/$chatId-estados.php");
            unlink("grupos/$chatId/$chatId-delegado.php");
            unlink("grupos/$chatId/$chatId-horario.php");
            rmdir("grupos/$chatId");

            $response = "✅ El grupo ha sido desconfigurado de la base de datos con éxito.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }else{

            $response = "⛔ El grupo no se encuentra en la base de datos. No se puede desconfigurar algo que no está configurado.";
            sendDeleteMessage($chatId, $messageId, $response, FALSE);

          }

          mysqli_close($conexion);
          exit;


        break;

      }

    }

  }else{

    switch($command){

    case '/examenes': case '/examenes@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      // VER TODOS LOS EXÁMENES DEL GRUPO.

      include 'conexion.php';

      $grupo = 0;

      if(is_numeric($message)){
        $consulta="SELECT * FROM `examen` WHERE idGrupo = '$chatId' AND grupo = '$message' ORDER BY `fecha` ASC;";
        $datos = mysqli_query($conexion, $consulta);
        $grupo = $message;
      }else if($message != ""){
        $like = "%$message%";
        $consulta="SELECT * FROM `examen` WHERE idGrupo = '$chatId' AND asignatura LIKE '$like';";
        $datos = mysqli_query($conexion, $consulta);
      }else if($message == ""){
        $consulta="SELECT * FROM `examen` WHERE idGrupo = '$chatId' AND grupo = '0' ORDER BY `fecha` ASC;";
        $datos = mysqli_query($conexion, $consulta);
      }

      if(mysqli_num_rows($datos)>0){
        $response = "📝 $firstname los exámenes próximos del grupo $grupo son: \n";

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
          $response .= "\nAsignatura: $fila[asignatura]\nMateria: $fila[temas]\nFecha: $fila[fecha]\n";
        }
      }else{
        $response = "✌ ¡$firstname estás de suerte! Ahora mismo no hay exámenes para el grupo seleccionado.";
      }

      sendDeleteMessage($chatId, $messageId, $response, FALSE);
      mysqli_close($conexion);
      exit;

    break;

    case '/entregas': case '/entregas@Delega_Bot':

    comprobarGrupo($chatId, $messageId);

      // VER TODOS LAS ENTREGAS DEL GRUPO.

      include 'conexion.php';

      $grupo = 0;

      if(is_numeric($message)){
        $consulta="SELECT * FROM `entregas` WHERE idGrupo = '$chatId' AND grupo = '$message' ORDER BY `fecha` ASC;";
        $datos = mysqli_query($conexion, $consulta);
        $grupo = $message;
      }else if($message != ""){
        $like = "%$message%";
        $consulta="SELECT * FROM `entregas` WHERE idGrupo = '$chatId' AND asignatura LIKE '$like';";
        $datos = mysqli_query($conexion, $consulta);
      }else if($message == ""){
        $consulta="SELECT * FROM `entregas` WHERE idGrupo = '$chatId' AND grupo = '0' ORDER BY `fecha` ASC;";
        $datos = mysqli_query($conexion, $consulta);
      }

      if(mysqli_num_rows($datos)>0){
        $response = "📬 $firstname las entregas próximas del grupo $grupo son: \n";

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
          $response .= "\n* <a href='$fila[enlace]'>$fila[fecha] - $fila[asignatura] $fila[descripcion]</a>";
        }
      }else{
        $response = "✌ ¡$firstname estás de suerte! Ahora mismo no hay entregas para el grupo seleccionado.";
      }

      sendDeleteMessage($chatId, $messageId, $response, FALSE);
      mysqli_close($conexion);
      exit;

    break;

    case '/guias': case '/guias@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      // VER TODOS LAS ENTREGAS DEL GRUPO.

      include 'conexion.php';

      $consulta="SELECT * FROM `guias` WHERE idGrupo = '$chatId'";
      $datos = mysqli_query($conexion, $consulta);

      if(mysqli_num_rows($datos)>0){
        $response = "📜 $firstname las guías son las siguientes: \n";

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
          $response .= "\n- <a href='$fila[enlace]'> $fila[asignatura]</a>";
        }
      }else{
        $response = "⛔ ¡$firstname actualmente no hay ninguna guía en la base de datos!";
      }

      sendDeleteMessage($chatId, $messageId, $response, TRUE);
      mysqli_close($conexion);
      exit;

    break;

    case '/enlaces': case '/enlaces@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      // VER TODOS LOS REPOSITORIOS DEL GRUPO.

      include 'conexion.php';

      $consulta="SELECT * FROM `repositorios` WHERE idGrupo = '$chatId'";
      $datos = mysqli_query($conexion, $consulta);

      if(mysqli_num_rows($datos)>0){
        $response = "🔗 $firstname los enlaces son las siguientes: \n";

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
          $response .= "\n- <a href='$fila[enlace]'> $fila[nombre]</a>";
        }
      }else{
        $response = "⛔ ¡$firstname actualmente no hay ningún enlace en la base de datos!";
      }

      sendDeleteMessage($chatId, $messageId, $response, TRUE);
      mysqli_close($conexion);
      exit;

    break;

    case '/profesores': case '/profesores@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      // VER TODOS LOS EXÁMENES DEL GRUPO.

      include 'conexion.php';

      if($message == ""){
        $consulta="SELECT * FROM `profesores` WHERE idGrupo = '$chatId';";
        $datos = mysqli_query($conexion, $consulta);
      }else if($message != ""){
        $like = "%$message%";
        $consulta="SELECT * FROM `profesores` WHERE idGrupo = '$chatId' AND asignatura LIKE '$like';";
        $datos = mysqli_query($conexion, $consulta);
      }

      if(mysqli_num_rows($datos)>0){
        $response = "👩‍🏫👨‍🏫 $firstname los profesores del grupo son: \n";

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){
          $response .= "\nAsignatura: $fila[asignatura]\nProfesor/a: $fila[nombre]\nCorreo: $fila[correo]\nHorarios:\n$fila[horario]\nDespacho: $fila[despacho]\n";
        }
      }else{
        $response = "✌ ¡$firstname estás de suerte! Ahora mismo no hay profesores para el grupo seleccionado.";
      }

      sendDeleteMessage($chatId, $messageId, $response, FALSE);
      mysqli_close($conexion);
      exit;

    break;

    case '/delegado': case '/delegado@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      include "grupos/$chatId/$chatId-delegado.php";

      if($idDelegado != ""){
        $response = "‼ Atención, el usuario $firstname te está buscando por el grupo $chatTitle.";
        sendMessageNS($idDelegado, $response, FALSE);

        $response = "📞 $firstname, llamando al delegado del grupo...";
        sendDeleteMessage($chatId, $messageId, $response, FALSE);
      }else{
        $response = "⛔ No existe actualmente ningún delegado en el sistema.";
        sendDeleteMessage($chatId, $messageId, $response, FALSE);
      }

    break;

    case '/horario': case '/horario@Delega_Bot':

      comprobarGrupo($chatId, $messageId);

      include 'grupos/'.$chatId.'/'.$chatId.'-horario.php';

      // VER EL HORARIO DEL GRUPO.

      if($idPhoto != ""){
        $response = '';
        sendPhoto($chatId,$idPhoto,$response);
        deleteMessage($chatId, $messageId);
      }else{
        $response = "⛔ No existe actualmente ningún horario en el sistema.";
        sendDeleteMessage($chatId, $messageId, $response, FALSE);
      }

      exit;

    break;

    case 'uWu':

    if($userId == 449567578){
        $urlsticker = "https://ignasicr.es/delegaBot/imgs/uwu.gif";
        sendSticker($chatId, $urlsticker);
        deleteMessage($chatId, $messageId);
    }

    break;

    case 'pole': case 'Pole': case 'POLE':
      $response = "⛔ ¿$firstname que te crees qué estamos en Forocoches? Telita...";
      sendMessage($chatId, $response, FALSE);
    break;

    case 'Delegado': case 'Delegao': case 'delegado': case 'delegao': case 'Delegada': case 'Delega': case 'delegada': case 'delega':
      $response = "♻ ¡¡DIMISIÓN!!";
      sendMessage($chatId, $response, FALSE);
    break;

    case 'Efe': case 'efe': case 'F': case 'f':
      $urlgif = "https://ignasicr.es/delegaBot/imgs/efe.webp";
      sendGif($chatId, $urlgif);
      deleteMessage($chatId, $messageId);
    break;

    // PALABRAS CLAVE EN CUALQUIER LUGAR DE UN MENSAJE PARA MENSAJES GRACIOSOS

    default:
          $tamanio = count($arr);
          for($i = 0; $i <= $tamanio; $i++){
              $command2 = $arr[$i];
              switch ($command2){
                  case 'bubo': case 'Bubo': case 'BUBO': case 'bubito': case 'Bubito': case 'bubo,': case 'BUBO,': case 'Bubo,':
                      $response = "Bubo, titiri titiri 🦉🎶";
                      sendMessage($chatId, $response, FALSE);
                      exit;
                  break;
                  case 'Ocho': case 'ocho': case '8':
                      $response = "¡Cómete un bizcocho!";
                      sendMessage($chatId, $response, FALSE);
                      exit;
                  break;
                  case 'catalunya': case 'Catalunya':
                  	$response = "Bon cop de falç! 🎶";
                  	sendMessage($chatId, $response, FALSE);
                    exit;
                  break;
                  case 'andalucia': case 'Andalucia': case 'andalucía': case 'Andalucía':
                    $response = "Verde que te quiero verde... 🎶";
                    sendMessage($chatId, $response, FALSE);
                  break;
                  case 'Oki': case 'oki': case 'OKI':
                    $response = "Oki, oki.";
                    sendMessage($chatId, $response, FALSE);
                    exit;
                  break;
              }
          }
      break;

    }

  }

include_once 'estados.php';

}else{

  switch($command){

    case '/start': case '/start@Delega_Bot':

      $response = "🚸 ¡Bienvenid@ a Delega Bot!\n";
      $response .= "\nEste bot hará que tus funciones como delegad@ en el grupo donde te encuentres sea mucho más llevadero. Ya que podrás disfrutar de todas las siguientes funciones:\n";
      $response .= "\n⚙ Sistema configuración\n📋 Sistema exámenes\n📬 Sistema entregas\n📜 Sistema guías docentes\n📯 Sistema delegados\n🗓 Sistema horarios\n👩‍🏫👨‍🏫 Sistema profesores\n🔗 Sistema enlaces\n";
      $response .= "\nPara más información sobre como funcionan los sistemas puedes utilizar /ayudaAdmin.";
      $response .= "\n\n<b>RECUERDA</b> que debes darle permisos de administrador y lectura de mensajes a DelegaBot para que funcione en el grupo.";

      sendDeleteMessage($chatId, $messageId, $response, TRUE);
      exit;

    break;

    case '/ayudaAdmin': case '/ayudaAdmin@Delega_Bot':

      $response = "💠 <b>Sistema de Ayuda Administrativa</b>\n\n";
      $response .= "<b>Recordatorio</b>: \n- El uso de los '#' para separar los datos es obligatorio, si no se hace así no podrá insertar nada.\n- Cada campo puede contener espacios, guiones o barras.\n- Fecha: DD/MM/YYYY.\n- Grupo grande: '0', Grupos prácticas: '1', '2', ... \n- Comandos tipo '/eliminar' mostrará la lista de datos y el identificador cada uno de ellos, para eliminar algo tenemos que utilizar lo siguiente: ejemplo: /eliminarEntrega 2, se eliminará la entrega con ese identificador de la base de datos.\n";

      $response .= "\n⚙ <b>Sistema configuración</b>\n";
      $response .= "/config -> Se configurará el grupo y dará acceso a todas las demás funcionalidades.\n";
      $response .= "/desconfig -> Desconfigurará el grupo y toda la información almacenada en la base de datos será automáticamente eliminada.\n";

      $response .= "\n📋 <b>Sistema exámenes</b>\n";
      $response .= "/añadirExamen asignatura#temas#fecha#grupo\n";
      $response .= "/eliminarExamen identificador\n";

      $response .= "\n📬 <b>Sistema entregas</b>\n";
      $response .= "/añadirEntrega asignatura#descripción#fecha#enlace#grupo\n";
      $response .= "/eliminarEntrega identificador\n";

      $response .= "\n📜 <b>Sistema guías docentes</b>\n";
      $response .= "/añadirGuia asignatura#enlace\n";
      $response .= "/eliminarGuia identificador\n";

      $response .= "\n📯 <b>Sistema delegados</b>\n";
      $response .= "/elegirDelegado identificador -> Obligatoriamente se debe dar un identificador de usuario. Para saber el ID de un usuario se puede hacer uso del /obtenerUid, el usuario recibirá su identificador por privado al utilizar tal comando.\n";
      $response .= "/eliminarDelegado\n";

      $response .= "\n🗓 <b>Sistema horarios</b>\n";
      $response .= "/añadirHorario -> Se tiene que enviar una imagen acto seguido de lanzar el comando.\n";
      $response .= "/eliminarHorario\n";

      $response .= "\n👩‍🏫👨‍🏫 <b>Sistema profesores</b>\n";
      $response .= "/añadirProfesor asignatura#nombre#correo#despacho#horario -> Se recomienda que los horarios tengan un salto de línea por cada día, así se verá mejor a la hora de mostrarlos al usuario.\n";
      $response .= "/eliminarProfesor identificador\n";

      $response .= "\n🔗 <b>Sistema enlaces</b>\n";
      $response .= "/añadirEnlaces nombre#enlace\n";
      $response .= "/eliminarEnlaces identificador\n";

      sendDeleteMessage($chatId, $messageId, $response, TRUE);

    break;

  }

}

  switch($command){

    case '/github': case '/github@Delega_Bot':
      $response = "El GitHub del creador es: <a href='https://github.com/IgnasiCR'>IgnasiCR</a>";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);
    break;

    case '/donaciones': case '/donaciones@Delega_Bot':

      $response .="\n💵 Si te gusta el bot y te gustaría que se siguiese mejorando, puedes dejar tu granito de arena en la siguiente cuenta:\n";
      $response .="\n<a href='paypal.me/IgnasiCR17'>PayPal - IgnasiCR17</a>";

      sendDeleteMessage($chatId, $messageId, $response, TRUE);

      exit;

    break;

    case '/obtenerGid': case '/obtenerGid@Delega_Bot':

      // SISTEMA PARA CONSEGUIR EL ID DEL CHAT

      $response = "$firstname el ID del chat es el: $chatId";
      deleteMessage($chatId, $messageId);
      sendMessage($userId, $response, FALSE);

    break;

    case '/obtenerUid': case '/obtenerUid@Delega_Bot':

      // SISTEMA PARA CONSEGUIR EL ID PROPIO

      $response = "$firstname tu ID es el: $userId";
      deleteMessage($chatId, $messageId);
      sendMessage($userId, $response, FALSE);

    break;

    case '/ayuda': case '/ayuda@Delega_Bot':

      $response = "💠 <b>Sistema de Ayuda</b>\n\n";
      $response .= "<b>Recordatorio</b>: \n- Donde aparezca 'palabraClave' se refiere a que buscará en el nombre de la asignatura alguna coincidencia con esa palabra. No obstante si no se incluye, mostrará toda la información almacenada, por ejemplo: /examenes FBD o /examenes\n";

      $response .= "\n📋 <b>Sistema exámenes</b>\n";
      $response .= "/examenes palabraClave -> Si se indica algún grupo de prácticas, ejemplo: /examenes 2, mostrará los exámenes del grupo 2, en caso contrario se mostrarán tan solo los del grupo grande (0).\n";

      $response .= "\n📬 <b>Sistema entregas</b>\n";
      $response .= "/entregas palabraClave -> Si se indica algún grupo de prácticas, ejemplo: /entregas 2, mostrará las entregas del grupo 2, en caso contrario se mostrarán tan solo las del grupo grande (0).\n";

      $response .= "\n📜 <b>Sistema guías docentes</b>\n";
      $response .= "/guias\n";

      $response .= "\n📯 <b>Sistema delegados</b>\n";
      $response .= "/delegado -> Se notificará por privado al delegado del grupo de que le están buscando.\n";

      $response .= "\n🗓 <b>Sistema horarios</b>\n";
      $response .= "/horario\n";

      $response .= "\n👩‍🏫👨‍🏫 <b>Sistema profesores</b>\n";
      $response .= "/profesores palabraClave\n";

      $response .= "\n🔗 <b>Sistema enlaces</b>\n";
      $response .= "/enlaces\n";

      sendDeleteMessage($chatId, $messageId, $response, TRUE);

    break;

}

?>
