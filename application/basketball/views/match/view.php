<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1><?=$this_match[0]->team_name?> (USF) contre <?=$this_match[0]->match_oponent?></h1>
            <?$date=substr($this_match[0]->match_datefr, 0,-6);
            $hour=substr($this_match[0]->match_datefr, 11);?>
           <h4>Le <b><?=$date?></b> <?if($this_match[0]->match_place!=0){?> au <?=$this_match[0]->place_name?> <?}?>à <b><?=$hour?></b></h4>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12"><br/><br/><br/>
        <div class="row-fluid">
            <div class="span4">
                <?if($this_match[0]->match_ar==0){ //Si aucun arbitre n'est demandé?> 
                    <center>
                        <a class="btn btn-large btn-inverse disabled" title="Aucun arbitre n'est demandé">Aucun arbitre n'est demandé</a>
                        <?if($session['rank']>=90){//Si admin, on affiche le plus et le moins?>
                             <!-- TODO   <br/><a href="<?=site_url('admin/match_edit/'.$this_match[0]->match_id.'/add/arbitre')?>" title="Demander un arbitre supplémentaire"><img src="<?= base_url('assets/img/plus.png')?>" width="20" height="20"/></a> -->
                        <?}?>
                    </center>
                    
                <?}else{?>
                    <center><a href="#arbitre_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal">Se porter volontaire pour arbitrer</a></center>
                
                <br/>                
                <table class="table table-bordered table-hover">
                  <caption>Arbitre(s) demandé(s) : <?=$this_match[0]->match_ar?></caption>
                  <tbody>
                     <?
                     foreach ($this_matchs_a as $this_match_a){ 
                      $name=htmlspecialchars($this_match_a->user_firstname).' '.substr(htmlspecialchars($this_match_a->user_lastname),0,1).'.';
                      $state=$this_match_a->match_arbitre_state;
                        switch ($state) {
                          case 2:
                              $class='';
                              $statement='Inscrit';
                              break;
                          case 1:
                              $class='success';
                              $statement='Confirmé';
                              break;
                          case 3:
                              $class='error';
                              $statement='Refusé';
                              break;
                          case 4:
                              $class='error';
                              $statement='Annulé';
                              break;
                          default:
                              $class='';
                              $statement='';
                              break;
                         }
                              ?>
                    <tr class="<?=$class?>">
                      <td><b><?=$name?></b></td>
                      <td>
                          <i><?=$statement?></i>
                          <span class="pull-right">
                              <?if($this_match_a->match_arbitre_uid==$session['id']&&$statement=='Inscrit'){//Si c'est l'utilisateur actuel, afficher le bouton annuler?>
                                <a href="#cancel_arbitre_modal" role="button" class="btn btn-danger" data-toggle="modal">Annuler</a>
                              <?}?>
                              <?if($session['rank']>=90){//Si l'utilisateur actuelle est admin, afficher les boutons confirmer et refuser'?>
                                <a href="<?=site_url('admin/match_moderation/confirm/'.$this_match_a->match_arbitre_mid.'/'.$this_match_a->match_arbitre_uid.'/arbitre')?>" title="Confirmer"><i class="icon-ok"></i></a>
                                <a href="<?=site_url('admin/match_moderation/refuse/'.$this_match_a->match_arbitre_mid.'/'.$this_match_a->match_arbitre_uid.'/arbitre')?>" title="Refuser"><i class="icon-remove"></i></a>
                              <?}?>
                          </span>
                      </td>
                    </tr>
                     <?}?>
                  </tbody>
                </table><?}?>
            </div>
            
            <div class="span4">
                <?if($this_match[0]->match_mr==0){?>
                    <center><a class="btn btn-large btn-inverse disabled" title="Aucun marqueur n'est demandé">Aucun marqueur n'est demandé</a></center>
                <?}else{?>
                <center><a href="#marqueur_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal">Se porter volontaire pour tenir la feuille</a></center>
                
                <br/>
                <table class="table table-bordered table-hover">
                  <caption>Marqueur demandé : <?=$this_match[0]->match_mr?></caption>
                  <tbody>
                     <?
                     foreach ($this_matchs_m as $this_match_m){ 
                      $name=htmlspecialchars($this_match_m->user_firstname.' '.substr(htmlspecialchars($this_match_m->user_lastname),0,1).'.');
                      $state=$this_match_m->match_marqueur_state;
                        switch ($state) {
                          case 2:
                              $class='';
                              $statement='Inscrit';
                              break;
                          case 1:
                              $class='success';
                              $statement='Confirmé';
                              break;
                          case 3:
                              $class='error';
                              $statement='Refusé';
                              break;
                          case 4:
                              $class='error';
                              $statement='Annulé';
                              break;
                          default:
                              $class='';
                              $statement='';
                              break;
                         }
                              ?>
                    <tr class="<?=$class?>">
                      <td><b><?=$name?></b></td>
                      <td>
                          <i><?=$statement?></i>
                          <span class="pull-right">
                              <?if($this_match_m->match_marqueur_uid==$session['id']&&$statement=='Inscrit'){//Si c'est l'utilisateur actuel, afficher le bouton annuler?>
                                <a href="#cancel_marqueur_modal" role="button" class="btn btn-danger" data-toggle="modal">Annuler</a>
                              <?}?>
                              <?if($session['rank']>=90){//Si l'utilisateur actuelle est admin, afficher les boutons confirmer et refuser'?>
                                <a href="<?=site_url('admin/match_moderation/confirm/'.$this_match_m->match_marqueur_mid.'/'.$this_match_m->match_marqueur_uid.'/marqueur')?>" title="Confirmer"><i class="icon-ok"></i></a>
                                <a href="<?=site_url('admin/match_moderation/refuse/'.$this_match_m->match_marqueur_mid.'/'.$this_match_m->match_marqueur_uid.'/marqueur')?>" title="Refuser"><i class="icon-remove"></i></a>
                              <?}?>
                          </span>
                      </td>
                    </tr>
                     <?}?>
                  </tbody>
                </table><?}?>
            </div>
            
            <div class="span4">
                <?if($this_match[0]->match_cr==0){?>
                    <center><a class="btn btn-large btn-inverse disabled" title="Aucun chronomètreur n'est demandé">Aucun chronomètreur n'est demandé</a></center>
                <?}else{?>
                <center><a href="#chrono_modal" role="button" class="btn btn-large btn-primary" data-toggle="modal">Se porter volontaire pour chronométrer</a></center>
                <br/>
                <table class="table table-bordered table-hover">
                  <caption>Chronométreur demandé : <?=$this_match[0]->match_cr?></caption>
                  <tbody>
                     <?
                     foreach ($this_matchs_c as $this_match_c){ 
                      $name=htmlspecialchars($this_match_c->user_firstname).' '.substr(htmlspecialchars($this_match_c->user_lastname),0,1).'.';
                      $state=$this_match_c->match_chrono_state;
                        switch ($state) {
                          case 2:
                              $class='';
                              $statement='Inscrit';
                              break;
                          case 1:
                              $class='success';
                              $statement='Confirmé';
                              break;
                          case 3:
                              $class='error';
                              $statement='Refusé';
                              break;
                          case 4:
                              $class='error';
                              $statement='Annulé';
                              break;
                          default:
                              $class='';
                              $statement='';
                              break;
                         }
                              ?>
                    <tr class="<?=$class?>">
                      <td><b><?=$name?></b></td>
                      <td>
                          <i><?=$statement?></i>
                          <span class="pull-right">
                              <?if($this_match_c->match_chrono_uid==$session['id']&&$statement=='Inscrit'){//Si c'est l'utilisateur actuel, afficher le bouton annuler?>
                                <a href="#cancel_chrono_modal" role="button" class="btn btn-danger" data-toggle="modal">Annuler</a>
                              <?}?>
                              <?if($session['rank']>=90){//Si l'utilisateur actuelle est admin, afficher les boutons confirmer et refuser'?>
                                <a href="<?=site_url('admin/match_moderation/confirm/'.$this_match_c->match_chrono_mid.'/'.$this_match_c->match_chrono_uid.'/chrono')?>" title="Confirmer"><i class="icon-ok"></i></a>
                                <a href="<?=site_url('admin/match_moderation/refuse/'.$this_match_c->match_chrono_mid.'/'.$this_match_c->match_chrono_uid.'/chrono')?>" title="Refuser"><i class="icon-remove"></i></a>
                              <?}?>
                          </span>
                      </td>
                    </tr>
                     <?}?>
                  </tbody>
                </table><?}?>
            </div>
            
        </div>        
    </div>
    </div>
