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