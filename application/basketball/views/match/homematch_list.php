<div class="container">
    <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Les matchs à domicile</h1>
       </div>
    </div>
    <? if(isset($_GET['error'])){ 
            if($_GET['error']=='match_register'){?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-error">Inscription au match échouée.<br/>Vous êtes probablement déjà inscrit(e) à ce match, sinon veuillez <a href="http://bryan.maarti.net#contact" target="_blank">contacter l'administrateur</a>.<br/>Rappel : Vous ne pouvez pas vous réinscrire après avoir annulé une précédente inscription.</p>
            </div>
        </div><?}}?>
    <div class="row-fluid">
        <div class="span12">
            <p class="alert alert-info">Liste des matchs à venir.<br/>
            Cliquez sur un match pour avoir plus d'informations et vous y inscrire.<br/>
            Vert = Volontaires suffisants, Orange = Manque de volontaires, Rouge = Aucun volontaire !</p>
        </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        
        <table class="table table-bordered table-hover table-striped">
            <caption><?=$pagination?></caption>
  <thead>
    <tr>
      <th rowspan="2" style="vertical-align:middle; text-align: center;">Date</th>
      <th rowspan="2" style="vertical-align:middle; text-align: center;">Équipe</th>
      <th rowspan="2" style="vertical-align:middle; text-align: center;">Adversaire</th>
      <th rowspan="2" style="vertical-align:middle; text-align: center;">Heure</th>
      <th rowspan="2" style="vertical-align:middle; text-align: center;">Lieu</th>
      <th colspan="3" style="vertical-align:middle; text-align: center;">Volontaires Confirmés / Demandés</th>
    </tr>
    <tr><th style="vertical-align:middle; text-align: center;"><abbr title="Arbitres">A</abbr></th><th style="vertical-align:middle; text-align: center;"><abbr title="Marqueurs">M</abbr></th><th style="vertical-align:middle; text-align: center;"><abbr title="Chronométreurs">C</abbr></th>
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
            ?>
            <tr class="<?=$rowclass?>" onclick="document.location = '<?=site_url('match/view/'.$userformatch->match_id)?>';" style="cursor:pointer;">
              <td><?=$date?></td>
              <td><a href="<?=site_url('team/view/'.$userformatch->team_id)?>" class="label label-info" title="<?=$userformatch->team_name?>"><?=htmlspecialchars($userformatch->team_label)?></span></td>
              <td><?=htmlspecialchars($userformatch->match_oponent)?></td>
              <td style="text-align: center;"><?=$hour?></td>
              <td><abbr title="<?=$userformatch->place_address?>"><?=$userformatch->place_name?></abbr></td>
              <td style="text-align: center;"><abbr title="Arbitres confirmés/Arbitres demandés"><?=$ai?>/<?=$ar?></abbr></td>
              <td style="text-align: center;"><abbr title="Marqueurs confirmés/Marqueur demandé"><?=$mi?>/<?=$mr?></abbr></td>
              <td style="text-align: center;"><abbr title="Chronométreurs confirmés/Chronométreur demandé"><?=$ci?>/<?=$cr?></abbr></td>
            </tr>
    <? } ?>
    
  </tbody>
</table>
        
    </div>
    </div>
</div>