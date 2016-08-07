<? if(displayCurrentDailyMessage()){
        $msg = displayCurrentDailyMessage();
        if ($msg['type']==1)
            echo '<div class="row-fluid">'.$msg['body'].'</div>';
}?>