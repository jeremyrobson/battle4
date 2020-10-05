class BattleMap {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.map = [];
        this.tiles = [];
        this.units = [];

        for (var x=0; x<width; x++) {
            this.map[x] = [];
            for (var y=0; y<height; y++) {
                var tile = {
                    x: x,
                    y: y,
                    unit: null,
                    color: random_color(0, 25, 100, 150, 0, 25)
                };
                this.map[x][y] = tile;
                this.tiles.push(tile);
            }
        }

        shuffle(this.tiles);
    }

    addUnit(unit) {
        var tile = this.tiles.pop();
        unit.x = tile.x;
        unit.y = tile.y;
        tile.unit = unit;
        this.units.push(unit);
    }

    moveUnit(unit, mx, my) {
        var x = unit.x + mx;
        var y = unit.y + my;
        var u = this.map[x][y].unit;
        if (u) {
            this.swapUnit(unit, u);
        }
        else {
            this.map[unit.x][unit.y].unit = null;
            unit.x = x;
            unit.y = y;
            this.map[x][y].unit = unit;
        }
        return true;
    }

    swapUnit(a, b) {
        var tmpx = a.x;
        var tmpy = a.y;
        a.x = b.x;
        a.y = b.y;
        b.x = tmpx;
        b.y = tmpy;
        this.map[a.x][a.y].unit = a;
        this.map[b.x][b.y].unit = b;
    }

    getUnitsInRadius(point, radius) {
        return this.units.filter((unit) => {
            var distance = getDistance(point, unit);
            return !unit.dead && distance <= radius;
        });
    }

    getFriends(unit, alive, healthy) {
        return this.units.filter(function(u) {
            return unit.party_id === u.party_id
                && unit.id !== u.id
                && (!alive || !u.dead)
                && (!healthy || !u.critical());
        }).sort(function(a, b) {
            var da = getDistance(unit, a);
            var db = getDistance(unit, b);
            return da - db;
        });
    }

    getEnemies(unit) {
        return this.units.filter(function(u) {
            return unit.party_id !== u.party_id
                && !u.dead;
        }).sort(function(a, b) {
            var da = getDistance(unit, a);
            var db = getDistance(unit, b);
            return da - db;
        });
    }

    //the safest possible area, todo: heatmap
    getSafety(unit) {
        if (Number.isInteger(unit.party_id)) {
            return {x: this.width-1, y: this.height-1};
        }
        else {
            return {x: 0, y: 0};
        }
    }

    draw(ctx, activeUnit) {
        ctx.shadowColor = 0;
        ctx.shadowBlur = 0;

        for (var x=0; x<this.width; x++) {
            var dx = x * TILE_WIDTH;

            for (var y=0; y<this.height; y++) {
                var dy = y * TILE_HEIGHT;

                ctx.fillStyle = this.map[x][y].color;
                ctx.fillRect(dx, dy, TILE_WIDTH, TILE_HEIGHT);
            }
        }

        this.units.forEach(function(unit) {
            unit.draw(ctx, activeUnit);
        });
    }
}