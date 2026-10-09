'use strict';

const { chromium } = require('playwright');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const raiz = path.resolve(__dirname, '..');
const evidencias = path.join(raiz, 'evidencias');
const chrome = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const url = 'http://127.0.0.1:8123/';

function escaparHtml(texto) {
  return texto
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;');
}

function esperarServidor(direccion, limiteMs = 10000) {
  const inicio = Date.now();
  return new Promise((resolve, reject) => {
    const intentar = async () => {
      try {
        const respuesta = await fetch(direccion);
        if (respuesta.ok) return resolve();
      } catch (_) {
        // El proceso puede tardar unas décimas en comenzar a escuchar.
      }
      if (Date.now() - inicio >= limiteMs) {
        return reject(new Error('El servidor PHP no respondió a tiempo.'));
      }
      setTimeout(intentar, 150);
    };
    intentar();
  });
}

(async () => {
  fs.mkdirSync(evidencias, { recursive: true });

  const verificacion = spawnSync('php', ['verificar.php'], {
    cwd: raiz,
    encoding: 'utf8',
    windowsHide: true,
  });
  const salida = `${verificacion.stdout || ''}${verificacion.stderr || ''}`.trim();
  if (verificacion.status !== 0 || !salida.includes('Resultado: 12 de 12 comprobaciones')) {
    throw new Error(`La verificación no alcanzó 12 de 12.\n${salida}`);
  }
  fs.writeFileSync(path.join(evidencias, 'RESULTADO_VERIFICADOR.txt'), `${salida}\n`, 'utf8');

  const servidor = spawn('php', ['-S', '127.0.0.1:8123', '-t', 'public'], {
    cwd: raiz,
    windowsHide: true,
    stdio: 'ignore',
  });

  let navegador;
  try {
    await esperarServidor(url);
    navegador = await chromium.launch({ executablePath: chrome, headless: true });

    const paginaCatalogo = await navegador.newPage({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
    await paginaCatalogo.goto(url, { waitUntil: 'networkidle' });
    await paginaCatalogo.screenshot({
      path: path.join(evidencias, 'CAPTURA_CATALOGO.png'),
      fullPage: true,
    });

    const paginaTerminal = await navegador.newPage({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
    await paginaTerminal.setContent(`<!doctype html>
      <html lang="es"><head><meta charset="utf-8"><style>
      *{box-sizing:border-box} body{margin:0;background:#111827;color:#e5e7eb;font-family:Consolas,"Cascadia Mono",monospace}
      .barra{height:54px;background:#1f2937;display:flex;align-items:center;padding:0 24px;color:#d1d5db;font-size:18px;border-bottom:1px solid #374151}
      .punto{width:14px;height:14px;border-radius:50%;margin-right:9px}.r{background:#ef4444}.a{background:#f59e0b}.v{background:#22c55e}
      .titulo{margin-left:16px}.terminal{padding:32px 40px;font-size:21px;line-height:1.55;white-space:pre-wrap}.comando{color:#93c5fd;margin-bottom:18px}.salida{color:#f3f4f6}
      .pie{position:fixed;right:28px;bottom:20px;color:#9ca3af;font:15px system-ui,sans-serif}
      </style></head><body>
      <div class="barra"><span class="punto r"></span><span class="punto a"></span><span class="punto v"></span><span class="titulo">PowerShell — Laboratorio 1</span></div>
      <div class="terminal"><div class="comando">PS ${escaparHtml(raiz)}&gt; php verificar.php</div><div class="salida">${escaparHtml(salida)}</div></div>
      <div class="pie">Evidencia generada el 05/10/2026</div>
      </body></html>`);
    await paginaTerminal.screenshot({
      path: path.join(evidencias, 'CAPTURA_VERIFICADOR.png'),
      fullPage: true,
    });
  } finally {
    if (navegador) await navegador.close();
    servidor.kill();
  }
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
