<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once './utils.php';

if (isset($_POST['restart'])) {
    // Lors du restart, on vide le battlelog, et on réinitialise les variables
    unset($_SESSION['battleLog']);
    $_SESSION['player']['health'] = $_SESSION['player']['max-health'];
    $_SESSION['player']['mana'] = $_SESSION['player']['max-mana'];

    $_SESSION['opponent']['health'] = $_SESSION['opponent']['max-health'];
    $_SESSION['opponent']['mana'] = $_SESSION['opponent']['max-mana'];

    $_SESSION['isStarted'] = false;

    header('Location: ./'); // Parce que j'en ai marre de renvoyer du POST inutilement à chaque refresh
}
$isStarted = $_SESSION['isStarted'] ?? false;
$player = $_SESSION['player'] ?? [];
$opponent = $_SESSION['opponent'] ?? [];
$battleLog = $_SESSION['battleLog'] ?? [];

$winner = null;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['player'], $_POST['adversaire'])) {
        // Lorsqu'on poste les infos pour définir les combattants, on récupère les infos et initialise le combat
        $player = $_POST['player'];
        $opponent = $_POST['adversaire'];

        $allowStart = areInputsValid($player, $opponent);

        if ($allowStart) {
            $player['max-health'] = $player['health'];
            $player['max-mana'] = $player['mana'];
            $player['healing-ratio'] = random_int(10, 30);
            $opponent['max-health'] = $opponent['health'];
            $opponent['max-mana'] = $opponent['mana'];
            $opponent['healing-ratio'] = random_int(10, 30);

            $isStarted = true;
        } else {
            $errors = getInputErrors($player, $opponent);
        }
    } elseif (
        isset($_POST['attack'])
    ) {
        // Lors d'une attack, si le combat est toujours en cours, on baisse les vies des combattants puis on check si KO

        $opponent['health'] = max(0, $opponent['health'] - $player['attack']);
        $player['health'] = max(0, $player['health'] - $opponent['attack']);

        $battleLog[] = $player['name'] . " attack ! " . $opponent['name'] . " perd " . $player['attack'] . " points de vie !";
        $battleLog[] = $opponent['name'] . " riposte ! " . $player['name'] . " perd " . $opponent['attack'] . " points de vie !";

        if ($opponent['health'] <= 0 && $player['health'] > 0) {
            // Player wins
            $batteLog[] = $opponent['name'] . " défaillit ! " . $player['name'] . " a remporté le combat !";
            $winner = 1;
        } elseif ($opponent['health'] > 0 && $player['health'] <= 0) {
            // AI wins
            $batteLog[] = $player['name'] . " défaillit ! " . $opponent['name'] . " a remporté le combat !";
            $winner = 2;
        } elseif ($opponent['health'] <= 0 && $player['health'] <= 0) {
            // Draw
            $batteLog[] = "Les deux combattantes tombent 😧 ! Égalité !";
            $winner = 3;
        }
    } elseif (isset($_POST['soin'])) {
        // Lors d'un soin, si le combat est toujours en cours, on échange de la mana pour du soin
        if ($player['health'] < $player['max-health']) {
            $amountHealed = heal($player);
            var_dump($amountHealed);

            $battleLog[] = $player['name'] . ($amountHealed ? " se soigne $amountHealed points de vie !" : " n'a plus de mana et n'a pas pu se soigner !");
        }
        if ($opponent['health'] < $opponent['max-health']) {
            $amountHealed = heal($opponent);
            var_dump($amountHealed);

            $battleLog[] = $opponent['name'] . ($amountHealed ? " en profite pour bander ses plaies et récupère $amountHealed points de vie !" : " n'a plus de mana et n'a pas pu se soigner !");
        }
    }

    $_SESSION['player'] = $player;
    $_SESSION['opponent'] = $opponent;
    $_SESSION['battleLog'] = $battleLog;
    $_SESSION['isStarted'] = $isStarted;
}

if ($winner) {
    switch ($winner) {
        case 1:
            $result = $player['name'] . " est le vainqueur !";
            break;
        case 2:
            $result = $opponent['name'] . " est le vainqueur !";
            break;
        case 3:
            $result = "Les deux adversaires sont KO, égalité !";
            break;
    }
}
?>

<html lang="fr">

<head>
    <title>Battle</title>
    <link rel="stylesheet" href="public/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</head>

