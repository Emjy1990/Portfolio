<?php $this->layout('layout', ['title' => 'connexion']) ?>

<div class="col-md-6 offset-md-3 my-3">
    <h1>Connexion</h1>

    <div class="alert" role="alert" id="alertSignin" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="content">
            
        </div>
    </div>

    <form action="<?= $router->generate('user_ajaxlogin') ?>" method="post" id="formSignin">
      <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Mot de passe</label>
        <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<?php $this->push('js') ?>
<script src="<?= $basePath ?>/assets/js/app.js"></script>
<?php $this->end(); ?>
