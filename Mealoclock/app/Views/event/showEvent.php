<?php $this->layout('layout', ['title' => 'Evènement #'.$eventModel->getId()]) ?>
<!-- Partie intermédiaire -->
<div class="container">
    <h1>&Eacute;vènement #<?= $eventModel->getId() ?></h1>
    <p><?= $eventModel->getDescription() ?></p>
    <p>
        <?= $eventModel->getAddress() ?><br>
        <?= $eventModel->getZipcode() ?> <?= $eventModel->getCity() ?><br>
    </p>
        
    <!-- Div qui va contenir la map GoogleMaps -->
    <div id="map" class="map"></div>
</div>

<?php $this->push('js') ?>
<script>
  var eventAddress = "<?= $eventModel->getAddress() ?>, <?= $eventModel->getZipcode() ?> <?= $eventModel->getCity() ?>";
</script>
<script src="<?= $basePath ?>/assets/js/app.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXyv9VfmYc7DfpoC-vxXgtSo5sYt2LnuA&callback=app.initMap"
async defer></script>
<?php $this->end() ?>