<body>
    <div class="container">
        <audio id="fight-song" src="fight.mp3"></audio>
        <audio id="hadoudken-song" src="Haduken.mp3"></audio>
        <audio id="fatality-song" src="fatality.mp3"></audio>
        <h1 class="animate__animated animate__rubberBand">Battle</h1>
        <?php if (!$isStarted) {
        ?>
            <div id="prematch">
                <?php if (isset($errors)) {
                ?>
                    <ul>
                        <?php
                        foreach ($errors['ErrorsList'] as $error) {
                        ?>
                            <li class="text-danger"><?php echo $error; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                }
                ?>
                <form id='formFight' action=" index.php" method="post" class="needs-validation" novalidate>
                    <div>
                        Joueur <br>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control<?php if (isset($errors['player-name-empty'])) echo ' is-invalid'; ?>" name="player[name]" value="<?php echo $player['name'] ?? null; ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Attaque</label>
                                <input required type="number" class="form-control<?php if (isset($errors['player-attack-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $player['attack'] ?? 25; ?>" name="player[attack]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number" class="form-control<?php
                                                                                    if (isset($errors['player-mana-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $player['mana'] ?? 100; ?>" name="player[mana]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number" class="form-control<?php
                                                                                    if (isset($errors['player-health-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $player['health'] ?? 150; ?>" name="player[health]">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        Adversaire <br>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control<?php
                                                                                if (isset($errors['opponent-name-empty'])) echo ' is-invalid'; ?>" name="adversaire[name]" value="<?php echo $opponent['name'] ?? null; ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">attack</label>
                                <input required type="number" class="form-control<?php if (isset($errors['opponent-attack-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $opponent['attack'] ?? 20; ?>" name="adversaire[attack]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number" class="form-control<?php if (isset($errors['opponent-mana-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $opponent['mana'] ?? 100; ?>" name="adversaire[mana]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number" class="form-control<?php
                                                                                    if (isset($errors['opponent-health-invalid'])) echo ' is-invalid'; ?>" value="<?php echo $opponent['health'] ?? 200; ?>" name="adversaire[health]">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="d-flex justify-content-center">
                            <input id="fight" type="submit" value="FIGHT">
                        </div>
                    </div>
                </form>
            </div>
        <?php } else {
        ?>
            <div id="match" class="row gx-5">
                <h2>Match</h2>
                <div class="col-6 ">
                    <div class="position-relative float-end">
                        <img id="player" src="https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=<?php echo $player['name'] ?? 'john'; ?>" alt="Avatar" class="avatar float-end mt-2">

                        <span style="width:200%;" class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger">
                             
                        </span>

                        <span style="width: <?php echo 200 * $player['health'] / $player['max-health']; ?>%;" class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-danger">
                            <?php echo $player['health'] . " / " . $player['max-health']; ?>
                        </span>
                        <ul>
                            <li>Name : <?php echo $player['name']; ?></li>
                            <li>attack : <?php echo $player['attack']; ?></li>
                            <li>Mana : <?php echo $player['mana']; ?></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6" id="adversaire">
                    <div class="position-relative float-start">
                        <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=<?php echo $opponent['name'] ?? 'jane'; ?>" alt="Avatar" class="avatar mt-2">

                        <span style="width: 200%;" class="position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger">
                             
                        </span>

                        <span style="width: <?php echo 200 * $opponent['health'] / $opponent['max-health']; ?>%;" class="position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-danger">
                            <?php echo $opponent['health'] . " / " . $opponent['max-health']; ?>
                        </span>
                        <ul>
                            <li>Name : <?php echo $opponent['name']; ?></li>
                            <li>attack : <?php echo $opponent['attack']; ?></li>
                            <li>Mana : <?php echo $opponent['mana']; ?></li>
                        </ul>
                    </div>
                </div>
                <?php if (isset($result)) {
                ?>
                    <div id="Resultats">
                        <h1>Résultat</h1>
                        <?php echo $result; ?>
                        <form class="d-flex justify-content-center" action="" method="post">
                            <input name="restart" type="submit" value="Nouveau combat">
                        </form>
                    </div>
                <?php
                } else {
                ?>
                    <div id="combats">
                        <h2>Combat</h2>
                        <form id='actionForm' action="index.php" method="post">
                            <div class="d-flex justify-content-center">
                                <input id="attack" name="attack" type="submit" value="Attaquer">
                                <input name="soin" type="submit" value="Se soigner">
                            </div>
                            <div class="d-flex justify-content-center">
                                <input id="restart" name="restart" type="submit" value="Stopper le combat">
                            </div>
                        </form>
                        <ul class="battleLog">
                            <?php foreach ($battleLog as $line) {
                                echo "<li>$line</li>";
                            }; ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        } ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let submitFight = document.querySelector("#fight");
            if (submitFight) {
                submitFight.addEventListener("click", function(event) {
                    event.preventDefault();
                    submitFight.classList.add("animate__animated");
                    submitFight.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitFight.classList.remove("animate__rubberBand");
                    }, 1000);
                    let fight_song = document.getElementById("fight-song");
                    fight_song.play();
                    setTimeout(function() {
                        document.forms["formFight"].submit();
                    }, 500);
                })
            }

            let submitattack = document.querySelector("#attack");
            let alreadyPlaySong = false;
            if (submitattack) {
                submitattack.addEventListener("click", function(event) {
                    if (alreadyPlaySong)
                        return true;
                    event.preventDefault();
                    let player = document.querySelector("#player")
                    player.classList.add("animate__animated");
                    player.classList.add("animate__rubberBand");
                    submitattack.classList.add("animate__animated");
                    submitattack.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitattack.classList.remove("animate__rubberBand");
                        player.classList.remove("animate__rubberBand");
                    }, 1000);
                    let hadouken_song = document.getElementById("hadoudken-song");
                    hadouken_song.play();
                    alreadyPlaySong = true;
                    setTimeout(function() {
                        submitattack.click();
                    }, 1000);
                })
            }

            let submitRestart = document.querySelector("#restart");
            let alreadyPlaySongRestart = false;
            if (submitRestart) {
                submitRestart.addEventListener("click", function(event) {
                    if (alreadyPlaySongRestart)
                        return true;
                    event.preventDefault();
                    let fatality_song = document.getElementById("fatality-song");
                    fatality_song.play();
                    alreadyPlaySongRestart = true;
                    setTimeout(function() {
                        submitRestart.click();
                    }, 2000);
                })
            }
        });
    </script>
</body>
<style>
    .avatar {
        vertical-align: middle;
        width: 100px;
        border-radius: 50%;
    }
</style>

</html>