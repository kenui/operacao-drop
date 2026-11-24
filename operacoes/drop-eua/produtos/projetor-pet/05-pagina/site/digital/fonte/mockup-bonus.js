// Renderiza o mockup dos bônus (sem crédito de IA). Uso: node mockup-bonus.js
const { chromium } = require('playwright');
const path = require('path');
const img = path.resolve(__dirname, '../../public_html/assets/img');
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ deviceScaleFactor: 1 });
  await p.goto('file://' + path.join(__dirname, 'mockup-bonus.html'));
  await p.locator('#stack').screenshot({ path: img + '/bonus-stack.png' });
  await p.locator('#th1').screenshot({ path: img + '/bonus-guide-sm.png' });
  await p.locator('#th2').screenshot({ path: img + '/bonus-rain-sm.png' });
  await b.close();
})();
