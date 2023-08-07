import AudioFile from './Audio.js';
import PageBuilder from './PageBuilder.js';
import vueTemplates from './vueTemplates.js'
import Fighter from './Fighter.js';
import HallOfFame from './HallOfFame.js';

export default class App {
    constructor() {
        this.container = document.querySelector(".container");
        this.prematch = document.querySelector("#prematch");
        this.audio = [];
        this.existingFighters = [];
        this.fighters = [];
        this.logs = [];
        this.fightId = null;
    }

    initBtns = {
        fight: async () => {
            const submitFight = document.querySelector("#fight");
            submitFight.addEventListener("click", () => {
                if (this.checkInput()) {
                    console.log('Start fight')
                    this.audio.push(new AudioFile('fight.mp3', submitFight));
                    this.startFight.bind(this)();
                }
                else {
                    // Error handler
                    console.log('No fight')
                }
            });
        },
        match: () => {
            this.initBtns.attack();
            this.initBtns.heal();
            this.initBtns.restart();
        },
        attack: () => {
            const submitAttack = document.querySelector("#attack");
            this.audio.push(new AudioFile('Haduken.mp3', submitAttack));
            submitAttack.addEventListener("click", this.attack.bind(this));
        },
        heal: () => {
            const healBtn = document.querySelector("#heal");
            healBtn.addEventListener("click", this.heal.bind(this));
        },
        restart: () => {
            const submitRestart = document.querySelector("#restart");
            this.audio.push(new AudioFile('fatality.mp3', submitRestart));
            submitRestart.addEventListener("click", this.reset.bind(this));
        }
    }
    initHOF() {
        const link = document.querySelector(".toHallOfFame");
        link.addEventListener("click", (e) => {
            e.preventDefault();
            new HallOfFame(document.body);
        })
    }
    attack() {
        this.fighters.forEach((fighter, index) => {

            const otherFighter = this.fighters[Number(!index)];
            const damage = otherFighter.attack;
            fighter.takeDamage(damage);
            const log = otherFighter.name + " attaque et inflige " + damage + " à " + fighter.name + " !";
            this.updateLogs(log);
        })
        this.sendLogs();
        this.updateVueValues();
        this.checkWin();
    }
    heal() {
        this.fighters.forEach((fighter) => {
            const hasHealed = fighter.heal();
            const log = hasHealed ?
                fighter.name + " s'est soigné de " + hasHealed + " PV"
                : fighter.name + " n'a plus de mana et n'a pas pu se soigner !";
            this.updateLogs(log);
        })
        this.sendLogs();
        this.updateVueValues();
    }
    reset() {
        console.log("Reset fight");
        this.fighters = [];
        this.logs = [];
        this.fightId = null;

        this.matchVue.remove();
        this.container.appendChild(this.prematch);

        const forms = document.querySelectorAll(`[id^=form-player]`);
        forms.forEach(form => { form.classList.remove('d-none') })
        this.initFighterSelect();
    }

