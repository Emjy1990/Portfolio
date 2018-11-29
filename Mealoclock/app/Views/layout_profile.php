<?php $this->layout('layout', ['title' => $title]) ?>

<h1>Mon Compte</h1>

<?= $this->section('content') ?>

<?php $this->start('nav-user') ?>

<?php $this->insert('partials/nav-user') ?>

<?php $this->stop() ?>
