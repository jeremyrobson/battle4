class BattleText extends BattleSprite {
    constructor(text, x, y, size, color, life, fn) {
        super(fn);
        this.text = text;
        this.x = x;
        this.y = y;
        this.size = size;
        this.color = color;
        this.life = life;
    }

    draw(ctx) {
        var dx = this.x;
        var dy = this.y;

        ctx.shadowColor = "black";
        ctx.shadowOffsetX = 1;
        ctx.shadowOffsetY = 1;

        ctx.fillStyle = this.color;
        ctx.font = `${this.size}px monospace`;
        ctx.fillText(this.text, dx, dy);

        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
    }
}