    updateLogs(newLog) {
        this.logs.push(newLog);
        this.updateVueLogs();
    }
    sendLogs() {
        const payload = JSON.stringify({ newLogs: this.logs });
        fetch(`./api/v1/fights/${this.fightId}/logs`, {
            method: "PATCH",
            body: payload
        })
    }
    updateVueLogs() {
        this.logsVue.replaceChildren(
            ...this.logs.map(log => new PageBuilder({ tag: "li", content: log }))
        )
    }
    checkWin() {
        if (this.fighters[0].isAlive && !this.fighters[1].isAlive) {
            // Player 1 wins
            this.declareWin(this.fighters[0]);
        }
        else if (!this.fighters[0].isAlive && this.fighters[1].isAlive) {
            // Player 2 wins
            this.declareWin(this.fighters[1]);
        }
        else if (!this.fighters[0].isAlive && !this.fighters[1].isAlive) {
            // DRAW
            this.declareWin(null);
        }
        else
            return;
    }
    declareWin(winner) {
        let resultText;
        if (winner != null) {
            resultText = `${winner.name} est le vainqueur !`;

            const payload = JSON.stringify({ fighterId: winner.id });
            fetch(`./api/v1/fights/${this.fightId}/winner`, {
                method: "PATCH",
                body: payload
            });
        }
        else
            resultText = 'Les deux opponents sont KO, égalité !';

        this.updateVueToResults(resultText);
    }
    initFighterSelect() {
        fetch('./api/v1/fighters')
            .then(response => response.json())
            .then(data => {
                const fs = [document.querySelector(".fighter1Select"), document.querySelector(".fighter2Select")];
                console.log('Reset first view');
                fs.forEach((sel, index) => {
                    sel.replaceChildren();
                    sel.appendChild(new PageBuilder({ tag: "option" }))
                    data.forEach(fighter => {

                        if (index === 0)
                            this.existingFighters.push(fighter);

                        const option = new PageBuilder({
                            tag: 'option',
                            content: fighter.name,
                            attrs: {
                                value: JSON.stringify(fighter)
                            }
                        });

                        sel.appendChild(option);
                    });

                    const form = document.querySelector(`#form-player${index + 1}`);
                    sel.addEventListener("change", (e) => {
                        fs[Number(!index)].querySelectorAll('option')
                            .forEach(option => {
                                if (option.value == e.target.value && option.value)
                                    option.disabled = true;
                                else
                                    option.disabled = false;
                            });
                        if (e.target.value)
                            form.classList.add('d-none');
                        else
                            form.classList.remove('d-none');
                    });
                })
            })
    }

    checkInput() {
        const { select, textInputs } = this.getInputs();

        console.log("Select values : ", select[0].value, select[1].value);

        if (select[0].value && select[1].value)
            return true;
        const player1Ok = select[0].value ? true : false;
        const player2Ok = select[1].value ? true : false;

        console.log("Selects not both ok");

        let send = true;
        console.log(textInputs);
        let i = 0;
        for (const player in textInputs) {
            i++;
            if (i === 1 && player1Ok)
                continue;
            if (i === 2 && player2Ok)
                continue;

            textInputs[player].forEach(input => {
                const pattern = /^[a-z0-9 ]+$/i;

                if (!pattern.test(input.value)) {
                    console.log('Regex fail on ', input.value);
                    input.classList.add('is-invalid');
                    send = false;
                    return;
                }

                const value = input.value.replaceAll(' ', ' ');

                input.classList.remove('is-invalid');
                input.classList.remove('is-valid');

                if (Number.isInteger(value) && value < 1) {
                    console.log(value, " is number but is not positive");
                    input.classList.add('is-invalid');
                    send = false;
                }
                else if (Number.isInteger(value)) {
                    input.classList.add('is-valid');
                }

                if ((typeof value === 'string' || value instanceof String) && value.trim().length === 0) {
                    console.log(value, " is string but empty");
                    input.classList.add('is-invalid');
                    send = false;
                }
                else if ((typeof value === 'string' || value instanceof String) &&
                    this.existingFighters.find(fighter => fighter.name == value)) {

                    console.log(value, " already exists");

                    input.classList.add('is-invalid');
                    send = false;
                }
                else if ((typeof value === 'string' || value instanceof String)) {
                    input.classList.add('is-valid');
                }
            })
        }
        console.log(send ? 'Inputs ok' : 'Invalid inputs');
        return send;
    }

    getInputs() {
        const fs = [document.querySelector(".fighter1Select"), document.querySelector(".fighter2Select")];
        const textInputs = {
            player1:
                [
                    document.querySelector("input[name='player[name]']"),
                    document.querySelector("input[name='player[attack]']"),
                    document.querySelector("input[name='player[mana]']"),
                    document.querySelector("input[name='player[health]']")
                ],
            player2:
                [
                    document.querySelector("input[name='opponent[name]']"),
                    document.querySelector("input[name='opponent[attack]']"),
                    document.querySelector("input[name='opponent[mana]']"),
                    document.querySelector("input[name='opponent[health]']")
                ]
        };
        return { select: fs, textInputs: textInputs };
    }

