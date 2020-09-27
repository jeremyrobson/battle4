class BattleSprite {
    constructor() {
        this.life = 100;
        this.dead = false;
    }

    update() {
        this.life--;
        if (this.life <= 0) {
            this.dead = true;
        }
    }

    draw(ctx) {
    }
}