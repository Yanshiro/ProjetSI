<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo right">Espace de connexion
            <?= $_SESSION['Auth']->login ?> </a>
        <ul id="nav-mobile" class="left hide-on-med-and-down">
            <?php 
            foreach ($tables as $table) { ?>
            <li><a href="#">
                    <?= ucfirst($table->table) ?></a></li>
            <?php

        } ?>
            <li><a href="index.php?controller=Utilisateur&f=deconnexion">Deconnexion</a></li>
        </ul>
    </div>
</nav>