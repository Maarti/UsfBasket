<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Message du jour</h1>
       </div>
    </div>
    <? if(isset($_GET['insert'])){ 
            if($_GET['insert']=='success'){?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-success">Message créé avec succès.</p>
            </div>
        </div>
    <?}}?>
    <div class="row-fluid">
        <div class="span12">
          
<form class="form-horizontal" method="POST" action="<?=site_url('admin/dailymsg_submit')?>">
<fieldset>

<!-- Form Name -->
<legend>Changer le message du jour</legend>

<!-- Date input -->
<div class="control-group">
    <label class="control-label" for="dailymsg_end_input">Afficher jusqu'au</label>
    <div class="controls">
    <div class="input-append date" id="datepicker" data-date="" data-date-format="dd/mm/yyyy" >
      <input id="dailymsg_end_input" name="dailymsg_end_input" type="text" placeholder="05/02/2015" class="input-medium" value="<?=set_value('dailymsg_end_input')?>" ><span class="add-on"><i class="icon-th"></i></span>
    </div>
    <?=form_error('dailymsg_end_input')?></div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="dailymsg_body">Message</label>
  <div class="controls">
    <input id="dailymsg_body" name="dailymsg_body" placeholder="Match du 01/02/15 annulé." class="input-xxlarge" required="" type="text" value="<?=set_value('dailymsg_body')?>">
    <?=form_error('dailymsg_body')?>
    
  </div>
</div>

<!-- Multiple Radios -->
<div class="control-group">
  <label class="control-label" for="dailymsg_type">Type</label>
  <div class="controls">
    <label class="radio" for="dailymsg_type-0">
      <input name="dailymsg_type" id="dailymsg_type-0" value="0" checked="checked" required="required" type="radio">
      Informatif
    </label>
    <label class="radio" for="dailymsg_type-1">
      <input name="dailymsg_type" id="dailymsg_type-1" value="1" required="required" type="radio">
      Urgent
    </label>
      <?=form_error('dailymsg_type')?>
  </div>
</div>

<!-- Button (Double) -->
<div class="control-group">
  <label class="control-label" for="dailymsg_submit"></label>
  <div class="controls">
    <button id="dailymsg_submit" name="dailymsg_submit" class="btn btn-primary">Valider</button>
    <button id="dailymsg_cancel" name="dailymsg_cancel" class="btn btn-danger">Annuler</button>
  </div>
</div>

</fieldset>
</form>

            <ul class="alert alert-info">
                <li>Un <strong>message informatif</strong> sera affiché uniquement sur la page d'accueil</li>
                <li>Un <strong>message urgent</strong> sera affiché en haut de chaque page du site</li>
            </ul>

        </div>
    
    </div>
</div>

<script type="text/javascript"> 
$(document).ready
(   function()
    {          
        // Fonction pour afficher le champ date
        $('#datepicker').datepicker();
    }
);
</script>