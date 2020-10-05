class Party {
    constructor(party_id, name, color) {
        this.party_id = party_id;
        this.name = name;
        this.color = color;
        this.units = [];
    }

    add(unit) {
        this.units.push(unit);
    }
}