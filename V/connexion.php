<?php include 'lib/header.php'; ?>

<div class="container">
    <div class="row">
        <form method="post">
            <input type="text" class="hide" name="controller" value="Utilisateur">
            <input type="text" class="hide" name="f" value="connexion">
            <input type="text" placeholder="login" name="login">
            <input type="password" placeholder="mdp" name="mdp">

            <button>Valider</button>
        </form>
    </div>
</div>

<?php include "lib/footer.php"; ?>