</div>

<?if($this_match[0]->match_ar>0){ // On ne charge le modal que lorsque c'est nécessaire?>
<!-- MODAL INSCRIPTION ARBITRE -->
<div id="arbitre_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="arbitre_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="arbitre_modalLabel">Voulez-vous vous inscrire pour arbitrer ce match ?</h3>
  </div>
  <div class="modal-body">
    <p>Un administrateur devra confirmer votre inscription.<br/>Vous pourrez annuler votre inscription (avant la veille du match).</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="arbitre"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="arbitre_register_input" name="arbitre_register_input" class="btn btn-primary">S'inscrire</button>
  </div>
      </form>
</div>
    
<!-- MODAL ANNULER ARBITRE -->
<div id="cancel_arbitre_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cancel_arbitre_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="cancel_arbitre_modalLabel">Êtes-vous sûr de vouloir annuler votre inscription ?</h3>
  </div>
  <div class="modal-body">
    <p>Si vous annulez votre inscription, vous ne pourrez plus vous inscrire à nouveau en tant qu'arbitre pour ce match.</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="arbitre"/>
                    <input type="hidden" name="cancel_input" id="cancel_input" value="TRUE"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="cancel_arbitre_register_input" name="cancel_arbitre_register_input" class="btn btn-primary">Confirmer</button>
  </div>
      </form>
</div>    
<?}?>


