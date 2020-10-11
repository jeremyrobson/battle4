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
        this.agl = rand(2, 10);
    }

    getTargets(map) {
        return map.getUnitsInRadius(this.target, this.spread);
    }

    calculateDamage(target) {
        switch (this.action_type) {
            case "melee":
                return rand(1, 7) + rand(1, 7) + rand(1, 7);
            case "arrow":
                return rand(1, 7);
            case "fire":
                return rand(1, 7) + rand(1, 7);
        }
    }

    calculateTotalDamage(map) {
        var total_damage = 0;
        var targets = this.getTargets(map);
        targets.forEach((target) => {
            if (this.actor.party_id === target.party_id) {
                total_damage -= this.calculateDamage(target);
            }
            else {
                total_damage += this.calculateDamage(target);
            }
        });
        return total_damage;
    }

    invoke(map) {
        //console.log("invoked " + this.action_type);
        var results = [];
        var targets = this.getTargets(map); //can only be enemies

        targets.forEach((target) => {
            var damage = this.calculateDamage();
            results.push({
                "target": target,
                "damage": damage,
                "type": target.applyDamage(damage)
            });
        });

        return results;
    }
}