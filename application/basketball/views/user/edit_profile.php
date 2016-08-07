<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Éditer votre compte</h1>
       </div>
    </div>
    <div class="row-fluid">
    <div class="span12">
        <? include('inc-edit_profile.php');?>
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