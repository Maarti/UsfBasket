<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Administration des équipes</h1>
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
            <table class="table table-hover table-striped">
  <thead>
    <tr>
      <th style="width: 30%;">Nom</th>
      <th style="width: 20%;">Label</th>
      <th style="width: 20%;">Catégorie</th>
      <th style="width: 10%; text-align:center;">Nb membres</th>
      <th style="width: 10%;"></th>
      <th style="width: 10%;"></th>
    </tr>
  </thead>
  <tbody>
    <?
    foreach ($teams as $team){
            $edit_modal_id='#team_edit_modal'.$team->team_id;
            $delete_modal_id='#team_delete_modal'.$team->team_id;
    ?>                    
            <tr>
              <td><?=$team->team_name?></td>
              <td><span class="label label-info"><?=$team->team_label?></span></td>
              <td><?=$team->category_name?></td>
              <td style="text-align:center;"><?=$team->nb_user?></td>
              <td><a href="<?=$edit_modal_id?>" role="button" class="btn btn-inverse pull-right" data-toggle="modal">Éditer</a></td>
              <td><a href="<?=$delete_modal_id?>" role="button" class="btn btn-danger" data-toggle="modal">Supprimer</a></td>
            </tr>
    <? } ?>
    <tr><td colspan="6"><a href="<?=site_url('admin/team_create')?>" class="btn btn-large btn-primary pull-right">Ajouter une équipe</a></td></tr>
  </tbody>
</table>
            
            

<?
foreach ($teams as $team){ // Pour chaque équipe, on créé un modal pour l'édition et la suppression
       $edit_modal_id='team_edit_modal'.$team->team_id;
       $delete_modal_id='team_delete_modal'.$team->team_id;
       
?>  
        <!-- Modal pour la suppression -->
        <div id="<?=$delete_modal_id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?=$delete_modal_id?>" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="<?=$delete_modal_id?>">Suppression d'équipe</h3>
          </div>
          <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer l'équipe <?=$team->team_name?> ?</p>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <a href="<?=site_url('admin/team_delete/'.$team->team_id)?>" class="btn btn-danger">Supprimer</a>
          </div>
        </div>
        
        <!-- Modal pour l'édition -->
        <div id="<?=$edit_modal_id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?=$edit_modal_id?>" aria-hidden="true">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="<?=$edit_modal_id?>">Éditer une équipe</h3>
          </div>
            
                <form class="form-horizontal" method="POST" action="<?=site_url('admin/team_edit/'.$team->team_id)?>">
                <fieldset>
          <div class="modal-body">
              
            <div class="span12">


                <!-- Form Name -->
                <legend>Modifier l'équipe <?=$team->team_name?></legend>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="team_name_input<?=$team->team_id?>">Nom</label>
                  <div class="controls">
                    <input id="team_name_input<?=$team->team_id?>" name="team_name_input<?=$team->team_id?>" type="text" placeholder="Première série masculine" class="input-xlarge" required="" value="<?=$team->team_name?>">
                    <?=form_error('team_name_input')?>
                  </div>
                </div>

                <!-- Text input-->
                <div class="control-group">
                    <label class="control-label" for="team_label_input<?=$team->team_id?>"><abbr title="Version raccourcie du nom (pour l'affichage)">Label</abbr></label>
                  <div class="controls">
                    <input id="team_label_input<?=$team->team_id?>" name="team_label_input<?=$team->team_id?>" type="text" placeholder="1ère série M" class="input-xlarge" required="" value="<?=$team->team_label?>">
                    <?=form_error('team_label_input')?>
                  </div>
                </div>

                            <!-- Select Basic -->
                <div class="control-group">
                  <label class="control-label" for="team_category_select<?=$team->team_id?>">Catégorie</label>
                  <div class="controls">
                    <select id="team_category_select<?=$team->team_id?>" name="team_category_select<?=$team->team_id?>" class="input-xlarge">
                        <? foreach ($categories as $category){ 
                            if($category->category_id!=0){?>
                            <option value="<?=$category->category_id?>" <?if($category->category_id==$team->team_category)echo 'selected'?>><?=$category->category_name?></option>
                            <? }} ?>
                    </select>
                  </div>
                </div>

            </div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-primary">Valider</button>
          </div>
        </fieldset>
        </form>
        </div>
<? } ?>
        </div>
  
    </div>
</div>