<?if($this_match[0]->match_mr>0){?>
<!-- MODAL INSCRIPTION MARQUEUR -->
<div id="marqueur_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="marqueur_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="marqueur_modalLabel">Voulez-vous vous inscrire pour tenir la feuille de match ?</h3>
  </div>
  <div class="modal-body">
    <p>Un administrateur devra confirmer votre inscription.<br/>Vous pourrez annuler votre inscription (avant la veille du match).</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="marqueur"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="marqueur_register_input" name="marqueur_register_input" class="btn btn-primary">S'inscrire</button>
  </div>
</form>
</div>
    
    
<!-- MODAL ANNULER MARQUEUR -->
<div id="cancel_marqueur_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cancel_marqueur_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="cancel_marqueur_modalLabel">Êtes-vous sûr de vouloir annuler votre inscription ?</h3>
  </div>
  <div class="modal-body">
    <p>Si vous annulez votre inscription, vous ne pourrez plus vous inscrire à nouveau en tant que marqueur pour ce match.</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="marqueur"/>
                    <input type="hidden" name="cancel_input" id="cancel_input" value="TRUE"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="cancel_marqueur_register_input" name="cancel_marqueur_register_input" class="btn btn-primary">Confirmer</button>
  </div>
      </form>
</div>
<?}?>


<?if($this_match[0]->match_cr>0){?>
<!-- MODAL INSCRIPTION CHRONO -->
<div id="chrono_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="chrono_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="chrono_modalLabel">Voulez-vous vous inscrire pour chronométrer ce match ?</h3>
  </div>
  <div class="modal-body">
    <p>Un administrateur devra confirmer votre inscription.<br/>Vous pourrez annuler votre inscription (avant la veille du match).</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="chrono"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="chrono_register_input" name="chrono_register_input" class="btn btn-primary">S'inscrire</button>
  </div>
      </form>
</div>
    
<!-- MODAL ANNULER CHRONO -->
<div id="cancel_chrono_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cancel_chrono_modalLabel" aria-hidden="true">
  <form class="form-horizontal" method="POST" action="<?=site_url('match/register')?>">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="cancel_chrono_modalLabel">Êtes-vous sûr de vouloir annuler votre inscription ?</h3>
  </div>
  <div class="modal-body">
    <p>Si vous annulez votre inscription, vous ne pourrez plus vous inscrire à nouveau en tant que chronométreur pour ce match.</p>
                    <input type="hidden" name="uid_input" id="uid_input" value="<?=$session['id']?>"/>
                    <input type="hidden" name="mid_input" id="mid_input" value="<?=$this_match[0]->match_id?>"/>
                    <input type="hidden" name="registered_role_input" id="registered_role_input" value="chrono"/>
                    <input type="hidden" name="cancel_input" id="cancel_input" value="TRUE"/>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" id="cancel_chrono_register_input" name="cancel_chrono_register_input" class="btn btn-primary">Confirmer</button>
  </div>
      </form>
</div><?}?>