<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1><?=$this_team[0]->team_name?></h1>
           <h4>Catégorie : <?=$this_team[0]->category_name?></h4>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <br/>
        <table class="table table-condensed table-striped table-hover">
          <thead>
            <tr>
                <th>Membres</th>
            </tr>
          </thead>
          <tbody>
            <?foreach ($this_members as $member){?>
            <tr onclick="document.location = '<?=site_url('user/view/'.$member->user_id)?>';" style="cursor:pointer;">
                <td><?=htmlspecialchars($member->user_firstname).' '.substr(htmlspecialchars($member->user_lastname), 0, 1).'. '?></td>
            </tr>
            <?}?>
          </tbody>
        </table>
    </div>
    </div>
</div>