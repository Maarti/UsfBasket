<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Les membres</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <table class="table table-condensed table-striped table-hover">
          <thead>
            <tr>
                <th>Prénom</th>
                <th>Équipe</th>
                <th>Score</th>
                <th>Dernière connexion</th>
                 <?foreach ($roles as $role){?>
                <th title="<?=$role->role_name?>" style="width:5%;"><?=$role->role_label?></th>
                <?}?>
            </tr>
          </thead>
          <tbody>
            <?foreach ($users as $user){?>
            <tr onclick="document.location = '<?=site_url('user/view/'.$user->user_id)?>';" style="cursor:pointer;">
                <td><?=htmlspecialchars($user->user_firstname).' '.substr(htmlspecialchars($user->user_lastname), 0, 1).'. '?></td>
                <td><a href="<?=site_url('team/view/'.$user->user_team)?>" class="label label-info" title="<?=$user->team_name?>"><?=$user->team_label?></a></td>
                <td><?=$user->user_score?></td>
                <td><?=$user->user_lastconnect?></td>
                <td title="<?=$roles[0]->role_name?>"><?if($user->user_isarbitre==1)echo'<i class="icon-ok"></i>'?></td>
                <td title="<?=$roles[1]->role_name?>"><?if($user->user_istable==1)echo'<i class="icon-ok"></i>'?></td>
                <td title="<?=$roles[2]->role_name?>"><?if($user->user_iscoach==1)echo'<i class="icon-ok"></i>'?></td>
                <td title="<?=$roles[3]->role_name?>"><?if($user->user_istrainer==1)echo'<i class="icon-ok"></i>'?></td>
                <td title="<?=$roles[4]->role_name?>"><?if($user->user_isplayer==1)echo'<i class="icon-ok"></i>'?></td>
            </tr>
            <?}?>
          </tbody>
        </table>
    </div>
    </div>
</div>