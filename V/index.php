<?php 
include 'lib/header.php';
include 'V/lib/navConnect.php';
?>

<div class="container">
    <div class="row">
        <h2>Tables du logiciel</h2>

        <table>
            <thead>
                <tr>
                    <th>Nom de la table</th>
                    <th>Table system</th>
                    <th>Action sur la table</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION["tables"] as $table) {
                    ?>
                <tr>
                    <td>
                        <?= $table->table ?>
                    </td>
                    <td>
                        <?= $table->tableSysteme == 1 ? 'oui' : 'non' ?>
                    </td>
                    <td>
                        <a href="index.php?controller=table&f=afficheStructure&table=<?= $table->table ?>" class="btn green">voir
                            la structure</a>
                        <a href="index.php?controller=table&f=addData&table=<?= $table->table ?>" class="btn blue">ajouter
                            données</a>
                    </td>
                </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "lib/footer.php"; ?>