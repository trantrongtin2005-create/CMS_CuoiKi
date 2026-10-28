// Let's inspect the DOM with edge and evaluate getBoundingClientRect
const { execSync } = require('child_process');
const script = `
  const banner = document.querySelector('.contact-hero-banner');
  const bRect = banner.getBoundingClientRect();
  const c = document.querySelector('.site-content .container');
  const cRect = c.getBoundingClientRect();
  const siteContent = document.querySelector('.site-content');
  const scRect = siteContent.getBoundingClientRect();
  const primary = document.querySelector('#primary');
  const pRect = primary ? primary.getBoundingClientRect() : null;
  console.log(JSON.stringify({
    windowWidth: window.innerWidth,
    siteContent: { width: scRect.width, left: scRect.left, top: scRect.top },
    container: { width: cRect.width, left: cRect.left, top: cRect.top },
    primary: pRect ? { width: pRect.width, left: pRect.left, top: pRect.top } : null,
    banner: { width: bRect.width, left: bRect.left, top: bRect.top, height: bRect.height }
  }));
`;
console.log("Evaluating script in browser...");
