<?php 
include 'lib/header.php';
include 'V/lib/navConnect.php';
?>

<div class="container">
    <div class="row">
        <h2>Structure de la table
            <?= $paramGet["table"] ?>
        </h2>
        <table>
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>Champ null</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($structure as $s) {
                    ?>
                <tr>
                    <td>
                        <?= $s->Field ?>
                    </td>
                    <td>
                        <?= $s->Type ?>
                    </td>
                    <td>
                        <?= $s->Key ?>
                    </td>
                    <td>
                        <?= $s->Default ?>
                    </td>
                    <td>
                        <?= $s->Extra ?>
                    </td>
                </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
    </div>
</div>