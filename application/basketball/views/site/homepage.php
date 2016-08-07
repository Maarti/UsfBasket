<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
               <div class="span12 hero-unit" id="myherounit">
                   <div class="row-fluid">
                       <div class="span12" id="homepage-title">
                            <h1>USF Basket</h1>
                            <p>Site officiel du club de l'USF Basket</p>
                            <?if(isset($session['logged_in'])){?>
                                <p><a class="btn btn-primary btn-large" href="<?=site_url('match/homematch_list')?>">Voir les prochains matchs !</a></p>
                                <?if($session['rank']>=90){?>
                                    <p><a class="btn btn-inverse btn-large" href="<?=site_url('admin/menu')?>">Accéder à l'administration</a></p>
                                <?}?>
                            <?}else{?>
                                <p><a class="btn btn-primary btn-large" href="<?=site_url('user/register')?>">Inscrivez-vous !</a></p>
                            <?}?>
                       </div>
                   </div>
               </div>
            </div><br/>
            <? if(displayCurrentDailyMessage(TRUE)){
                $msg = displayCurrentDailyMessage(TRUE);
                echo '<div class="row-fluid">'.$msg['body'].'</div>';
            }?>
            <div class="row-fluid">                
                <div class="span6">
                    <h2>Prochain match :</h2>
                    <p>
                    <?if($next_match){ //S'il y a un prochain match?>
                        <b><a href="<?=site_url('team/view/'.$next_match['team_id'])?>"><?=htmlspecialchars($next_match['team_name'])?></a></b> contre <b><?=htmlspecialchars($next_match['match_oponent'])?></b><br/>
                        le <b><?=$next_match['match_datefr']?></b> à <b><?=$next_match['match_hour']?></b><br/>
                        au <b><?=htmlspecialchars($next_match['place_name'])?></b><br/>
                        <a class="btn btn-small btn-primary" href="<?=site_url('match/view/'.$next_match['match_id'])?>">Y participer !</a>
                    <?}else{?>
                        Pas de match prévu
                    <?}?>
                    </p>
                </div>
                
                <div class="span6">
                    <h2>Dernière mise-à-jour :</h2>
                    <?foreach($patchnotes as $patchnote) {?>
                    <blockquote>
                        <p><?=$patchnote->patchnote_body?></p>
                        <small>Mise-à-jour <?=$patchnote->patchnote_version?> <cite>le <?=$patchnote->patchnote_formated_date?></cite></small>
                    </blockquote>
                    <?}?>
                    <p><a class="pull-right" href="<?=site_url('site/patchnote')?>">Voir les notes de mise-à-jour</a></p>
                </div>
            </div>
    </div>
</div>
</div>