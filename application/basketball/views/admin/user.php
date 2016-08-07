<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Administration des membres</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <br/><br/>
        <table class="table table-condensed table-striped table-hover">
            <caption><strong>Comptes activés</strong></caption>
          <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>E-mail</th>
                <th>Tél.</th>
                <th>Date Naiss.</th>
                <th>Équipe</th>
                <th>Score</th>
                <th>Date d'inscription</th>
                <th>Dernière connexion</th>
                <th>Rang</th>
            </tr>
          </thead>
          <tbody>
            <?foreach ($activeUsers as $user){?>
            <tr onclick="document.location = '<?=site_url('user/view/'.$user->user_id)?>';" style="cursor:pointer;">
                <td><?=htmlspecialchars($user->user_lastname)?></td>
                <td><?=htmlspecialchars($user->user_firstname)?></td>
                <td><?=htmlspecialchars($user->user_mail)?></td>
                <td><?=htmlspecialchars($user->user_cellphone)?></td>
                <td><?=htmlspecialchars($user->user_birthdate)?></td>
                <td><a href="<?=site_url('team/view/'.$user->user_team)?>" class="label label-info" title="<?=$user->team_name?>"><?=$user->team_label?></a></td>
                <td><?=$user->user_score?></td>
                <td><?=$user->user_registerdate?></td>
                <td><?=$user->user_lastconnect?></td>
                <td><?=$user->rank_label?></td>
            </tr>
            <?}?>
          </tbody>
        </table>
    </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <br/><br/>
        <table class="table table-condensed table-striped table-hover">
            <caption><strong>Comptes non-activés</strong></caption>
            <?if($inactiveUsers){?>
          <thead>
            <tr>
                <th>Noms</th>
                <th>E-mail</th>
                <th>Tél.</th>
                <th>Date Naiss.</th>
                <th>Équipe</th>
                <th>Date d'inscription</th>
                <th>Rang</th>
                <th></th>
            </tr>
          </thead>
          <tbody>
            <?foreach ($inactiveUsers as $user){?>
            <tr>
                <td><?=htmlspecialchars($user->user_lastname)?> <?=htmlspecialchars($user->user_firstname)?></td>
                <td><?=htmlspecialchars($user->user_mail)?></td>
                <td><?=htmlspecialchars($user->user_cellphone)?></td>
                <td><?=htmlspecialchars($user->user_birthdate)?></td>
                <td><a href="<?=site_url('team/view/'.$user->user_team)?>" class="label label-info" title="<?=$user->team_name?>"><?=$user->team_label?></a></td>
                <td><?=$user->user_registerdate?></td>
                <td><?=$user->rank_label?></td>
                <td><a class="btn btn-danger" href="<?=site_url('admin/activateUser/'.$user->user_activating_code)?>" title="Activer manuellement le compte">Activer</a></td>
            </tr>
            <?}?>
          </tbody>
            <?}else{echo '<tr><td style="text-align:center;"><i>Tous les comptes sont activés.</i></td></td>';}?>
        </table>
    </div>
    </div>
    <?if($inactiveUsers){?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-info">Activation de compte :<br/>Vous pouvez activer manuellement le compte d'un membre si celui-ci ne l'a pas fait en cliquant sur le lien qu'il a reçu par e-mail. Cependant, vous n'aurez pas la certitude que son adresse e-mail est correcte.</p>
            </div>
        </div>
    <?}?>
</div>