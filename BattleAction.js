class BattleAction {
    constructor(action_data, actor, target) {
        this.action_type = action_data.name;
        this.action_cost = parseInt(action_data.action_cost);
        this.actor = actor;
        this.target = target; //can be enemy or tile
        this.ct = 0;
        this.range = parseFloat(action_data.range);
        this.spread = parseFloat(action_data.spread);
        this.pow = 10;
        this.agl = randint(2, 10); //todo: non-instant actions
    }

    getTargets(map) {
        return map.getUnitsInRadius(this.target, this.spread);
    }

    calculateDamage(target, fix) {
        let base = 0;
        switch (this.action_type) {
            case "melee":
                base = (roll_dice(fix) + roll_dice(fix) + roll_dice(fix)) * this.actor.str;
                return Math.floor(base * (100-target.def) / 100);
            case "arrow":
                base = (roll_dice(fix)) * this.actor.str;
                return Math.floor(base * (100-target.def) / 100);
            case "fire":
                base = (roll_dice(fix) + roll_dice(fix)) * this.actor.mag;
                return base;
        }
        return base;
    }

    calculateTotalDamage(map) {
        var total_damage = 0;
        var targets = this.getTargets(map);
        targets.forEach((target) => {
            if (this.actor.party_id === target.party_id) {
                total_damage -= this.calculateDamage(target, 6);
            }
            else {
                total_damage += this.calculateDamage(target, 6);
            }
        });
        return total_damage;
    }

    invoke(map) {
        //console.log("invoked " + this.action_type);
        var results = [];
        var targets = this.getTargets(map); //can only be enemies

        targets.forEach((target) => {
            var damage = this.calculateDamage(target);
            results.push({
                "target": target,
                "damage": damage,
                "type": target.applyDamage(damage)
            });
        });

        return results;
    }
}