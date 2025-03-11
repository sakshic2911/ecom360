<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= DROPSHIPPING ?>/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo">
                <?= $this->Html->image("dropshipping/Ecom360logo.png", ["alt" => "", "height" => "50px"]) ?>
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Menu -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu</span>
        </li>
        <?php $menu = $_SESSION['menu'];
        $user = $_SESSION['user'];
        $menu_category = 0; 
        if($user->user_type==2)
        {
            $menu_category = $_SESSION['menu_category'];
        }
        $path = $this->request->getUri()->getPath();
        
        $folder = '';

        if($path == '/client' )
        $folder = 'Clients';

        if($path == '/library' )
        $folder = 'Library';

        if($path == '/support-library'  || $path == '/support-resources')
        $folder = 'Resources';

        if($path == '/archive-tickets' || $path == '/tickets' || $path == '/client-archive-tickets' || $path=='/tickets/client-ticket')
        $folder = 'Tickets';

        if($path == '/internal-staff')
        $folder = 'Internal Staff';

        if($path == '/roles')
        $folder = 'Roles';
        
        
        foreach($menu as $m) { 
            if(count($m->main[$m->folder]) == 1) {
                $murl = $m->main[$m->folder][0]->url; ?>
                <li class="menu-item <?= $folder == $m->folder  ? 'active' : 'inactive' ?>">
                    <a href="<?= DROPSHIPPING ?>/<?= $murl;?>" class="menu-link">
                        <i class="menu-icon tf-icons bx <?= $m->icon;?>"></i>
                        <div> <?php echo $m->main[$m->folder][0]->menu_name;  ?></div>
                    </a>
                </li>
            <?php 
            } 
        } ?>
            
            <!-- Accounts -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Account</span>
        </li>
        <?php if (($user->user_type == 2 || $user->user_type == 1) && $_SESSION['userType'] == 'admin') { ?>
        <li class="menu-item">
            <a href="<?= $this->Url->build(['controller' => 'Client', 'action' => 'backToAdmin']) ?>" class="menu-link">
                <i class="bx bx-left-arrow-alt me-2"></i>
                <div>
                    Go Back
                </div>
            </a>
        </li>
        <?php } ?>
        <li class="menu-item <?= $_SESSION['activeUrl'] == 'account'  ? 'active' : 'inactive' ?>">
            <a href="<?= DROPSHIPPING ?>/account-setting" class="menu-link">
                <i class="bx bx-cog me-2"></i>
                <div>
                    Settings
                </div>
            </a>
        </li>
        <li class="menu-item mt-1">
            <a class="menu-link" href="<?= DROPSHIPPING ?>/logout">
                <i class="bx bx-power-off me-2"></i>
                <div class="align-middle">Log Out</div>
            </a>
        </li>
    </ul>
</aside>

<!-- / Menu -->