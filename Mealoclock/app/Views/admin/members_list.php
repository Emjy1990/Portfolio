<?php $this->layout('layout', ['title' => 'Admin - Liste des utilisateurs']) ?>

<h1>Utilisateurs</h1>

<table class="table table-hover table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Créé le</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($usersList as $currentUser) : ?>
        <tr>
            <td><?= $currentUser->getId() ?></td>
            <td><?= $currentUser->getEmail() ?></td>
            <td><?= $currentUser->getRoleId() ?></td>
            <td><?= $currentUser->getDateInserted() ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
