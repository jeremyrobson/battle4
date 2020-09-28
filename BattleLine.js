class BattleLine extends BattleSprite {
    constructor(u1, u2) {
        super();
        this.u1 = u1;
        this.u2 = u2;
    }

    draw(ctx) {
        var dx1 = this.u1.x * TILE_WIDTH + TILE_WIDTH / 2;
        var dy1 = this.u1.y * TILE_HEIGHT + TILE_HEIGHT / 2;
        var dx2 = this.u2.x * TILE_WIDTH + TILE_WIDTH / 2;
        var dy2 = this.u2.y * TILE_HEIGHT + TILE_HEIGHT / 2;

        ctx.lineWidth = 2;
        context.lineCap = "round";
        ctx.strokeStyle = this.u1.color;
        ctx.beginPath();
        ctx.moveTo(dx1, dy1);
        ctx.lineTo(dx2, dy2);
        ctx.stroke();
    }
}