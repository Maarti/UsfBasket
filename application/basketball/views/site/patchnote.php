<div class="container"> <? $this->load->view('site/dailyMessage-inc');?>
    <div class="row-fluid">
       <div class="span12">
           <h1>Notes de mise-à-jour</h1>
       </div>
    </div>
    <div class="row-fluid">
        <div class="span12"><br/>
            <?foreach($patchnotes as $patchnote) {?>
            <blockquote>
                <p><?=$patchnote->patchnote_body?></p>
                <small>Mise-à-jour <?=$patchnote->patchnote_version?> <cite>le <?=$patchnote->patchnote_formated_date?></cite></small>
            </blockquote><br/>
            <?}?>
        </div>
    </div>
</div>
