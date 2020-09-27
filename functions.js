Array.prototype.pick_random = function() {
    return this[rand(0, this.length)];
};

function clamp(value, min, max) {
    if (value < min) return min;
    if (value >= max) return max;
    return value;
}

function rand(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

function getDistance(a, b) {
    var dx = b.x - a.x;
    var dy = b.y - a.y;
    return Math.sqrt(dx * dx + dy * dy);
}

function random_name() {
    var name = "";
    var length = rand(2,5);
    for (var i=0; i<length; i++) {
        name += phonemes.pick_random()
    }
    return name;
}