    async createFighters() {
        const { select, textInputs } = this.getInputs();

        for (const index in select) {
            const sel = select[index];
            if (sel.value) {
                const newFighter = new Fighter(JSON.parse(sel.value));
                this.fighters.push(newFighter);
            }
            else {
                const playerNumber = Number(index) + 1;
                const player = textInputs['player' + playerNumber];
                console.log(textInputs, player, playerNumber);

                const randHealRate = Math.round(Math.random() * 10) + 15;

                const newFighterToAPI = {
                    name: player[0].value.replaceAll(' ', ' '),
                    attack: player[1].value,
                    mana: player[2].value,
                    health: player[3].value,
                    healRatio: randHealRate
                }
                const newFighterId = await fetch('./api/v1/fighters', {
                    method: "POST",
                    body: JSON.stringify(newFighterToAPI)
                })
                    .then(response => response.text())
                    .then(data => data);

                newFighterToAPI.id = Number(newFighterId);
                const newFighter = new Fighter(newFighterToAPI);
                this.fighters.push(newFighter);
            }
        }

        return true;
    }
    async startFight() {
        await this.createFighters();

        const fightInfos = JSON.stringify({
            id_fighter1: this.fighters[0].id,
            id_fighter2: this.fighters[1].id
        })
        fetch('./api/v1/fights', {
            method: "POST",
            body: fightInfos
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                this.fightId = Number(data.newFightId);
            });
        this.prematch.remove();
        this.createBattleVue();
    }

    createBattleVue() {
        this.matchVue = new PageBuilder(vueTemplates.match);
        this.logsVue = this.matchVue.querySelector(".battleLog");

        this.fighters[0].vue = {
            name: this.matchVue.querySelector("#name-player1"),
            attack: this.matchVue.querySelector("#attack-player1"),
            health: this.matchVue.querySelector("#hp-player1"),
            mana: this.matchVue.querySelector("#mana-player1"),
            avatar: this.matchVue.querySelector("#avatar-player1")
        }
        this.fighters[1].vue = {
            name: this.matchVue.querySelector("#name-player2"),
            attack: this.matchVue.querySelector("#attack-player2"),
            health: this.matchVue.querySelector("#hp-player2"),
            mana: this.matchVue.querySelector("#mana-player2"),
            avatar: this.matchVue.querySelector("#avatar-player2")
        }

        this.fighters[0].vue.avatar.src = `https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=${this.fighters[0].name}`;
        this.fighters[1].vue.avatar.src = `https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=${this.fighters[1].name}`;

        this.updateVueValues();
        this.container.append(this.matchVue);
        this.initBtns.match();
    }
    updateVueToResults(resultText) {
        this.matchVue.querySelector("#combats").remove();

        const result = new PageBuilder(vueTemplates.result);
        const resetBtn = result.querySelector("input");
        result.insertBefore(document.createTextNode(resultText), result.querySelector("div"));
        resetBtn.addEventListener("click", () => {
            this.reset();
        });

        this.matchVue.appendChild(result);
    }
    updateVueValues() {
        this.fighters[0].vue.name.textContent = 'Name : ' + this.fighters[0].name;
        this.fighters[0].vue.health.textContent = this.fighters[0].health + ' / ' + this.fighters[0].maxHealth;
        this.fighters[0].vue.attack.textContent = 'Attack : ' + this.fighters[0].attack;
        this.fighters[0].vue.mana.textContent = 'Mana : ' + this.fighters[0].mana;

        this.fighters[1].vue.name.textContent = 'Name : ' + this.fighters[1].name;
        this.fighters[1].vue.health.textContent = this.fighters[1].health + ' / ' + this.fighters[1].maxHealth;
        this.fighters[1].vue.attack.textContent = 'Attack : ' + this.fighters[1].attack;
        this.fighters[1].vue.mana.textContent = 'Mana : ' + this.fighters[1].mana;
    }
}