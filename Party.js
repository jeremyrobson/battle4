class Party {
    constructor(party_data, color) {
        this.party_id = party_data.party_id;
        this.name = name;
        this.color = color;
        this.units = [];

        party_data["units"].forEach((unit_data) => {
            this.units.push(new BattleUnit(this, unit_data));
        });
    }

    add(unit) {
        this.units.push(unit);
    }
}