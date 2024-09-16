// Parallax

const layers = document.querySelectorAll(".rel-imgs");
const speeds = [2, 2, 3, 2, 2, 3];

const parallaxEffect = (e) => {
  const positionX = e.clientX - Math.floor(window.innerWidth);
  const positionY = e.clientY - Math.floor(window.innerHeight);

  layers.forEach((layer, i) => {
    gsap.to(layer, {
      translateX: positionX * (speeds[i] / 100),
      translateY: positionY * (speeds[i] / 100),
    });
  });
};

document.addEventListener("mousemove", parallaxEffect);
