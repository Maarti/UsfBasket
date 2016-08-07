<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1><?=$this_user[0]->user_firstname?> <?=substr($this_user[0]->user_lastname,0,1)?>.</h1>
       </div>
    </div>
    <? if(isset($_GET['edit'])){ ?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-success">Édition réussie.<br/>Les informations de votre compte ont été mises à jour avec succès, reconnectez-vous pour qu'elles prennent effet sur votre session.</p>
            </div>
        </div><?}?>
    <div class="row-fluid">
    <div class="span12">
        
        <br/>
   
        
     <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#infos">Infos</a></li>
    <li><a href="#score">Score</a></li>
    <li><a href="#messages">Messages</a></li>
    <? if($my_profile){?>
        <li><a href="#edit">&Eacute;diter mon profile</a></li>
    <?}?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="infos">
            <table>
                <tr><td>Équipe : </td><td><?=$this_user[0]->team_name?></td></tr>
                <tr><td>Score : </td><td><?=$this_user[0]->user_score?></td></tr>
                <tr><td>Membre sur le site depuis : </td><td><?=$this_user[0]->user_registerdate?></td></tr>
                <tr><td>Dernière connexion le : </td><td><?=$this_user[0]->user_lastconnect?></td></tr>
            </table>
        </div>

        <div class="tab-pane" id="score">

        </div>

        <div class="tab-pane" id="messages">
            
        </div>
        
         <? if($my_profile){?>
        <div class="tab-pane" id="edit">
            <? include('inc-edit_profile.php'); ?>
        </div>
         <?}?>
    
    </div>
     

        
      
          
        
        
    </div>
    </div>
</div>





    <script type="text/javascript">    
        $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        })
        
        $(document).ready
        (   function()
            {
                 // Fonction pour afficher le champ date
                $('#datepicker').datepicker();
            }
        );
    </script>