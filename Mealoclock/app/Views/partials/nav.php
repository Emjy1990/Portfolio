    <!-- Premier menu -->
    <ul class="menu flex-wrap flex-md-nowrap">
        <li class="burger p-3 menu-hover">
            <i class="fas fa-bars"></i>
        </li>
        <li class="search">
            <i class="fas fa-search"></i>
            <input type="text">
        </li>
        <li class="title">
            <a href="<?= $router->generate('main_home') ?>">
                <img src="<?= $basePath ?>/assets/images/title.svg" alt="Mealoclock">
            </a>
        </li>
        <li class="login p-3 menu-hover">
            <?php if ($connectedUser !== false) : ?>
                <a href="<?= $router->generate('user_profile') ?>">
                    <i class="fas fa-user"></i>
                    &nbsp;Mon compte
                </a>
            <?php else : ?>
                <a href="<?= $router->generate('user_login') ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    &nbsp;Connexion
                </a>
            <?php endif; ?>
        </li>
        <li class="inscription p-3 menu-hover">
            <?php if ($connectedUser !== false) : ?>
                <form action="<?= $router->generate('user_logout') ?>" method="post">
                    <button class="menu-hover">
                        <i class="fas fa-sign-out-alt"></i>
                        &nbsp;Déconnexion
                    </button>
                </form>
            <?php else : ?>
                <a href="<?= $router->generate('user_signup') ?>">
                    <i class="fas fa-edit"></i>
                    &nbsp;Inscription
                </a>
            <?php endif; ?>
        </li>
    </ul>
    
    <!-- Second menu -->
    <ul class="menu flex-wrap flex-md-nowrap">
        <li class="menu-hover">
            <a href="<?= $router->generate('community_community', ['id' => 12]) ?>">Communauté 12</a>
        </li>
        <li class="menu-hover">
            <a href="<?= $router->generate('event_list') ?>">&Eacute;v&egrave;nements</a>
        </li>
        <li class="menu-hover">
            <a href="<?= $router->generate('admin_membersList') ?>">Admin Membres</a>
        </li>
        <li class="menu-hover">Lorem</li>
        <li class="menu-hover"><i class="fab fa-twitter"></i></li>
        <li class="menu-hover"><i class="fab fa-facebook-f"></i></li>
    </ul>
