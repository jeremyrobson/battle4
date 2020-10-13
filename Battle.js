class Battle {

    constructor(params, fn) {
        this.battle_code = params.battle_code;
        this.q = new BattleQueue();
        this.map = new BattleMap(params.width, params.height);
        this.ondone = fn instanceof Function ? fn : function() { };
        this.text = "";

        this.units = [];

        this.player = params.player;
        this.cpu = params.cpu;

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

        this.status = "";

        this.unit_count = {};
        this.unit_count[player.party_id] = player.units.length;
        this.unit_count[cpu.party_id] = cpu.units.length;
    }

    checkBattle() {
        this.unit_count[player.party_id] = this.units.filter(function(unit) {
            return unit.party_id === player.party_id && unit.hp > 0;
        }).length;

        this.unit_count[cpu.party_id] = this.units.filter(function(unit) {
            return unit.party_id === cpu.party_id && unit.hp > 0;
        }).length;

        if (this.unit_count[player.party_id] === 0) {
            this.lose();
            return false;
        }
        if (this.unit_count[cpu.party_id] === 0) {
            this.win();
            return false;
        }
        return true;
    }

    update(timestamp) {
        let tick = timestamp / 500;
        if (Math.floor(tick) > this.last) { //one tick per second
            if (this.checkBattle()) {
                this.text = this.q.update(this.map);
            }

            this.last = Math.floor(tick);
        }
    }

    draw(ctx) {
        ctx.fillStyle = "skyblue";
        ctx.fillRect(0, 0, 640, 480);

        this.map.draw(ctx, this.q.next);

        ctx.font = "12px monospace";
        ctx.fillStyle = player.color;
        ctx.fillText(`Friendlies: ${this.unit_count[player.party_id]}`, 0, 0);
        ctx.fillStyle = cpu.color;
        ctx.fillText(`Enemies: ${this.unit_count[cpu.party_id]}`, 0, 12);
    }

    win() {
        this.done("You Win!", "rgb(255,255,0)");
        this.winner = this.player.party_id;
    }

    lose() {
        this.done("You Lose!", "rgb(255,0,0)");
        this.winner = "enemy";
    }

    done(text, color) {
        addSprite(new BattleText(text, 100, 100, 50, color, 200, () => {
            this.ondone();
        }));
        this.status = "done";
    }

    getBattleData() {
        return {
            battle_code: this.battle_code,
            winner: this.winner,
            player: this.player,
            enemy: this.cpu
        }
    }
}