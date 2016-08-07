<script type="text/javascript"> 
$(document).ready
(   function()
    {          
        // Fonction pour afficher le champ date
        $('#datepicker').datepicker();
    }
);
</script>

<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Administration des matchs</h1>
       </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
          
            <form class="form-horizontal" method="POST" action="<?=site_url('admin/match_edit_submit/'.$thisMatch[0]->match_id)?>">
            <fieldset>

            <!-- Form Name -->
            <legend>Éditer un match</legend>
        <div class="row-fluid">
            <div class="span6">
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_oponent_input">Adversaire</label>
              <div class="controls">
                <input id="match_oponent_input" name="match_oponent_input" type="text" placeholder="Marcilly-en-Vilette" class="input-xlarge" required="" value="<?=$thisMatch[0]->match_oponent?>">
                <?=form_error('match_oponent_input')?>
              </div>
            </div>

            <!-- Select Basic -->
            <div class="control-group">
              <label class="control-label" for="match_team_select">Équipe</label>
              <div class="controls">
                <select id="match_team_select" name="match_team_select" class="input-xlarge">
                  <? foreach ($teams as $team){
                        if($team->team_id == $thisMatch[0]->match_team)
                            $selected='selected';
                        else
                            $selected='';
                        ?>
                        <option value="<?=$team->team_id?>" <?=$selected?>><?=$team->team_name?></option>
                  <? }?>
                </select>
                  <?=form_error('match_team_select')?>
              </div>
            </div>

            
            <? 
            $date = $thisMatch[0]->match_date;
            if ($date == '0000-00-00')
                $date='';
            else{
                $year = substr($date,0,4);
                $month = substr($date,5,2);
                $day = substr($date,8,2);
                $hour = substr($date,11,2).':'.substr($date,14,2);;
                
                $date = $day.'/'.$month.'/'.$year;
            }
            ?>
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_date_input">Date</label>
              <div class="controls">
              <div class="input-append date" id="datepicker" data-date="" data-date-format="dd/mm/yyyy" >
                <input id="match_date_input" name="match_date_input" type="text" placeholder="01/01/2014" class="input-medium" value="<?=$date?>" ><span class="add-on"><i class="icon-th"></i></span>
              </div>
            <?=form_error('match_date_input')?></div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_hour_input">Heure</label>
              <div class="controls">
                <input id="match_hour_input" name="match_hour_input" type="text" placeholder="13:45" class="input-mini" required="" value="<?=$hour?>">
                <?=form_error('match_hour_input')?>
              </div>
            </div>
            

            <!-- Select Basic -->
            <div class="control-group">
              <label class="control-label" for="match_place_select">Lieu</label>
              <div class="controls">
                <select id="match_place_select" name="match_place_select" class="input-xlarge">
                  <? foreach ($places as $place){ 
                      if($place->place_id == $thisMatch[0]->match_place)
                            $selected='selected';
                        else
                            $selected='';
                        ?>
                        <option value="<?=$place->place_id?>" <?=$selected?>><?=$place->place_name?></option>
                  <? }?>
                </select>
                <?=form_error('match_place_select')?>
              </div>
            </div>
            </div>
            
            <div class="span6">
            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_ar_radios">Arbitre(s) demandé(s)</label>
              <div class="controls">
                  <? for($i=2;$i>=0;$i--){
                  if($thisMatch[0]->match_ar==$i)
                        $checked='checked';
                    else
                        $checked='';
                    ?>
                    <label class="radio inline" for="match_ar_radios-<?=$i?>">
                        <input type="radio" name="match_ar_radios" id="match_ar_radios-<?=$i?>" value="<?=$i?>" <?=$checked?>>
                        <?=$i?>
                    </label>
                  <?}?>
                
                  <?=form_error('match_ar_radios')?>
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_mr_radios">Marqueur demandé</label>
              <div class="controls">
               <? for($i=1;$i>=0;$i--){
                  if($thisMatch[0]->match_mr==$i)
                        $checked='checked';
                    else
                        $checked='';
                    ?>
                    <label class="radio inline" for="match_mr_radios-<?=$i?>">
                        <input type="radio" name="match_mr_radios" id="match_mr_radios-<?=$i?>" value="<?=$i?>" <?=$checked?>>
                        <?=$i?>
                    </label>
                  <?}?>
                  <?=form_error('match_mr_radios')?>
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_cr_radios">Chronométreur demandé</label>
              <div class="controls">
                <? for($i=1;$i>=0;$i--){
                    if($thisMatch[0]->match_cr==$i)
                        $checked='checked';
                    else
                        $checked='';
                    ?>
                    <label class="radio inline" for="match_cr_radios-<?=$i?>">
                        <input type="radio" name="match_cr_radios" id="match_cr_radios-<?=$i?>" value="<?=$i?>" <?=$checked?>>
                        <?=$i?>
                    </label>
                <?}?>
                <?=form_error('match_cr_radios')?>
              </div>
            </div>
          </div>
        </div>
            <!-- Button (Double) -->
            <div class="control-group span6 offset2">
              <div class="controls">
                <button type="submit" id="match_edit_submit_button" name="match_edit_submit_button" class="btn btn-large btn-primary">Valider</button>
                <a href="<?=site_url('admin/match')?>" id="match_edit_cancel_button" name="match_edit_cancel_button" class="btn btn-large btn-danger">Annuler</a>
              </div>
            </div>
           
            </fieldset>
            </form>

            
        </div>
    </div>
</div>