<header>
<? $url=$_SERVER['REQUEST_URI']; 

if(isset($session['logged_in'])){
    if($session['logged_in']){
        // Initialisation du menu admin
        if($session['rank']>=90){
            $menuAdministrationBegin = '<li '.((strpos($url, 'admin') == true) ? ' class="active dropdown"' : 'class="dropdown"').'>
                <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#">Administration <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">';
            $menuAdministrationEnd = '</ul></li>';
}}}
?>
    
    
<div class="navbar<? //((strpos($url, 'admin') == true) ? ' navbar-inverse' : '')?>">
  <div class="navbar-inner">
     <div class="container">
               <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
        <a class="brand" href="<?=site_url('site/homepage')?>">USF Basket</a>
        <div class="nav-collapse collapse">
        <ul class="nav">
            <li <?=((strpos($url, '/match/') == true) ? ' class="active dropdown"' : 'class="dropdown"')?>>
            <a class="dropdown-toggle" id="dropdownMatchs" role="button" data-toggle="dropdown" data-target="#" href="#">Matchs <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMatchs">                   
                    <li<?=((strpos($url,'match/homematch_list' ) == true) ? ' class="active"' : '')?>><a href="<?=site_url('match/homematch_list')?>">Organisation des matchs</a></li>
                    <li class="<?=((isset($session['team_id'])) ? '' : 'disabled')?><?=((strpos($url,'match/mymatch_list' ) == true) ? ' active' : '')?>"><a href="<?=((isset($session['team_id'])) ? site_url('match/mymatch_list') : '')?>">Mes matchs</a></li>
                </ul>
            </li>
            <?foreach ($menus as $menu => $link){
                echo '<li'.((strpos($url, $link) == true) ? ' class="active"' : '').'><a href="'.site_url($link).'">'.$menu.'</a></li>';                
            }
            ?>
        </ul>
        <ul class="nav pull-right">
            <? if(isset($session['logged_in'])){
                if($session['logged_in'])
            {?>                
                <li<?= ((strpos($url, 'user/view'.$session['id']) == true) ? ' class="active"' : '')?>><a href="<?= site_url('user/view/'.$session['id']) ?>"><?=$session['firstname']?></a></li>
                <? if($session['rank']>=90) { // Si l'utilisateur est admin ou mieux'
                    echo $menuAdministrationBegin;  // Affichage des menus déroulants admin
                    foreach ($adminMenu as $menu){
                        if(!$menu['disabled']){ // Si le menu n'est pas désactivé on l'affiche
                            echo '
                                <li'.((strpos($url, $menu['link']) == true) ? ' class="active"' : '').'><a href="'. site_url($menu['link']).'">'.$menu['label'].'</a></li>';
                        }
                    }   
                    echo $menuAdministrationEnd;
                } ?>
                <li<?= ((strpos($url, 'user/disconnect') == true) ? ' class="active"' : '')?>><a href="<?= site_url('user/disconnect') ?>">Déconnexion</a></li>
            <? }} else { ?>
                <li<?= ((strpos($url, 'user/register') == true) ? ' class="active"' : '')?>><a href="<?= site_url('user/register') ?>">Inscription</a></li>
                <li<?= ((strpos($url, 'user/connect') == true) ? ' class="active"' : '')?>><a href="<?= site_url('user/connect') ?>">Connexion</a></li>
            <? } ?>
        </ul>
        </div>
     </div>
  </div>
</div>
    
    <!--<div class="alert alert-danger"><center>Version du site : <span class="label label-warning">OPEN BETA-TEST</span></center></div>-->
</header>