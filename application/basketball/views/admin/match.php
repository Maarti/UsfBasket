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
            <? if(isset($_GET['edit'])){
                if($_GET['edit']=='success')
                    $edit_state='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Édition effectuée avec <strong>succès</strong>.</div>';
                elseif($_GET['edit']=='error')
                    $edit_state='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Échec de l\'édition</strong>. Erreur dans le formulaire.</div>';
                echo $edit_state;
            }?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <h2>Matchs Futurs</h2>
            <table class="table table-bordered table-hover table-striped">
                <caption><?=$pagination?></caption>
                <thead>
                  <tr>
                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Date</th>
                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Équipe</th>
                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Adversaire</th>
                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Heure</th>
                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Lieu</th>
                    <th colspan="3" style="vertical-align:middle; text-align: center;">Disponibles</th>
                    <th rowspan="2" width="40px"></th>
                  </tr>
                  <tr><th><abbr title="Arbitres">A</abbr></th><th><abbr title="Marqueurs">M</abbr></th><th><abbr title="Chronométreurs">C</abbr></th></tr>
                </thead>
                <tbody>
                  <?
                  foreach ($usersformatchs as $userformatch){
                          $date=substr($userformatch->match_datefr, 0,-6);
                          $hour=substr($userformatch->match_datefr, 11);

                          $ar=$userformatch->match_ar;              // arbitres requis
                          $ai=nb_arbitre($userformatch->match_id);  // arbitres inscrits
                          $mr=$userformatch->match_mr;              // marqueur requis
                          $mi=nb_marqueur($userformatch->match_id); // marqueurs inscrits
                          $cr=$userformatch->match_cr;              // chronométreur requis
                          $ci=nb_chrono($userformatch->match_id);   // chronométreur inscrits    

                          $total=0;                   
                          if($ar-$ai<=0)$diff=0;
                          else $diff=$ar-$ai;
                          $total=$total+$diff;

                          if($mr-$mi<=0)$diff=0;
                          else $diff=$mr-$mi;
                          $total=$total+$diff;

                          if($cr-$ci<=0)$diff=0;
                          else $diff=$cr-$ci;
                          $total=$total+$diff;

                          if($total==0)
                              $rowclass="success";        // S'il n'y a aucun inscrit => rouge
                          elseif($ar+$mr+$cr==$total)     // S'il y a au moins un inscrit mais qu'il manque du monde => orange
                              $rowclass="error";          // S'il y a assez de monde partout => vert
                          else
                              $rowclass="warning";
                          $delete_modal_id='#match_delete_modal'.$userformatch->match_id;
                          ?>

                    <tr class="<?=$rowclass?>" style="cursor:pointer;">
                      <td><?=$date?></td>
                      <td><span class="label label-info"><?=$userformatch->team_label?></span></td>
                      <td><?=$userformatch->match_oponent?></td>
                      <td><?=$hour?></td>
                      <td><abbr title="<?=$userformatch->place_address?>"><?=$userformatch->place_name?></abbr></td>
                      <td><abbr title="Arbitres inscrits/Arbitres demandés"><?=$ai?>/<?=$ar?></abbr></td>
                      <td><abbr title="Marqueurs inscrits/Marqueur demandé"><?=$mi?>/<?=$mr?></abbr></td>
                      <td><abbr title="Chronométreurs inscrits/Chronométreur demandé"><?=$ci?>/<?=$cr?></abbr></td>
                      <td><a href="<?=site_url('admin/match_edit/'.$userformatch->match_id)?>" title="Éditer"><i class="icon-edit"></i></a>&nbsp;<a href="<?=$delete_modal_id?>" role="button" data-toggle="modal" title="Supprimer"><i class="icon-remove"></i></a></td>
                    </tr>
                    <? } ?>
                    <tr><td colspan="9"><a href="<?=site_url('admin/match_create')?>" class="btn btn-large btn-primary pull-right">Créer un match</a></td></tr>
                  </tbody>
                </table>
         </div>  
    </div>
</div>


<?
foreach ($usersformatchs as $userformatch){ // Pour chaque match, on créé un modal pour la suppression
       $delete_modal_id='match_delete_modal'.$userformatch->match_id;
       
?>  
        <!-- Modal pour la suppression  -->
        <div id="<?=$delete_modal_id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?=$delete_modal_id?>" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="<?=$delete_modal_id?>">Suppression de match</h3>
          </div>
          <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer le match de <span class="label label-info"><?=$userformatch->team_label?></span> contre <?=$userformatch->match_oponent?> ?</p>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <a href="<?=site_url('admin/match_delete/'.$userformatch->match_id)?>" class="btn btn-danger">Supprimer</a>
          </div>
        </div>
       
<? } ?>