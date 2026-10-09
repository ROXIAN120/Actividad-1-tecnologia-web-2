'use strict';

const { chromium } = require('playwright');
const { spawn, spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const raiz = path.resolve(__dirname, '..');
const evidencias = path.join(raiz, 'evidencias');
const chrome = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const urlBase = 'http://127.0.0.1:8125';

function ejecutar(archivo) {
  const resultado = spawnSync('php', [archivo], {
    cwd: raiz,
    encoding: 'utf8',
    windowsHide: true,
  });
  const salida = `${resultado.stdout || ''}${resultado.stderr || ''}`.trim();
  if (resultado.status !== 0) {
    throw new Error(`${archivo} terminó con error.\n${salida}`);
  }
  return salida;
}

function escaparHtml(texto) {
  return texto.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');
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

  const verificador = ejecutar('verificar.php');
  if (!verificador.includes('Resultado: 12 de 12 comprobaciones')) {
    throw new Error(`El verificador oficial no alcanzó 12 de 12.\n${verificador}`);
  }
  const retos = ejecutar('verificar_retos.php');
  const pruebas = ejecutar('pruebas.php');
  const sinStrict = ejecutar('pruebas_sin_strict.php');

  fs.writeFileSync(path.join(evidencias, 'RESULTADO_VERIFICADOR.txt'), `${verificador}\n`, 'utf8');
  fs.writeFileSync(path.join(evidencias, 'RESULTADO_RETOS.txt'), `${retos}\n`, 'utf8');
  fs.writeFileSync(path.join(evidencias, 'RESULTADOS_PRUEBAS.txt'), `${pruebas}\n\n${sinStrict}\n`, 'utf8');

  const servidor = spawn('php', ['-S', '127.0.0.1:8125', '-t', 'public'], {
    cwd: raiz,
    windowsHide: true,
    stdio: 'ignore',
  });

  let navegador;
  try {
    await esperarServidor(`${urlBase}/reporte.php`);
    navegador = await chromium.launch({ executablePath: chrome, headless: true });

    const reporte = await navegador.newPage({ viewport: { width: 1440, height: 1000 }, deviceScaleFactor: 1 });
    await reporte.goto(`${urlBase}/reporte.php`, { waitUntil: 'networkidle' });
    await reporte.screenshot({ path: path.join(evidencias, 'CAPTURA_REPORTE.png'), fullPage: true });

    const filtro = await navegador.newPage({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
    await filtro.goto(`${urlBase}/reporte.php?categoria=%3Cb%3Ehola%3C%2Fb%3E`, { waitUntil: 'networkidle' });
    await filtro.screenshot({ path: path.join(evidencias, 'CAPTURA_FILTRO_SEGURO.png'), fullPage: true });

    const terminal = await navegador.newPage({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
    await terminal.setContent(`<!doctype html><html lang="es"><head><meta charset="utf-8"><style>
      *{box-sizing:border-box} body{margin:0;background:#111827;color:#e5e7eb;font-family:Consolas,"Cascadia Mono",monospace}
      .barra{height:54px;background:#1f2937;display:flex;align-items:center;padding:0 24px;color:#d1d5db;font-size:18px;border-bottom:1px solid #374151}
      .punto{width:14px;height:14px;border-radius:50%;margin-right:9px}.r{background:#ef4444}.a{background:#f59e0b}.v{background:#22c55e}
      .titulo{margin-left:16px}.terminal{padding:30px 40px;font-size:20px;line-height:1.48;white-space:pre-wrap}.comando{color:#93c5fd;margin-bottom:16px}.salida{color:#f3f4f6}
      .pie{position:fixed;right:28px;bottom:20px;color:#9ca3af;font:15px system-ui,sans-serif}
    </style></head><body>
      <div class="barra"><span class="punto r"></span><span class="punto a"></span><span class="punto v"></span><span class="titulo">PowerShell — Laboratorio 2</span></div>
      <div class="terminal"><div class="comando">PS ${escaparHtml(raiz)}&gt; php verificar.php</div><div class="salida">${escaparHtml(verificador)}</div></div>
      <div class="pie">Evidencia generada el 09/10/2026</div>
    </body></html>`);
    await terminal.screenshot({ path: path.join(evidencias, 'CAPTURA_VERIFICADOR.png'), fullPage: true });
  } finally {
    if (navegador) await navegador.close();
    servidor.kill();
  }
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
