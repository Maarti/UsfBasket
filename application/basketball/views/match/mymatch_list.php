<?$currentTime = date('Y-m-d h:i:s', time());?>
<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Présence aux matchs de mon équipe</h1>
       </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="span8">
                <br/>
                <p>Soyez avertis des présences et absences de vos coéquipiers aux matchs grâce au code couleur suivant :</p>
                <table class="table table-bordered"><tr>
                        <td class="registerPresent" style="text-align:center;">Présent</td>
                        <td class="registerAny" style="text-align:center;">Incertain</td>
                        <td class="registerAway" style="text-align:center;">Absent</td>
                </tr></table>
                <p>Cliquez sur <a class="btn btn-mini btn-success">Prs.</a> pour informer de votre présence à un match ou sur  <a class="btn btn-danger btn-mini">Abs.</a> pour prévenir de votre absence.</p>
            </div>
        </div>
    </div>
    
    <? if(isset($_GET['error'])){ 
            if($_GET['error']=='invalidParam'){?>
        <div class="row-fluid">
            <div class="span12">
                <br/>
                <p class="alert alert-error">Échec de l'inscription : Paramètres invalides ou le match a déjà eu lieu.</p>
            </div>
        </div><?}}?>    
    
    <div class="row-fluid">
    <div class="span12">
        <br/>
        <table class="table table-bordered table-condensed">
            <thead>
              <tr>
                  <th width="120px" style="vertical-align:middle; text-align: center;">Date des matchs</th>
                <?foreach ($teamPlayers as $teamPlayer){?>
                    <th style="vertical-align:middle; text-align: center;"><?=$teamPlayer->user_firstname?></th>
                <?}?>
                    <th style="text-align: center;">Nb. de présents</th>
              </tr>
            </thead>
            <tbody>
              <?foreach ($teamMatchs as $teamMatch){
                  if($teamMatch->match_date<$currentTime) // Si le match a deja eu lieu
                      $registerable=FALSE;
                  else  // Sinon on autorise laffichage des liens d'inscription
                      $registerable=TRUE;
                  $nbPresent=0;?>
                      <tr>
                      <td style="text-align: center;"><?=$teamMatch->match_datefr?></td>
                        <?foreach ($teamPlayers as $teamPlayer){?>
                            <?if(isset($registerMatrix[$teamMatch->match_id][$teamPlayer->user_id])){
                                if($registerMatrix[$teamMatch->match_id][$teamPlayer->user_id]=='1'){ // Si le joueur s'est inscrit "présent"
                                    $class='registerPresent';
                                    $title='Présent';
                                    $nbPresent++;
                                }else{ // Si le joueur est absent
                                    $class='registerAway';
                                    $title='Absent';
                                }
                            }else{ // Si le joueur n'est pas encore inscrit
                                $class='registerAny';
                                $title='Incertain';
                            }?>
                            <td class="<?=$class?>" title="<?=$title?>" style="text-align: center;">
                                <?if($teamPlayer->user_id==$session['id'] && $registerable){?>
                                <a href="<?=site_url('match/setPresence/'.$teamMatch->match_id.'/1')?>" class="btn btn-mini btn-success" title="Annoncer que je serai présent au match">Prs.</a> <a href="<?=site_url('match/setPresence/'.$teamMatch->match_id.'/0')?>" class="btn btn-mini btn-danger" title="Prévenir de mon absence au match">Abs.</a>
                                <?}?>
                            </td>
                        <?}?>
                        <td style="text-align: center;"><?=$nbPresent?></td>
                      </tr>
              <? } ?>
            </tbody>
          </table>
    </div>
    </div>
</div>