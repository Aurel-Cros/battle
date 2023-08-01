const AUDIO_DIR = './';

const submitFight = document.querySelector("#fight");
if (submitFight) {
    submitFight.addEventListener("click", function (event) {
        event.preventDefault();

        submitFight.classList.add("animate__animated");
        submitFight.classList.add("animate__rubberBand");
        setTimeout(function () {
            submitFight.classList.remove("animate__rubberBand");
        }, 1000);

        const fight_song = new Audio(AUDIO_DIR + 'fight.mp3');
        fight_song.play();

        setTimeout(function () {
            document.forms["formFight"].submit();
        }, 500);
    })
}

const submitattack = document.querySelector("#attack");
let alreadyPlaySong = false;
if (submitattack) {
    submitattack.addEventListener("click", function (event) {
        if (alreadyPlaySong)
            return true;
        event.preventDefault();

        const player = document.querySelector("#player")
        player.classList.add("animate__animated");
        player.classList.add("animate__rubberBand");
        submitattack.classList.add("animate__animated");
        submitattack.classList.add("animate__rubberBand");
        setTimeout(function () {
            submitattack.classList.remove("animate__rubberBand");
            player.classList.remove("animate__rubberBand");
        }, 1000);

        const hadouken_song = new Audio(AUDIO_DIR + 'Haduken.mp3');
        hadouken_song.play();
        alreadyPlaySong = true;

        setTimeout(function () {
            submitattack.click();
        }, 1000);
    })
}

const submitRestart = document.querySelector("#restart");
let alreadyPlaySongRestart = false;
if (submitRestart) {
    submitRestart.addEventListener("click", function (event) {
        if (alreadyPlaySongRestart)
            return true;
        event.preventDefault();

        const fatality_song = new Audio(AUDIO_DIR + 'fatality.mp3');
        fatality_song.play();
        alreadyPlaySongRestart = true;

        setTimeout(function () {
            submitRestart.click();
        }, 2000);
    })
}

document.querySelector(".fighter1Select").addEventListener("change", (e) => {
    if (!e.target.value) {
        document.querySelector("input[name='player[id]']").value = '';
        document.querySelector("input[name='player[name]']").readOnly = false;
        document.querySelector("input[name='player[attack]']").readOnly = false;
        document.querySelector("input[name='player[health]']").readOnly = false;
        document.querySelector("input[name='player[mana]']").readOnly = false;
        return;
    }
    const fighter = JSON.parse(e.target.value);
    document.querySelector("input[name='player[id]']").value = fighter.id;
    document.querySelector("input[name='player[name]']").value = fighter.name;
    document.querySelector("input[name='player[attack]']").value = fighter.attack;
    document.querySelector("input[name='player[health]']").value = fighter.health;
    document.querySelector("input[name='player[mana]']").value = fighter.mana;

    document.querySelector("input[name='player[name]']").readOnly = true;
    document.querySelector("input[name='player[attack]']").readOnly = true;
    document.querySelector("input[name='player[health]']").readOnly = true;
    document.querySelector("input[name='player[mana]']").readOnly = true;
});
document.querySelector(".fighter2Select").addEventListener("change", (e) => {
    if (!e.target.value) {
        document.querySelector("input[name='opponent[id]']").value = '';
        document.querySelector("input[name='opponent[name]']").readOnly = false;
        document.querySelector("input[name='opponent[attack]']").readOnly = false;
        document.querySelector("input[name='opponent[health]']").readOnly = false;
        document.querySelector("input[name='opponent[mana]']").readOnly = false;
        return;
    }
    const fighter = JSON.parse(e.target.value);
    document.querySelector("input[name='opponent[id]']").value = fighter.id;
    document.querySelector("input[name='opponent[name]']").value = fighter.name;
    document.querySelector("input[name='opponent[attack]']").value = fighter.attack;
    document.querySelector("input[name='opponent[health]']").value = fighter.health;
    document.querySelector("input[name='opponent[mana]']").value = fighter.mana;

    document.querySelector("input[name='opponent[name]']").readOnly = true;
    document.querySelector("input[name='opponent[attack]']").readOnly = true;
    document.querySelector("input[name='opponent[health]']").readOnly = true;
    document.querySelector("input[name='opponent[mana]']").readOnly = true;
});