class Battle {

    constructor(width, height, parties, fn) {
        this.q = new BattleQueue();
        this.map = new BattleMap(width, height);
        this.ondone = fn instanceof Function ? fn : function() { };
        this.text = "";

        this.units = [];
        this.friendlies = 0;
        this.enemies = 0;

        this.player = parties[0];
        this.cpu = parties[1];

        this.player.units.forEach((unit) => {
            this.q.add(unit);
            this.units.push(unit);
            this.map.addUnit(unit);
        });

        this.cpu.units.forEach((unit) => {
            this.q.add(unit);
            this.units.push(unit);
            this.map.addUnit(unit);
        });

        this.activeUnit = null;
        this.last = 0;

        this.friendlies = this.player.units.length;
        this.enemies = this.cpu.units.length;
        this.status = "";
    }

    checkBattle() {
        this.friendlies = this.units.filter(function(unit) {
            return unit.team === "player" && unit.hp > 0;
        }).length;

        this.enemies = this.units.filter(function(unit) {
            return unit.team === "cpu" && unit.hp > 0;
        }).length;

        if (this.friendlies === 0) {
            this.done("You Lose!", "rgb(255,0,0)");
            return false;
        }
        if (this.enemies === 0) {
            this.done("You Win!", "rgb(255,255,0)");
            return false;
        }
        return true;
    }

    update(timestamp) {
        if (Math.floor(timestamp / 100) > this.last) { //one tick per second
            if (this.checkBattle()) {
                this.text = this.q.update(this.map);
            }

            this.last = Math.floor(timestamp / 100);
        }
    }

    draw(ctx) {
        ctx.fillStyle = "skyblue";
        ctx.fillRect(0, 0, 640, 480);

        this.map.draw(ctx, this.q.next);

        ctx.font = "12px monospace";
        ctx.fillStyle = "rgb(255,255,0)";
        ctx.fillText(`Friendlies: ${this.friendlies}`, 0, 0);
        ctx.fillText(`Enemies: ${this.enemies}`, 0, 12);
    }

    done(text, color) {
        addSprite(new BattleText(text, 100, 100, 50, color, 200, () => {
            this.ondone();
        }));
        this.status = "done";
    }
}