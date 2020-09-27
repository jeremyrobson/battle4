class BattleQueue {
    constructor() {
        this.list = [];
        this.next = null;
    }

    log() {
        var text = "";
        this.list.forEach((item) => {
            text = text + item.getText() + "&nbsp;" + "&nbsp;" + item.ct + "<br>";
        });
        return text;
    }

    update(map) {
        if (this.next) {
            this.next.turn(map);

            if (this.next.done) {
                this.next = null;
            }

            return this.log();
        }

        this.sort();

        for (var i=0; i<this.list.length; i++) {
            var item = this.list[i];
            if (item.ct >= 100) {
                this.next = item.ready();
                break;
            }
        }

        if (this.next) {
            return this.log();
        }

        this.list.forEach(function (item) {
            item.update();
        });

        return this.log();
    }

    add(item) {
        this.list.push(item);
        this.sort();
    }

    sort() {
        this.list.sort(function(a, b) {
            return b.ct - a.ct;
        });
    }

    draw(ctx) {

    }
}