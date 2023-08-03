export default class Fighter {
    constructor(stats) {
        this.isAlive = true;
        this.id = stats.id;
        this.name = stats.name;
        this.health = stats.health;
        this.maxHealth = stats.health;
        this.mana = stats.mana;
        this.maxMana = stats.mana;
        this.attack = stats.attack;
        this.healRatio = stats.healRatio;
    }
    takeDamage(amount) {
        this.health = Math.max(this.health - amount, 0);

        if (this.health <= 0)
            this.isAlive = false;
    }
    heal() {
        if (this.mana < this.healRatio)
            return false;

        const oldHealth = this.health;

        const amount = Math.round(this.health * (this.healRatio / 100));
        this.health = Math.min(this.maxHealth, this.health + amount);
        this.mana = Math.max(this.mana - this.healRatio, 0);

        return (this.health - oldHealth);
    }
}