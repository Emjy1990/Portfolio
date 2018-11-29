<?php $this->layout('layout', ['title' => 'Home']) ?>

<!-- Partie intermédiaire -->
<div class="container">
    <?php if ($connectedUser !== false) : ?>
        <h1>Bonjour <?= $connectedUser->getEmail() ?></h1>
    <?php else : ?>
        <h1>Bienvenue chez O'clock</h1>
    <?php endif; ?>
    
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>
<!-- Liste des communautés -->
<div class="container-fluid">
    <?php foreach ($listOfCommunities as $currentCommunity) : ?>
    <div class="row box">
        <div class="col-12 col-md-3">
            <img src="<?= $basePath ?>/<?= $currentCommunity->getImage() ?>" alt="<?= $this->e($currentCommunity->getName()) ?>" class="img-fluid">
        </div>
        <div class="col-12 col-md-6 box-content">
            <h2><?= $this->e($currentCommunity->getName()) ?></h2>
            <p><?= $this->e($currentCommunity->getDescription()) ?></p>
        </div>
        <div class="col-12 col-md-3 area">
            <h3>10 membres</h3>
            <p>
                créé par toto492@gmail.com
            </p>
            <div class="text-right">
                <a href="<?= $router->generate('community_community', ['id' => $currentCommunity->getId()]) ?>" class="btn btn-dark">Accéder</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
