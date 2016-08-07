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
          
            <form class="form-horizontal" method="POST" action="<?=site_url('admin/team_create_submit')?>">
            <fieldset>

            <!-- Form Name -->
            <legend>Ajouter une équipe</legend>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="team_name_input">Nom</label>
              <div class="controls">
                <input id="team_name_input" name="team_name_input" type="text" placeholder="Première série masculine" class="input-xlarge" required="" value="<?=set_value('team_name_input')?>">
                <?=form_error('team_name_input')?>
              </div>
            </div>

            <!-- Text input-->
            <div class="control-group">
                <label class="control-label" for="team_label_input"><abbr title="Version raccourcie du nom (pour l'affichage)">Label</abbr></label>
              <div class="controls">
                <input id="team_label_input" name="team_label_input" type="text" placeholder="1ère série M" class="input-xlarge" required="" value="<?=set_value('team_label_input')?>">
                <?=form_error('team_label_input')?>
              </div>
            </div>

                        <!-- Select Basic -->
            <div class="control-group">
              <label class="control-label" for="team_category_select">Catégorie</label>
              <div class="controls">
                <select id="team_category_select" name="team_category_select" class="input-xlarge">
                    <? foreach ($categories as $category){ 
                        if($category->category_id!=0){?>
                        <option value="<?=$category->category_id?>"><?=$category->category_name?></option>
                        <? }} ?>
                </select>
              </div>
            </div>

            <!-- Button (Double) -->
            <div class="control-group">
              <div class="controls">
                <button type="submit" id="team_submit_button" name="team_submit_button" class="btn btn-large btn-primary">Créer</button>
                <a href="<?=site_url('admin/team')?>" id="team_cancel_button" name="team_cancel_button" class="btn btn-large btn-danger">Annuler</a>
              </div>
            </div>
            </fieldset>
            </form>

            
        </div>
    </div>
</div>