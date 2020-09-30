class BattleSprite {
    constructor(fn, life) {
        this.life = life || 100;
        this.dead = false;
        this.ondead = fn instanceof Function ? fn : function() { };
    }

    update() {
        this.life--;
        if (this.life <= 0) {
            this.dead = true;
            this.ondead();
        }
    }

    draw(ctx) {
    }
}