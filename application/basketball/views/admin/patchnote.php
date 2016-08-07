<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Patchnotes</h1>
       </div>
    </div>
    <? if(isset($_GET['insert'])){ 
            if($_GET['insert']=='success'){?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-success">Patchnote ajouté avec succès.</p>
            </div>
        </div>
    <?}}?>
    <div class="row-fluid">
        <div class="span7">
          
            
            <form class="form-horizontal" method="POST" action="<?=site_url('admin/patchnote_submit')?>">
            <fieldset>

            <!-- Form Name -->
            <legend>Créer une note de mise-à-jour</legend>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="patchnote_version_input">Version</label>
              <div class="controls">
                <input id="patchnote_version_input" name="patchnote_version_input" type="text" placeholder="1.0.0" class="input-small" required="" value="<?=set_value('patchnote_version_input')?>">
                <?=form_error('patchnote_version_input')?>
              </div>
            </div>

            <!-- Textarea -->
            <div class="control-group">
              <label class="control-label" for="patchnote_body_textarea">Description</label>
              <div class="controls">                     
                <textarea id="patchnote_body_textarea" name="patchnote_body_textarea" style="height: 100px;width: 300px;">&lt;ul&gt;
&lt;li&gt;&lt;/li&gt;
&lt;/ul&gt;<?=set_value('patchnote_body_textarea')?></textarea>
                  <?=form_error('patchnote_body_textarea')?>
              </div>
            </div>

            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="patchnote_submit"></label>
              <div class="controls">
                <button type="submit" id="patchnote_submit" name="patchnote_submit" class="btn btn-large btn-primary">Valider</button>
              </div>
            </div>

            </fieldset>
            </form>
            <p class="pull-right"><a href="<?=site_url('site/patchnote')?>">Voir les notes de mise-à-jour</a></p>

        </div>
        <div class="span5">
             <br/>
            <legend>Brouillon</legend>
            <textarea id="patchnote_draft_textarea" name="patchnote_draft_textarea" style="height: 180px;width: 400px;"><?if(isset($draft_patchnote[0]->patchnote_body))echo $draft_patchnote[0]->patchnote_body?></textarea>
        </div>
    </div>
</div>