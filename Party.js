class Party {
    constructor(party_data) {
        this.party_id = party_data.party_id;
        this.name = name;
        this.color = party_data.color;
        this.units = [];
        this.starting_point = party_data.starting_point;

        Object.keys(party_data["units"]).forEach((unit_id) => {
            this.units.push(new BattleUnit(this, party_data["units"][unit_id]));
        });
    }

    add(unit) {
        this.units.push(unit);
    }
}