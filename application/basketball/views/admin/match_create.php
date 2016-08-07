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
          
            <form class="form-horizontal" method="POST" action="<?=site_url('admin/match_create_submit')?>">
            <fieldset>

            <!-- Form Name -->
            <legend>Créer un match</legend>
        <div class="row-fluid">
            <div class="span6">
                
            <!-- Radio type match-->
            <div class="control-group">
              <label class="control-label" for="match_home_radios">Type de match</label>
              <div class="controls">
                <label class="radio inline" for="match_home_radios-1">
                  <input type="radio" name="match_home_radios" id="match_home_radios-1" value="1" checked="checked">
                  Domicile
                </label>
                <label class="radio inline" for="match_home_radios-0">
                  <input type="radio" name="match_home_radios" id="match_home_radios-0" value="0">
                  Extérieur
                </label>
                  <?=form_error('match_home_radios')?>
              </div>
            </div>                
                
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_oponent_input">Adversaire</label>
              <div class="controls">
                <input id="match_oponent_input" name="match_oponent_input" type="text" placeholder="Marcilly-en-Vilette" class="input-xlarge" required="" value="<?=set_value('match_oponent_input')?>">
                <?=form_error('match_oponent_input')?>
              </div>
            </div>

            <!-- Select Basic -->
            <div class="control-group">
              <label class="control-label" for="match_team_select">Équipe</label>
              <div class="controls">
                <select id="match_team_select" name="match_team_select" class="input-xlarge">
                  <? foreach ($teams as $team){?>
                        <option value="<?=$team->team_id?>"><?=$team->team_name?></option>
                  <? }?>
                </select>
                  <?=form_error('match_team_select')?>
              </div>
            </div>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_date_input">Date</label>
              <div class="controls">
              <div class="input-append date" id="datepicker" data-date="" data-date-format="dd/mm/yyyy" >
                <input id="match_date_input" name="match_date_input" type="text" placeholder="01/01/2014" class="input-medium" value="<?=set_value('match_date_input')?>" ><span class="add-on"><i class="icon-th"></i></span>
              </div>
            <?=form_error('match_date_input')?></div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="match_hour_input">Heure</label>
              <div class="controls">
                <input id="match_hour_input" name="match_hour_input" type="text" placeholder="13:45" class="input-mini" required="" value="<?=set_value('match_hour_input')?>">
                <?=form_error('match_hour_input')?>
              </div>
            </div>
            

            <!-- Select Basic -->
            <div class="control-group">
              <label class="control-label" for="match_place_select">Lieu</label>
              <div class="controls">
                <select id="match_place_select" name="match_place_select" class="input-xlarge">
                  <? foreach ($places as $place){ ?>
                        <option value="<?=$place->place_id?>"><?=$place->place_name?></option>
                  <? }?>
                        <option value="other">Autre...</option>
                </select>
                <?=form_error('match_place_select')?>
              </div>
            </div>
            
            <div class="control-group" id="NewPlaceBlock">
              <label class="control-label" for="match_new_place_input">Nouveau Lieu</label>
              <div class="controls">
                <input id="match_new_place_input" name="match_new_place_input" type="text" placeholder="Complexe sportif Henry Fauquet" class="input-xlarge" required="" value="<?=set_value('match_new_place_input')?>">
                <?=form_error('match_new_place_input')?>
              </div>
              <label class="control-label" for="match_new_placeAddress_input">Adresse</label>
              <div class="controls">
                <input id="match_new_placeAddress_input" name="match_new_placeAddress_input" type="text" placeholder="45000 Orléans - 13 rue des Lilas" class="input-xlarge" required="" value="<?=set_value('match_new_placeAddress_input')?>">
                <?=form_error('match_new_placeAddress_input')?>
              </div>
            </div>
            </div>
            
            <div class="span6" id="homeMatch_volounteers">
            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_ar_radios">Arbitre(s) demandé(s)</label>
              <div class="controls">
                <label class="radio inline" for="match_ar_radios-0">
                  <input type="radio" name="match_ar_radios" id="match_ar_radios-0" value="2" checked="checked">
                  2
                </label>
                <label class="radio inline" for="match_ar_radios-1">
                  <input type="radio" name="match_ar_radios" id="match_ar_radios-1" value="1">
                  1
                </label>
                <label class="radio inline" for="match_ar_radios-2">
                  <input type="radio" name="match_ar_radios" id="match_ar_radios-2" value="0">
                  0
                </label>
                  <?=form_error('match_ar_radios')?>
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_mr_radios">Marqueur demandé</label>
              <div class="controls">
                <label class="radio inline" for="match_mr_radios-0">
                  <input type="radio" name="match_mr_radios" id="match_mr_radios-0" value="1" checked="checked">
                  1
                </label>
                <label class="radio inline" for="match_mr_radios-1">
                  <input type="radio" name="match_mr_radios" id="match_mr_radios-1" value="0">
                  0
                </label>
                  <?=form_error('match_mr_radios')?>
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="match_cr_radios">Chronométreur demandé</label>
              <div class="controls">
                <label class="radio inline" for="match_cr_radios-0">
                  <input type="radio" name="match_cr_radios" id="match_cr_radios-0" value="1" checked="checked">
                  1
                </label>
                <label class="radio inline" for="match_cr_radios-1">
                  <input type="radio" name="match_cr_radios" id="match_cr_radios-1" value="0">
                  0
                </label>
                  <?=form_error('match_cr_radios')?>
              </div>
            </div>
</div>
        </div>
            <!-- Button (Double) -->
            <div class="control-group span6 offset2">
              <div class="controls">
                <button type="submit" id="match_create_submit_button" name="match_create_submit_button" class="btn btn-large btn-primary">Créer</button>
                <a href="<?=site_url('admin/match')?>" id="match_create_cancel_button" name="match_create_cancel_button" class="btn btn-large btn-danger">Annuler</a>
              </div>
            </div>
           
            </fieldset>
            </form>

            
        </div>
    </div>
</div>


<script type="text/javascript"> 
$(document).ready
(   function()
    {        
        //Fonction pour afficher les champs "Nouveau Lieu" et "Addresse" lorsque l'utilisateur sélectionne "Autre" dans la liste déroulante de Lieu
        $('#NewPlaceBlock').hide();
        $('#match_new_place_input').val($("select[id='match_place_select'] option:selected").text());
        $('#match_new_placeAddress_input').val($("select[id='match_place_select'] option:selected").text());
        $("select[id='match_place_select']").change
        (   function()
            {
                var place_chosen = $("select[id='match_place_select'] option:selected").val();
                var place_value = $("select[id='match_place_select'] option:selected").text();
                if(place_chosen=='other'){
                    // Quand "Autre est sélectionné, on affiche les champs de création de Lieu
                    $('#match_new_placeAddress_input').val('');
                    $('#match_new_place_input').val('');
                    $('#NewPlaceBlock').show();
                    $('#match_new_place_input').focus();                    
                }else{
                    $('#NewPlaceBlock').hide();
                    $('#match_new_placeAddress_input').val(place_value);
                    $('#match_new_place_input').val(place_value);
                }
            }
        );

        // Fonction pour afficher le nombre de volontaires à cocher si c'est un match à domicile
        $('#match_home_radios-0').click(function() {
            if($('#match_home_radios-0').is(':checked')) {
                $('#homeMatch_volounteers').hide();
            }
        });
        $('#match_home_radios-1').click(function() {
            if($('#match_home_radios-1').is(':checked')) {
                $('#homeMatch_volounteers').show();
            }
        });

    }     
);
</script>