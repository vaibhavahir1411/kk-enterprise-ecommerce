// public_html/assets/js/theme-effects.js

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    
    // Create canvas for effects
    const canvas = document.createElement('canvas');
    canvas.classList.add('firework-canvas');
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    let width = window.innerWidth;
    let height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;

    window.addEventListener('resize', () => {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    });

    // Check Theme
    if (body.classList.contains('theme-diwali') || body.classList.contains('theme-default')) {
        // Simple Fireworks Effect
        const particles = [];
        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = height;
                this.vx = Math.random() * 4 - 2;
                this.vy = -(Math.random() * 5 + 10); // Shoot up
                this.color = `hsl(${Math.random() * 360}, 100%, 50%)`;
                this.life = 100;
                this.exploded = false;
            }
            update() {
                if (!this.exploded) {
                    this.x += this.vx;
                    this.y += this.vy;
                    this.vy += 0.2; // Gravity
                    if (this.vy >= 0) {
                        this.explode();
                        this.exploded = true;
                    }
                } else {
                    this.life -= 2;
                }
            }
            explode() {
                for (let i = 0; i < 20; i++) {
                   sparks.push(new Spark(this.x, this.y, this.color));
                }
            }
            draw() {
                if (!this.exploded) {
                    ctx.fillStyle = this.color;
                    ctx.fillRect(this.x, this.y, 4, 4);
                }
            }
        }

        const sparks = [];
        class Spark {
            constructor(x, y, color) {
                this.x = x;
                this.y = y;
                this.vx = Math.random() * 6 - 3;
                this.vy = Math.random() * 6 - 3;
                this.color = color;
                this.life = 50;
            }
            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.life--;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.life / 50;
                ctx.fillRect(this.x, this.y, 3, 3);
                ctx.globalAlpha = 1.0;
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);
            
            // Randomly launch fireworks
            if (Math.random() < 0.03) {
                particles.push(new Particle());
            }

            particles.forEach((p, index) => {
                p.update();
                p.draw();
                if (p.life <= 0 && p.exploded) particles.splice(index, 1);
            });

            sparks.forEach((s, index) => {
                s.update();
                s.draw();
                if (s.life <= 0) sparks.splice(index, 1);
            });

            requestAnimationFrame(animate);
        }
        
        // Start animation only if strictly Diwali or explicit
        if (body.classList.contains('theme-diwali')) {
            animate();
        }
    }
});
