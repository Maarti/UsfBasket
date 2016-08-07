<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
 <? if(isset($_GET['error'])){ 
            if($_GET['error']=='sa'){?>
        <div class="row-fluid">
            <div class="span12">
                <p class="alert alert-error">Accès interdit.<br/>Seuls les super-administrateurs peuvent accéder à cette page.</p>
            </div>
        </div>
            <?}}?>
</div>