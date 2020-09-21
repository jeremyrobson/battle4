const TILE_WIDTH = 40;
const TILE_HEIGHT = 30;

const RANGE_WEIGHT = 2; //how valuable range is over damage

let action_templates = {
    "melee": {
        "range": 1.5,  //sqrt(2) is minimum required distance for melee attack
        "action_cost": 50
    },
    "arrow": {
        "range": 5,
        "action_cost": 50
    }
};

let job_templates = {
    "fighter": {
        "move_cost": 20,
        "actions": ["melee"]
    },
    "archer": {
        "move_cost": 40,
        "actions": ["arrow"]
    }
};

class BattleMap {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.map = [];
        this.tiles = [];
        this.units = [];

        for (var x=0; x<width; x++) {
            this.map[x] = [];
            for (var y=0; y<height; y++) {
                var tile = {
                    x: x,
                    y: y,
                    unit: null
                };
                this.map[x][y] = tile;
                this.tiles.push(tile);
            }
        }

        shuffle(this.tiles);
    }

    addUnit(unit) {
        var tile = this.tiles.pop();
        unit.x = tile.x;
        unit.y = tile.y;
        tile.unit = unit;
        this.units.push(unit);
    }

    moveUnit(unit, mx, my) {
        var x = unit.x + mx;
        var y = unit.y + my;
        var u = this.map[x][y].unit;
        if (u) {
            this.swapUnit(unit, u);
        }
        else {
            this.map[unit.x][unit.y].unit = null;
            unit.x = x;
            unit.y = y;
            this.map[x][y].unit = unit;
        }
        return true;
    }

    swapUnit(a, b) {
        var tmpx = a.x;
        var tmpy = a.y;
        a.x = b.x;
        a.y = b.y;
        b.x = tmpx;
        b.y = tmpy;
        this.map[a.x][a.y].unit = a;
        this.map[b.x][b.y].unit = b;
    }

    getFriends(unit, alive, healthy) {
        return map.units.filter(function(u) {
            return unit.team === u.team && unit.id !== u.id && (!alive || !u.dead) && (!healthy || !u.critical());
        }).sort(function(a, b) {
            var da = getDistance(unit, a);
            var db = getDistance(unit, b);
            return da - db;
        });
    }

    getEnemies(unit) {
        return map.units.filter(function(u) {
            return unit.team !== u.team && !u.dead;
        }).sort(function(a, b) {
            var da = getDistance(unit, a);
            var db = getDistance(unit, b);
            return da - db;
        });
    }

    //the safest possible area, todo: heatmap
    getSafety(unit) {
        if (unit.team === "player") {
            return {x: this.width-1, y: this.height-1};
        }
        else {
            return {x: 0, y: 0};
        }
    }

    draw(ctx, activeUnit) {
        ctx.shadowColor = 0;
        ctx.shadowBlur = 0;

        for (var x=0; x<this.width; x++) {
            var dx = x * TILE_WIDTH;

            for (var y=0; y<this.height; y++) {
                var dy = y * TILE_HEIGHT;

                ctx.fillStyle = "forestgreen";
                ctx.fillRect(dx, dy, TILE_WIDTH, TILE_HEIGHT);
            }
        }

        units.forEach(function(unit) {
            unit.draw(context, activeUnit);
        });
    }
}


class Party {
    constructor(name, color) {
        this.name = name;
        this.color = color;
        this.units = [];
    }

    add(unit) {
        this.units.push(unit);
    }
}

class Unit {
    constructor(party, job_type) {
        var job_template = job_templates[job_type];

        this.id = Math.random();
        this.name = random_name();
        this.sprite = this.name.substr(0, 1);
        this.team = party.name;
        this.color = party.color;
        this.job_type = job_type;
        this.hp = 100;
        this.mp = 100;
        this.ct = 0;
        this.str = 10;
        this.agl = rand(2,10);
        this.mag = 5;
        this.dead = false;
        this.acted = false;
        this.done = false;
        this.actions = job_template.actions;
        this.move_cost = job_template.move_cost;

        this.x = 0;
        this.y = 0;
    }

    getText() {
        return this.name + " (" + this.hp + ")";
    }

    critical() {
        return this.hp < 25;
    }

    ready() {
        this.acted = false;
        this.done = false;

        return this;
    }

    update() {
        if (!this.dead) {
            this.ct += this.agl;
        }
    }

