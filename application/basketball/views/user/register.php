<script type="text/javascript"> 
$(document).ready
(   function()
    {     
        // Fonction pour afficher le champ date
        $('#datepicker').datepicker();
        
        
        // Fonction pour afficher la bulle "Plus d'infos !"
        $("#show_optional_infos").popover('show'); 
        $("#show_optional_infos").click(function()
        {
            $("#show_optional_infos").popover('destroy');
        });
        
        // Si l'url contient "register_submit", on ouvre directement la partie "facultative" (pour voir les erreurs de form)
        if(window.location.href.indexOf("register_submit") > -1) {
            $('#facultatives').collapse('show');
        }
        
    }
);
</script>

<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Inscription</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-info">Seuls votre prénom, votre équipe et vos rôles seront rendu publics.<br/>
                    <b>Votre numéro de téléphone</b> peut être utile à l'USF pour vous contacter en cas de besoin mais <b>restera privé</b>.<br/>
                Votre adresse e-mail vous servira uniquement à vous connecter.</p>
            </div>
        </div>
        <div class="row-fluid">
            <form class="form-horizontal" method="POST" action="<?= site_url('user/register_submit')?>">
<fieldset>

<!-- Form Name -->
<legend>Informations obligatoires</legend>

<div class="row-fluid">
    <div class="span6">
        <!-- Text input-->
        <div class="control-group">
          <label class="control-label" for="user_mail_input">E-mail</label>
          <div class="controls">
            <input id="user_mail_input" name="user_mail_input" type="email" placeholder="example@domaine.fr" class="input-large" required value="<?php echo set_value('user_mail_input'); ?>">
            <?php echo form_error('user_mail_input'); ?>
          </div>
        </div>

        <!-- Password input-->
        <div class="control-group">
          <label class="control-label" for="user_pass_input">Mot de passe</label>
          <div class="controls">
            <input id="user_pass_input" name="user_pass_input" type="password" placeholder="*****" class="input-large" required>
            <?php echo form_error('user_pass_input'); ?>
          </div>
        </div>
        
         <!-- Password input-->
        <div class="control-group">
          <label class="control-label" for="user_passconfirm_input">Confirmation</label>
          <div class="controls">
            <input id="user_passconfirm_input" name="user_passconfirm_input" type="password" placeholder="*****" class="input-large" required>
            <?php echo form_error('user_passconfirm_input'); ?>
          </div>
        </div>
    </div>
    <div class="span6">
        <!-- Text input-->
        <div class="control-group">
          <label class="control-label" for="user_lastname_input">Nom</label>
          <div class="controls">
            <input id="user_lastname_input" name="user_lastname_input" type="text" placeholder="PARKER" class="input-large" required="" value="<?php echo set_value('user_lastname_input'); ?>">
            <?php echo form_error('user_lastname_input'); ?>
          </div>
        </div>

        <!-- Text input-->
        <div class="control-group">
          <label class="control-label" for="user_firstname_input">Prénom</label>
          <div class="controls">
            <input id="user_firstname_input" name="user_firstname_input" type="text" placeholder="Tony" class="input-large" required="" value="<?php echo set_value('user_firstname_input'); ?>">
            <?php echo form_error('user_firstname_input'); ?>
          </div>
        </div>
        
    </div>
</div>
<br/>
</fieldset>
     
     
<div class="accordion">

<fieldset>
<!-- Form Name -->

      
<legend>
    Informations facultatives
    <a id="show_optional_infos" class="accordion-toggle badge badge-inverse" data-toggle="collapse" href="#facultatives" rel="popover" data-html="true" data-content="<h5>Plus d'infos !</h5>" >+</a>
</legend>
    
<div id="facultatives" class="accordion-body collapse">
      
<div class="row-fluid">
    <div class="span6">

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="user_cellphone_input">N° Tél.</label>
  <div class="controls">
    <input id="user_cellphone_input" name="user_cellphone_input" type="text" placeholder="0600000000" class="input-large" value="<?php echo set_value('user_cellphone_input'); ?>">
    <?php echo form_error('user_cellphone_input'); ?>
  </div>
</div>
    </div>

<div class="span6">
<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="user_birthdate_input">Date de naissance</label>
  <div class="controls">
  <div class="input-append date" id="datepicker" data-date="" data-date-format="dd/mm/yyyy" >
    <input id="user_birthdate_input" name="user_birthdate_input" type="text" placeholder="01/01/1980" class="input-medium" value="<?php echo set_value('user_birthdate_input'); ?>" ><span class="add-on"><i class="icon-th"></i></span>
    <?php echo form_error('user_birthdate_input'); ?>
  </div></div>
</div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
<!-- Multiple Checkboxes -->
<div class="control-group">
  <label class="control-label" for="user_role_checkboxes">Rôle(s)</label>
  <div class="controls">
      <? foreach ($roles as $role) { ?>
    <label class="checkbox" for="user_role_checkboxes<?=$role->role_id?>">
      <input type="checkbox" name="user_role_checkboxes[]" id="user_role_checkboxes<?=$role->role_id?>" value="<?=$role->role_id?>">
      <?=$role->role_name?>
    </label>
      <?}?>
  </div>
</div>
    </div>
    <div class="span6">
<!-- Select Basic -->
<div class="control-group">
  <label class="control-label" for="user_team_select">Équipe</label>
  <div class="controls">
    <select id="user_team_select" name="user_team_select" class="input-large">
        <option value="NULL"></option>
        <? foreach ($teams as $team){?>
        <option value="<?=$team->team_id?>"><?=$team->team_name?></option>
        <?}?>
    </select>
  </div>
</div>
    </div>
</div>
            
    </div>
  
</fieldset></div>
  

        </div>
     <div class="row-fluid">
        <div class="span12  text-center">
            <div class="control-group">
              <label class="control-label" for="user_register_submit"></label>
              <div class="controls">
                <button type="submit" id="user_register_submit" name="user_register_submit" class="btn btn-primary btn-large">S'inscrire !</button>
              </div>
            </div>
        </div>
  


        </div>
      </form>
    </div>
    </div>

</div>

