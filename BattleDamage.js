class BattleDamage extends BattleSprite {
    constructor(text, target) {
        super();
        this.x = target.x;
        this.y = target.y;
        this.text = text;
    }

    draw(ctx) {
        var dx = this.x * TILE_WIDTH + TILE_WIDTH / 2;
        var dy = this.y * TILE_HEIGHT + TILE_HEIGHT / 2;

        ctx.shadowColor = "black";
        ctx.shadowOffsetX = 1;
        ctx.shadowOffsetY = 1;

        ctx.font = "24px monospace";
        ctx.fillStyle = "rgb(255,0,0)";
        ctx.fillText(this.text, dx, dy);

        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
    }
}