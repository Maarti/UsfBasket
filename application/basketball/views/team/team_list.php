<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Les équipes</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
                <th>Nom</th>
                <th>Label</th>
                <th>Catégorie</th>
                <th>Nb. membres</th>                
            </tr>
          </thead>
          <tbody>
            <?foreach ($teams as $team){?>
            <tr onclick="document.location = '<?=site_url('team/view/'.$team->team_id)?>';" style="cursor:pointer;">
                <td><?=htmlspecialchars($team->team_name)?></td>
                <td><span class="label label-info"><?=htmlspecialchars($team->team_label)?></span></td>
                <td><?=$team->category_name?></td>
                <td><?=$team->nb_user?></td>
            </tr>
            <?}?>
          </tbody>
        </table>
    </div>
    </div>
</div>