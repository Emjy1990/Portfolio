<?php $this->layout('layout', ['title' => 'Liste des évènements']) ?>

<h1>&Eacute;vènements</h1>

<!-- Liste des évènements -->
<div class="container-fluid">
    <?php foreach ($eventsList as $currentEvent) : ?>
    <div class="row box">
        <div class="col-12 col-md-9 box-content">
            <h2><a href="<?= $router->generate('event_showEvent', [
                    'idCommunity' => $currentEvent->getCommunityId(),
                    'slugCommunity' => $currentEvent->getCommunity()->getSlug(),
                    'idEvent' => $currentEvent->getId(),
                    'slugEvent' => $currentEvent->getSlug()
                ]) ?>"><?= $currentEvent->getTitle() ?></a></h2>
            <p><?= $currentEvent->getDescription() ?></p>
        </div>
        <div class="col-12 col-md-3 area">
            <h3><?= $currentEvent->getDateEvent() ?></h3>
            <p>
                <small>organisé par <?= $currentEvent->getHost()->getEmail() ?></small>
            </p>
            <p>
                <?= $currentEvent->getNbGuests() ?>
            </p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
