<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$player = $_SESSION['player'] ?? [];
$opponent = $_SESSION['opponent'] ?? [];
$battleLog = $_SESSION['battleLog'] ?? [];

$winner = null;

function heal(&$john, $amount = 20)
{
    if ($john['mana'] >= $amount) {
        $john['mana'] -= $amount;
        $john['sante'] = min($john['max-health'], $john['sante'] + $john['max-health'] * $amount / 100);
        return true;
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['player'], $_POST['adversaire'])) {
        $player = $_POST['player'];
        $opponent = $_POST['adversaire'];

        $player['max-health'] = $player['sante'];
        $opponent['max-health'] = $opponent['sante'];
        $player['max-mana'] = $player['mana'];
        $opponent['max-mana'] = $opponent['mana'];

        $_SESSION['isEnded'] = false;
        $winner = null;
        unset($battleLog);
        $battleLog = [];
    } elseif (
        isset($_POST['attaque']) && !$_SESSION['isEnded']
    ) {
        $opponent['sante'] = max(0, $opponent['sante'] - $player['attaque']);
        $player['sante'] = max(0, $player['sante'] - $opponent['attaque']);

        $battleLog[] = $player['name'] . " attaque ! " . $opponent['name'] . " perd " . $player['attaque'] . " points de vie !";
        $battleLog[] = $opponent['name'] . " riposte ! " . $player['name'] . " perd " . $opponent['attaque'] . " points de vie !";

        if ($opponent['sante'] <= 0 && $player['sante'] > 0) {
            // Player wins
            $batteLog[] = $opponent['name'] . " défaillit ! " . $player['name'] . " a remporté le combat !";
            $winner = 1;
        } elseif ($opponent['sante'] > 0 && $player['sante'] <= 0) {
            // AI wins
            $batteLog[] = $player['name'] . " défaillit ! " . $opponent['name'] . " a remporté le combat !";
            $winner = 2;
        } elseif ($opponent['sante'] <= 0 && $player['sante'] <= 0) {
            // Draw
            $batteLog[] = "Les deux combattantes tombent 😧 ! Égalité !";
            $winner = 3;
        }
    } elseif (isset($_POST['soin']) && !$_SESSION['isEnded']) {
        heal($player, 20);
        heal($opponent, 15);
        $battleLog[] = $player['name'] . " se soigne ! ";
        $battleLog[] = $opponent['name'] . " en profite pour bander ses plaies également !";
    } elseif (isset($_POST['restart'])) {
        unset($battleLog);
        $battleLog = [];
        $player['sante'] = $player['max-health'];
        $player['mana'] = $player['max-mana'];
        $opponent['sante'] = $opponent['max-health'];
        $opponent['mana'] = $opponent['max-mana'];

        $winner = null;
        $_SESSION['isEnded'] = false;
    }

    $_SESSION['player'] = $player;
    $_SESSION['opponent'] = $opponent;
    $_SESSION['battleLog'] = $battleLog;
}

if ($winner) {
    $_SESSION['isEnded'] = true;
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
        <div id="prematch">
            <form id='formFight' action="index.php" method="post">
                <div>
                    Joueur <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="player[name]" value="<?php echo $player['name'] ?? null; ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" value="25" name="player[attaque]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" value="100" name="player[mana]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" value="150" name="player[sante]">
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    Adversaire <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="adversaire[name]" value="<?php echo $opponent['name'] ?? null; ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" value="20" name="adversaire[attaque]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" value="100" name="adversaire[mana]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" value="200" name="adversaire[sante]">
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
        <div id="match" class="row gx-5">
            <h2>Match</h2>
            <div class="col-6 ">
                <div class="position-relative float-end">
                    <img id="player" src="https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=<?php echo $player['name']; ?>" alt="Avatar" class="avatar float-end mt-2">

                    <span style="width:200%;" class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger">
                         
                    </span>

                    <span style="width: <?php echo 200 * $player['sante'] / $player['max-health']; ?>%;" class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-danger">
                        <?php echo $player['sante'] . " / " . $player['max-health']; ?>
                    </span>
                    <ul>
                        <li>Name : <?php echo $player['name']; ?></li>
                        <li>Attaque : <?php echo $player['attaque']; ?></li>
                        <li>Mana : <?php echo $player['mana']; ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-6" id="adversaire">
                <div class="position-relative float-start">
                    <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=<?php echo $opponent['name']; ?>" alt="Avatar" class="avatar mt-2">

                    <span style="width: 200%;" class="position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger">
                         
                    </span>

                    <span style="width: <?php echo 200 * $opponent['sante'] / $opponent['max-health']; ?>%;" class="position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-danger">
                        <?php echo $opponent['sante'] . " / " . $opponent['max-health']; ?>
                    </span>
                    <ul>
                        <li>Name : <?php echo $opponent['name']; ?></li>
                        <li>Attaque : <?php echo $opponent['attaque']; ?></li>
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
            }
            ?>
            <div id="combats">
                <h2>Combat</h2>
                <form id='actionForm' action="index.php" method="post">
                    <div class="d-flex justify-content-center">
                        <input id="attaque" name="attaque" type="submit" value="Attaquer">
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
        </div>
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

            let submitAttaque = document.querySelector("#attaque");
            let alreadyPlaySong = false;
            if (submitAttaque) {
                submitAttaque.addEventListener("click", function(event) {
                    if (alreadyPlaySong)
                        return true;
                    event.preventDefault();
                    let player = document.querySelector("#player")
                    player.classList.add("animate__animated");
                    player.classList.add("animate__rubberBand");
                    submitAttaque.classList.add("animate__animated");
                    submitAttaque.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitAttaque.classList.remove("animate__rubberBand");
                        player.classList.remove("animate__rubberBand");
                    }, 1000);
                    let hadouken_song = document.getElementById("hadoudken-song");
                    hadouken_song.play();
                    alreadyPlaySong = true;
                    setTimeout(function() {
                        submitAttaque.click();
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