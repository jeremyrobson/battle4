class BattleLine extends BattleSprite {
    constructor(u1, u2) {
        super();
        this.u1 = u1;
        this.u2 = u2;
    }

    draw(ctx) {
        ctx.lineWidth = 2;
        context.lineCap = "round";
        ctx.strokeStyle = this.u1.color;
        ctx.beginPath();
        ctx.moveTo(this.u1.x * TILE_WIDTH, this.u1.y * TILE_HEIGHT);
        ctx.lineTo(this.u2.x * TILE_WIDTH, this.u2.y * TILE_HEIGHT);
        ctx.stroke();
    }
}