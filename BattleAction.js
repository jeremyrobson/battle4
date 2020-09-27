class BattleAction {
    constructor(action_type, actor, target, map) {
        var action = action_templates[action_type];

        this.action_type = action_type;
        this.action_cost = action.action_cost;
        this.actor = actor;
        this.target = target; //can be enemy or tile
        this.map = map;
        this.ct = 0;
        this.range = action.range;
        this.pow = 10;
        this.agl = rand(2, 10);
    }

    getTargets() {
        //todo: get targets based on selected target's x,y for AOE
        return [this.target];
    }

    calculateDamage() {
        switch (this.action_type) {
            case "melee":
                return rand(1, 7) + rand(1, 7) + rand(1, 7);
            case "arrow":
                return rand(1, 7);
        }
    }

    calculateAOEDamage() {
        /*
        var total_damage = 0;
        var targets = get AOE targets from map
        targets.forEach((target) => {
            total_damage += this.calculateDamage();
        });
        return total_damage;
        */
    }

    invoke() {
        //console.log("invoked " + this.action_type);
        var results = [];
        var targets = this.getTargets(); //can only be enemies

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