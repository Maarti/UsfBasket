<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('is_arb'))
{
    function displayCurrentDailyMessage($block=FALSE)
    {
        $CI = get_instance();
        $CI->load->model('dailymessage_model');
        $currentMsg = $CI->dailymessage_model->getActiveDailyMessage();
 
        if(isset($currentMsg[0])) { // S'il y a bien un message actif à afficher
            if ($currentMsg[0]->dailyMessage_type == 1){ // Si le message est de type "urgent"
                $title = 'Message urgent ! ';
                $alert = 'alert';
            }else{
                $title = 'Message du jour :';
                $alert = 'info';
            }
            if ($block)
                $msg = '<div class="alert alert-'.$alert.' alert-block span12">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>'.$title.'</h4>
                        '.htmlspecialchars($currentMsg[0]->dailyMessage_body).'</div>';
            else
                $msg = '<div class="alert alert-'.$alert.' span12">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>'.$title.'</strong> '.htmlspecialchars($currentMsg[0]->dailyMessage_body).'</div>';
            $theMsg = array();
            $theMsg['type']=$currentMsg[0]->dailyMessage_type;
            $theMsg['body']=$msg;
            return $theMsg;
        }else{                      // S'il n'y a pas de message à afficher
            return '';
        }
    }
}

?>