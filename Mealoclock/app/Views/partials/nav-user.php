<ul class="menu flex-wrap flex-md-nowrap">
    <li class="menu-hover">
        <a href="#">Bonjour <?= $connectedUser->getEmail() ?></a>
    </li>
    <li class="menu-hover">
        <a href="<?= $router->generate('user_communities') ?>">Mes communautés</a>
    </li>
    <li class="menu-hover">
        <a href="#">Notifications</a>
    </li>
</ul>
