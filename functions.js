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

function random_color(r0, r1, g0, g1, b0, b1) {
    var r = rand(r0, r1);
    var g = rand(g0, g1);
    var b = rand(b0, b1);
    return `rgb(${r},${g},${b})`;
}