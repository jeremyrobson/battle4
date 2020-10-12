class Party {
    constructor(party_data) {
        this.party_id = party_data.party_id;
        this.name = name;
        this.color = party_data.color;
        this.units = [];
        this.starting_point = party_data.starting_point;

        party_data["units"].forEach((unit_data) => {
            this.units.push(new BattleUnit(this, unit_data));
        });
    }

    add(unit) {
        this.units.push(unit);
    }
}