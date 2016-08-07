<?  $url=$_SERVER['REQUEST_URI'];?>
        <div class="navbar navbar-inverse">
    <div class="navbar-inner">
    <a class="brand" href="<?=site_url('admin/menu')?>">Administration</a>
    <ul class="nav nav-pills">
        <? foreach ($adminMenu as $menu){
            if (!$menu['disabled']){                  // Si le menu n'est pas désactivé
                if(strpos($url, $menu['link']))     // Si on se trouve sur la page du menu
                    $class = ' class="active"';
                else
                    $class ='';            
            echo '<li'.$class.'><a href="'.site_url($menu['link']).'">'.$menu['label'].'</a></li>';
            }
        }?>
    </ul>
    </div>
    </div>
    