<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Connexion</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <? if(isset($msg))
            echo $msg;?>
        
<form class="form-horizontal" method="POST" action="<?= site_url('user/connect_submit')?>">
<fieldset>

<!-- Form Name -->
<legend>Informations de connexion</legend>
<div class="row-fluid">
<div class="span3"> </div>
     <div class="span6">
<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="connect_mail_input">E-mail</label>
  <div class="controls">
    <input id="connect_mail_input" name="connect_mail_input" type="email" placeholder="exemple@domaine.fr" class="input-xlarge" required value="<?php echo set_value('connect_mail_input'); ?>">
    <?= form_error('connect_mail_input') ?>
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="connect_pass_input">Mot de passe</label>
  <div class="controls">
    <input id="connect_pass_input" name="connect_pass_input" type="password" placeholder="*****" class="input-xlarge" required>
    <?= form_error('connect_pass_input') ?>
  </div>
</div>

<!-- Button -->
<div class="control-group">
  <label class="control-label" for="user_connect_submit"></label>
  <div class="controls">
    <button id="user_connect_submit" name="user_connect_submit" class="btn btn-primary btn-large">Se connecter</button>
  </div>
</div>
     </div>
</div>
</fieldset>
</form>

        

    </div>
    </div>
</div>