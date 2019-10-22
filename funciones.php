<?php

include_once 'conexion.php';

define('api', 'https://api.telegram.org/bot'.token.'/');

$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$callbackId = $update["callback_query"]["from"]["id"];
$callbackName = $update["callback_query"]["from"]["username"];
$callbackData = $update["callback_query"]["data"];

if ($callbackName=="") {
    $modo=1;
    $callbackName = $update["callback_query"]["message"]["from"]["first_name"];
}else{
    $callbackName = "@".$callbackName;
}

function callback($up){
  return $up["callback_query"];
}

function sendDeleteMessage($chatId, $messageId, $response, $links){
  sendMessage($chatId, $response, $links);
  deleteMessage($chatId, $messageId);
}

function sendMessage($chatId, $response, $links){
    if($links){
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true&disable_web_page_preview=true';
    }else{
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true';
    }
    file_get_contents($url);
}

function sendDeleteMessageNS($chatId, $messageId, $response, $links){
  sendMessage($chatId, $response, $links);
  deleteMessage($chatId, $messageId);
}

function sendMessageNS($chatId, $response, $links){
    if($links){
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_web_page_preview=true';
    }else{
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'';
    }
    file_get_contents($url);
}

function getStatus($chatId, $userId){

  $url = $GLOBALS[website].'/getChatMember?chat_id='.$chatId.'&user_id='.$userId.'';
  $update = file_get_contents($url);
  $update = json_decode($update, TRUE);

  $tipoUsuario = $update["result"]["status"];
  return $tipoUsuario;

}

function getPathPhoto($photoId){

  $url = $GLOBALS[website].'/getFile?file_id='.$photoId.'';
  $update = file_get_contents($url);
  $update = json_decode($update, TRUE);

  $file_path = $update["result"]["file_path"];
  return $file_path;

}

function deleteMessage($chatId, $messageId){
   $url = $GLOBALS[website].'/deleteMessage?chat_id='.$chatId.'&message_id='.$messageId;
   file_get_contents($url);
}

function sendPhoto($chatId,$urlphoto,$response){
  if($response == ""){
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&disable_notification=true';
  }else{
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&caption='.$response.'&disable_notification=true';
  }
  file_get_contents($url);
}

function sendSticker($chatId, $urlsticker){
  $url = $GLOBALS[website].'/sendSticker?chat_id='.$chatId.'&sticker='.$urlsticker.'&disable_notification=true';
  file_get_contents($url);
}

function sendGif($chatId, $urlgif){
  $url = $GLOBALS[website].'/sendAnimation?chat_id='.$chatId.'&animation='.$urlgif.'&disable_notification=true';
  file_get_contents($url);
}

function apiRequest($metodo){
    $req = file_get_contents(api.$metodo);
    return $req;
}

function inlineKeyboard($menud, $chat, $text){

  $menu = $menud;

  if(strpos($text, "\n")){
    $text = urlencode($text);
  }

  $d2 = array("inline_keyboard" => $menu, );

  $d2 = json_encode($d2);

  return apiRequest("sendMessage?chat_id=$chat&parse_mode=Markdown&text=$text&reply_markup=$d2");

}

function commandAdmin($command, $tipoUsuario, $chatId, $messageId){

  $respuesta = FALSE;

    switch($command){

      case '/config': case '/config@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/desconfig': case '/desconfig@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirExamen': case '/añadirExamen@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarExamen': case '/eliminarExamen@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirEntrega': case '/añadirEntrega@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarEntrega': case '/eliminarEntrega@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirHorario': case '/añadirHorario@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarHorario': case '/eliminarHorario@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/elegirDelegado': case '/elegirDelegado@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarDelegado': case '/eliminarDelegado@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirGuia': case '/añadirGuia@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarGuia': case '/eliminarGuia@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirHorario': case '/añadirHorario@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarHorario': case '/eliminarHorario@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirProfesor': case '/añadirProfesor@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarProfesor': case '/eliminarProfesor@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/añadirEnlace': case '/añadirEnlace@Delega_Bot':
        $respuesta = TRUE;
      break;

      case '/eliminarEnlace': case '/eliminarEnlace@Delega_Bot':
        $respuesta = TRUE;
      break;

    }

    if($respuesta){
      if($tipoUsuario == 'creator' || $tipoUsuario == 'administrator'){
        return $respuesta;
      }else{
        $response = "⛔ No tienes suficientes permisos en este grupo para realizar tal acción.";
        sendDeleteMessage($chatId, $messageId, $response, FALSE);
        exit;
      }
    }else{
      return $respuesta;
    }

}

function commandGrupo($command, $chatId, $messageId){

  $respuesta = FALSE;

  switch($command){

    case '/añadirExamen': case '/añadirExamen@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarExamen': case '/eliminarExamen@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirEntrega': case '/añadirEntrega@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarEntrega': case '/eliminarEntrega@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirHorario': case '/añadirHorario@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarHorario': case '/eliminarHorario@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/elegirDelegado': case '/elegirDelegado@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarDelegado': case '/eliminarDelegado@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirGuia': case '/añadirGuia@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarGuia': case '/eliminarGuia@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirHorario': case '/añadirHorario@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarHorario': case '/eliminarHorario@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirProfesor': case '/añadirProfesor@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarProfesor': case '/eliminarProfesor@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/añadirEnlace': case '/añadirEnlace@Delega_Bot':
      $respuesta = TRUE;
    break;

    case '/eliminarEnlace': case '/eliminarEnlace@Delega_Bot':
      $respuesta = TRUE;
    break;


  }

  if($respuesta){

    include 'conexion.php';

    $consulta="SELECT * FROM grupo WHERE idGrupo='$chatId';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos)==0){

      $response = "⛔ El grupo no se encuentra en la base de datos. Se debe realizar antes /config para ello.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);
      mysqli_close($conexion);
      exit;

    }else{
      return $respuesta;
    }

  }else{
    return $respuesta;
  }

}

function comprobarGrupo($chatId, $messageId){

  include 'conexion.php';

  $consulta="SELECT * FROM grupo WHERE idGrupo='$chatId';";
  $datos=mysqli_query($conexion,$consulta);

  if(mysqli_num_rows($datos)==0){

    $response = "⛔ El grupo no se encuentra en la base de datos. Un administrador debe utilizar /config antes.";
    sendDeleteMessage($chatId, $messageId, $response, FALSE);
    mysqli_close($conexion);
    exit;

  }

}

 ?>
