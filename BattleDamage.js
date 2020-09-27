class BattleDamage extends BattleSprite {
    constructor(text, target) {
        super();
        this.x = target.x;
        this.y = target.y;
        this.text = text;
    }

    draw(ctx) {
        var dx = this.x * TILE_WIDTH;
        var dy = this.y * TILE_HEIGHT;

        ctx.font = "24px monospace";
        ctx.fillStyle = "rgb(255,0,0)";
        ctx.fillText(this.text, dx, dy);
    }
}