    selectAction(map) {
        var enemies = map.getEnemies(this);
        //var friends = map.getFriends(this, false);

        var scores = [];

        //for each action look at each enemy
        //for each enemy, calculate damage, and look at action range
        //the best action is the one with the highest damage + longest range + shortest distance
        //todo: factor in move_cost, action_cost, target status (critical)
        this.actions.forEach((action_type) => {
            var action_template = action_templates[action_type];
            enemies.forEach((enemy) => {
                var distance = getDistance(this, enemy);
                var action = new BattleAction(action_type, this, enemy);
                var value = action_template.range*RANGE_WEIGHT + action.calculateDamage() - distance;
                scores.push({
                    action_type: action_type,
                    target: enemy,
                    value: value
                });
            });
        });

        scores.sort(function(a, b) {
            return a.value - b.value;
        });

        var best_score = scores.pop();

        return new BattleAction(best_score.action_type, this, best_score.target);
    }

    invokeAction(action) {
        action.invoke();
    }

    invoke(map) {
        var target = null;
        var distance = 0;

        if (this.acted) {
            target = map.getSafety(this);
            distance = getDistance(this, target);
            if (distance > 1) {
                if (this.moveToward(map, target)) {
                    this.ct -= this.move_cost;
                }
                else {
                    console.error("unable to move");
                }
            }
            else {
                this.done = true;
            }
        }
        else {
            var action = this.selectAction(map);

            target = action.target;
            distance = getDistance(this, target);

            if (distance <= action.range) {
                this.invokeAction(action);
                this.ct -= action.action_cost;
                this.acted = true;
            } else {
                //console.log("living friends", map.getFriends(this, true).length);
                //run away if critical but still have friends left
                if (this.critical() && map.getFriends(this, true, true).length > 1) {
                    target = map.getSafety(this);
                }

                if (this.moveToward(map, target)) {
                    this.ct -= this.move_cost;
                } else {
                    console.error("unable to move");
                }
            }
        }


        if (this.ct <= 0) {
            if (!this.acted) {
                //if movement drained the ct, the unit cannot act
                //therefore the selected action must take ct into account?
                console.error(this.name + " failed to invoke action", this.ct);
            }
            this.ct = 0;
            this.done = true;
        }
    }

    applyDamage(damage) {
        this.hp -= damage;
        if (this.hp <= 0) {
            this.hp = 0;
            this.dead = true;
            this.color = "rgb(100,100,100)";
            this.ct = 0;

            if (this.team === "player") {
                friendlies--;
            }
            if (this.team === "cpu") {
                enemies--;
            }
        }
    }

    moveToward(map, target) {
        var mx = 0;
        var my = 0;
        if (target.x < this.x) mx--;
        if (target.y < this.y) my--;
        if (target.x > this.x) mx++;
        if (target.y > this.y) my++;
        return map.moveUnit(this, mx, my);
    }

    draw(ctx, activeUnit) {
        if (activeUnit && activeUnit.id === this.id) {
            ctx.shadowColor = "yellow";
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            ctx.shadowBlur = 10;
        }
        else {
            ctx.shadowColor = 0;
            ctx.shadowBlur = 0;
        }

        var dx = this.x * TILE_WIDTH;
        var dy = this.y * TILE_HEIGHT;
        ctx.font = "12px Arial";
        ctx.fillStyle = this.color;
        ctx.fillText(this.name, dx, dy);
    }
}

class BattleAction {
    constructor(action_type, actor, target) {
        var action = action_templates[action_type];

        this.action_type = action_type;
        this.action_cost = action.action_cost;
        this.actor = actor;
        this.target = target;
        this.ct = 0;
        this.range = action.range;
        this.pow = 10;
        this.agl = rand(2, 10);
    }

    calculateDamage() {
        switch (this.action_type) {
            case "melee":
                return rand(1, 7) + rand(1, 7) + rand(1, 7);
            case "arrow":
                return rand(1, 7);
        }
    }

    invoke() {
        console.log("invoked " + this.action_type);
        var damage = this.calculateDamage();
        this.target.applyDamage(damage);
        sprites.push(new Damage(damage, this.target));
        sprites.push(new Line(this.actor, this.target));
    }
}

class Queue {
    constructor() {
        this.list = [];
        this.next = null;
    }

    log() {
        var text = "";
        this.list.forEach((item) => {
            text = text + item.getText() + "&nbsp;" + "&nbsp;" + item.ct + "<br>";
        });
        textarea.innerHTML = text;
    }

    update(map) {
        if (this.next) {
            this.next.invoke(map);

            if (this.next.done) {
                this.next = null;
            }

            return;
        }

        this.sort();

        for (var i=0; i<this.list.length; i++) {
            var item = this.list[i];
            if (item.ct >= 100) {
                this.next = item.ready();
                break;
            }
        }


        this.log();

        if (this.next) {
            return;
        }

        this.list.forEach(function (item) {
            item.update();
        });
    }

    add(item) {
        this.list.push(item);
        this.sort();
    }

    sort() {
        this.list.sort(function(a, b) {
            return b.ct - a.ct;
        });
    }

    draw(ctx) {

    }
}

class Sprite {
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

class Damage extends Sprite {
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

class Line extends Sprite {
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