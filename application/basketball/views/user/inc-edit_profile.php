<form class="form-horizontal" method="POST" action="<?= site_url('user/editprofile_submit')?>">

    <!-- Form Name -->
    <legend>Mot de passe obligatoire</legend>   

            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="user_pass_input">Mot de passe</label>
              <div class="controls">
                <input id="user_pass_input" name="user_pass_input" type="password" placeholder="*****" class="input-large" required>
                <?php echo form_error('user_pass_input'); ?>
              </div>
            </div>
            <br/>

    <!-- Form Name -->
    <legend>Modification de vos informations personnelles</legend>
    <div class="row-fluid">
        <div class="span6">
   <!-- New Password input-->
   <div class="control-group">
     <label class="control-label" for="user_newpass_input">Changer de mot de passe ?</label>
     <div class="controls">
       <input id="user_newpass_input" name="user_newpass_input" type="password" placeholder="*****" class="input-large">
       <p class="help-block">(Si non, laisser vide)</p>
           <?php echo form_error('user_newpass_input'); ?>
     </div>
   </div>

    <!-- Confirm Password input-->
   <div class="control-group">
     <label class="control-label" for="user_passconfirm_input">Confirmation</label>
     <div class="controls">
       <input id="user_passconfirm_input" name="user_passconfirm_input" type="password" placeholder="*****" class="input-large">
       <?php echo form_error('user_passconfirm_input'); ?>
     </div>
   </div>

    <!-- Text input-->
    <div class="control-group">
      <label class="control-label" for="user_cellphone_input">N° Port.</label>
      <div class="controls">
        <input id="user_cellphone_input" name="user_cellphone_input" type="text" placeholder="0600000000" class="input-large" value="<?=$this_user[0]->user_cellphone?>">
        <?php echo form_error('user_cellphone_input'); ?>
      </div>
    </div>

    <? 
    $birthdate = $this_user[0]->user_birthdate;
    if ($birthdate == '0000-00-00')
        $birthdate='';
    else{
        $year = substr($birthdate,0,4);
        $month = substr($birthdate,5,2);
        $day = substr($birthdate,8,2);
        $birthdate = $day.'/'.$month.'/'.$year;
    }
    ?>
    <!-- Text input-->
    <div class="control-group">
      <label class="control-label" for="user_birthdate_input">Date de naissance</label>
      <div class="controls">
      <div class="input-append date" id="datepicker" data-date="" data-date-format="dd/mm/yyyy" >
        <input id="user_birthdate_input" name="user_birthdate_input" type="text" placeholder="01/01/1980" class="input-medium" value="<?=$birthdate?>" ><span class="add-on"><i class="icon-th"></i></span>
        <?php echo form_error('user_birthdate_input'); ?>
      </div></div>
    </div>


    <!-- Multiple Checkboxes -->
    <div class="control-group">
      <label class="control-label" for="user_role_checkboxes">Rôle(s)</label>
      <div class="controls">
          <? foreach ($roles as $role) {
            $check_param='';
            switch ($role->role_label) {
                case 'A':
                    if($this_user[0]->user_isarbitre==1)
                            $check_param='checked="TRUE"';
                    break;
                case 'T':
                    if($this_user[0]->user_istable==1)
                            $check_param='checked="TRUE"';
                    break;
                case 'C':
                    if($this_user[0]->user_iscoach==1)
                            $check_param='checked="TRUE"';
                    break;    
                case 'E':
                    if($this_user[0]->user_istrainer==1)
                            $check_param='checked="TRUE"';
                    break;
                case 'J':
                    if($this_user[0]->user_isplayer==1)
                            $check_param='checked="TRUE"';
                    break;
            }
            ?>              
        <label class="checkbox" for="user_role_checkboxes<?=$role->role_id?>">
          <input type="checkbox" name="user_role_checkboxes[]" <?=$check_param?> id="user_role_checkboxes<?=$role->role_id?>" value="<?=$role->role_id?>">
          <?=$role->role_name?>
        </label>
          <?}?>
      </div>
    </div>
    <!-- Select Basic -->
    <div class="control-group">
      <label class="control-label" for="user_team_select">Équipe</label>
      <div class="controls">
        <select id="user_team_select" name="user_team_select" class="input-large">
            <option value="NULL"></option>
            <? foreach ($teams as $team){
                if($team->team_id==$this_user[0]->user_team)
                    $selected_param='selected="TRUE"';
                else
                    $selected_param='';?>
            <option <?=$selected_param?> value="<?=$team->team_id?>"><?=$team->team_name?></option>
            <?}?>
        </select>
      </div>
    </div> 
</div>
    </div>
    <br/>
    <div class="row-fluid">
        <div class="span12"><center>
        <a class="btn btn-large btn-danger" href="<?=site_url('user/view/'.$session['id'])?>">Annuler</a>
        &nbsp;&nbsp;<button type="submit" id="edit_account_submit_input" name="edit_account_submit_input" class="btn btn-large btn-primary">Valider</button></center>
        </div>
    </div>
    </form>