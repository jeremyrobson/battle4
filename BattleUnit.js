class BattleUnit {
    constructor(party, unit_data) {
        this.unit_id = unit_data.unit_id;
        this.name = unit_data.name;
        this.sprite = unit_data.job.sprite;
        this.party_id = unit_data.party_id;
        this.color = unit_data.color;
        this.job_id = unit_data.job_id;
        this.hp = parseInt(unit_data.hp);
        this.mp = parseInt(unit_data.mp);
        this.str = parseInt(unit_data.str);
        this.agl = parseInt(unit_data.agl);
        this.sta = parseInt(unit_data.sta);
        this.mag = parseInt(unit_data.mag);
        this.ct = 0; //todo: change to sta
        this.dead = false;
        this.acted = false;
        this.done = true;
        this.actions = unit_data.job.actions;
        this.move_cost = unit_data.job.move_cost;
        this.x = 0;
        this.y = 0;
        this.starting_point = party.starting_point;
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
        //also the target with lowest hp?
        //todo: factor in move_cost, action_cost, target status (critical)
        Object.keys(this.actions).forEach((action_id) => {
            var action_data = this.actions[action_id];
            enemies.forEach((enemy) => {
                var distance = getDistance(this, enemy);
                var action = new BattleAction(action_data, this, enemy);
                var value = action_data.range*RANGE_WEIGHT + action.calculateTotalDamage(map) - distance;
                scores.push({
                    action_id: action_id,
                    target: enemy,
                    value: value
                });
            });
        });

        scores.sort(function(a, b) {
            return a.value - b.value;
        });

        var best_score = scores.pop();

        return new BattleAction(this.actions[best_score.action_id], this, best_score.target);
    }

    turn(map) {
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

                var results = action.invoke(map);

                results.forEach((result) => {
                    switch (result.type) {
                        case "hit":
                            addSprite(new BattleDamage(result.damage, result.target));
                            addSprite(new BattleLine(this, result.target));
                            break;
                        case "kill":
                            addSprite(new BattleDamage(result.damage, result.target));
                            addSprite(new BattleLine(this, result.target));
                            break;
                        case "miss":
                            break;
                    }
                });

                this.ct -= action.action_cost;
                this.acted = true;
            } else {
                //console.log("living friends", map.getFriends(this, true).length);
                //run away if critical but still have friends left
                if (this.critical() && map.getFriends(this, true, true).length > 0) {
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
                //console.error(this.name + " failed to invoke action", this.ct);
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
            return "kill";
        }
        return "hit";
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
        if (activeUnit && !this.done) {
            ctx.shadowColor = "yellow";
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            ctx.shadowBlur = 10;
        }
        else {
            ctx.shadowColor = 0;
            ctx.shadowBlur = 0;
        }

        var dx = this.x * TILE_WIDTH + TILE_WIDTH / 3;
        var dy = this.y * TILE_HEIGHT + TILE_HEIGHT / 2;
        ctx.font = "16px Arial";
        ctx.fillStyle = this.color;
        ctx.fillText(this.sprite, dx, dy);

        this.drawHealth(ctx);

        ctx.shadowColor = "black";
        ctx.shadowBlur = 0;
    }

    drawHealth(ctx) {
        var dx = this.x * TILE_WIDTH;
        var dy = this.y * TILE_HEIGHT;
        ctx.fillStyle = this.dead ? "rgb(0,0,0)" : "rgb(255,0,0)";
        ctx.fillRect(dx, dy, TILE_WIDTH, 4);
        ctx.fillStyle = "rgb(0,255,0)";
        ctx.fillRect(dx, dy, TILE_WIDTH * this.hp / 100, 4);
    }
}