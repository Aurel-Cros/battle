import AudioFile from './Audio.js';
import PageBuilder from './PageBuilder.js';
import match from './vueTemplates.js'
import Fighter from './Fighter.js';

export default class App {
    constructor() {
        this.container = document.querySelector(".container");
        this.prematch = document.querySelector("#prematch");
        this.audio = [];
        this.fighters = [];
    }

    initBtns = {
        fight: () => {
            const submitFight = document.querySelector("#fight");
            this.audio.push(new AudioFile('fight.mp3', submitFight));
            submitFight.addEventListener("click", this.startFight.bind(this));
        },
        attack: () => {

            const submitAttack = document.querySelector("#attack");
            this.audio.push(new AudioFile('Haduken.mp3', submitAttack));
        },
        restart: () => {
            const submitRestart = document.querySelector("#restart");
            this.audio.push(new AudioFile('fatality.mp3', submitRestart));
        }
    }
    initFighterSelect() {
        fetch('./api/v1/fighters')
            .then(response => response.json())
            .then(data => {
                const fs = [document.querySelector(".fighter1Select"), document.querySelector(".fighter2Select")];
                fs.forEach((sel, index) => {
                    data.forEach(fighter => {

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
                    const formParent = form.parentElement;
                    sel.addEventListener("change", (e) => {
                        fs[Number(!index)].querySelectorAll('option')
                            .forEach(option => {
                                if (option.value == e.target.value && option.value)
                                    option.disabled = true;
                                else
                                    option.disabled = false;
                            });
                        if (e.target.value)
                            form.remove();
                        else
                            formParent.appendChild(form);
                    });
                })
            })
    }
    startFight() {
        const fs = [document.querySelector(".fighter1Select"), document.querySelector(".fighter2Select")];
        fs.forEach(sel => {
            const newFighter = new Fighter(JSON.parse(sel.value));
            this.fighters.push(newFighter);
        });
        console.log(this.fighters);
        this.prematch.remove();
        this.createBattleVue();
    }
    createBattleVue() {
        this.matchVue = new PageBuilder(match);

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
        console.log(this.fighters);

        this.fighters[0].vue.avatar.src = `https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=${this.fighters[0].name}`;
        this.fighters[1].vue.avatar.src = `https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=${this.fighters[1].name}`;

        this.updateVueValues();
        this.container.append(this.matchVue);
    }
    updateVueValues() {
        this.fighters[0].vue.name.textContent = 'Name : ' + this.fighters[0].name;
        this.fighters[0].vue.health.textContent = this.fighters[0].health;
        this.fighters[0].vue.attack.textContent = 'Attack : ' + this.fighters[0].attack;
        this.fighters[0].vue.mana.textContent = 'Mana : ' + this.fighters[0].mana;

        this.fighters[1].vue.name.textContent = 'Name : ' + this.fighters[1].name;
        this.fighters[1].vue.health.textContent = this.fighters[1].health;
        this.fighters[1].vue.attack.textContent = 'Attack : ' + this.fighters[1].attack;
        this.fighters[1].vue.mana.textContent = 'Mana : ' + this.fighters[1].mana;
    }
}