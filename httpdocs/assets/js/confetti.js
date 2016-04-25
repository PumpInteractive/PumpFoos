function Confetti()
{
    //canvas init
    this.canvas = null;
    this.ctx = null;
    this.interval = null;

    //angle will be an ongoing incremental flag. Sin and Cos functions will be applied to it to create vertical and horizontal movements of the flakes
    this.angle = null;

    //canvas dimensions
    this.W = null;
    this.H = null;

    //snowflake particles
    this.mp = 200; //max particles
    this.particles = [];
}

Confetti.prototype.start = function() {
    var self = this;

    this.canvas = document.getElementById("confetti");
    this.ctx = this.canvas.getContext("2d");

    //canvas dimensions
    this.W = window.innerWidth;
    this.H = window.innerHeight;

    this.canvas.width = this.W;
    this.canvas.height = this.H;

    for (var i = 0; i < this.mp; i++) {
        this.particles.push({
            x: Math.random() * this.W, //x-coordinate
            y: Math.random() * this.H, //y-coordinate
            r: Math.random() * 15 + 1, //radius
            d: Math.random() * this.mp, //density
            color: "rgba(" + Math.floor((Math.random() * 255)) + ", " + Math.floor((Math.random() * 255)) + ", " + Math.floor((Math.random() * 255)) + ", 1)",
            tilt: Math.floor(Math.random() * 5) - 5
        });
    }

    //animation loop
    this.interval = setInterval(function(){
        self.draw(self); // need to call draw this way so we can send in the object context in self
    }, 20);
}

Confetti.prototype.stop = function() {
    clearInterval(this.interval);
}

//Lets draw the flakes
Confetti.prototype.draw = function(self) {
    self.ctx.clearRect(0, 0, self.W, self.H);

    for (var i = 0; i < self.mp; i++) {
        var p = self.particles[i];
        self.ctx.beginPath();
        self.ctx.lineWidth = p.r;
        self.ctx.strokeStyle = p.color; // Green path
        self.ctx.moveTo(p.x, p.y);
        self.ctx.lineTo(p.x + p.tilt + p.r / 2, p.y + p.tilt);
        self.ctx.stroke(); // Draw it
    }

    self.update();
}

//Function to move the snowflakes
Confetti.prototype.update = function() {

    this.angle += 0.01;

    for (var i = 0; i < this.mp; i++) {
        var p = this.particles[i];
        //Updating X and Y coordinates
        //We will add 1 to the cos function to prevent negative values which will lead flakes to move upwards
        //Every particle has its own density which can be used to make the downward movement different for each flake
        //Lets make it more random by adding in the radius
        p.y += Math.cos(this.angle + p.d) + 1 + p.r / 2;
        p.x += Math.sin(this.angle) * 2;

        //Sending flakes back from the top when it exits
        //Lets make it a bit more organic and let flakes enter from the left and right also.
        if (p.x > this.W + 5 || p.x < -5 || p.y > this.H) {
            if (i % 3 > 0) //66.67% of the flakes
            {
                this.particles[i] = {
                    x: Math.random() * this.W,
                    y: -10,
                    r: p.r,
                    d: p.d,
                    color: p.color,
                    tilt: p.tilt
                };
            } else {
                //If the flake is exitting from the right
                if (Math.sin(this.angle) > 0) {
                    //Enter from the left
                    this.particles[i] = {
                        x: -5,
                        y: Math.random() * this.H,
                        r: p.r,
                        d: p.d,
                        color: p.color,
                        tilt: p.tilt
                    };
                } else {
                    //Enter from the right
                    this.particles[i] = {
                        x: this.W + 5,
                        y: Math.random() * this.H,
                        r: p.r,
                        d: p.d,
                        color: p.color,
                        tilt: p.tilt
                    };
                }
            }
        }
    }
}