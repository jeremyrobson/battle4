class BattleUnit {
    constructor(party, unit_data) {
        var job_template = job_templates[unit_data.job_id];

        this.unit_id = unit_data.unit_id;
        this.name = unit_data.name;
        this.sprite = job_template.sprite;
        this.party_id = unit_data.party_id;
        this.color = party.color;
        this.job_id = unit_data.job_id;
        this.hp = unit_data.hp;
        this.mp = 100;
        this.ct = 0; //todo: change to sta
        this.str = 10;
        this.agl = unit_data.agl;
        this.mag = 5;
        this.dead = false;
        this.acted = false;
        this.done = true;
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
        //also the target with lowest hp
        //todo: factor in move_cost, action_cost, target status (critical)
        this.actions.forEach((action_type) => {
            var action_template = action_templates[action_type];
            enemies.forEach((enemy) => {
                var distance = getDistance(this, enemy);
                var action = new BattleAction(action_type, this, enemy);
                var value = action_template.range*RANGE_WEIGHT + action.calculateTotalDamage(map) - distance;
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