import express from 'express';
import fetch from 'node-fetch';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = 3000;

// Servir arquivos estáticos (HTML, CSS, JS, assets)
app.use(express.static(__dirname));

// Rota para ler os sites do arquivo sites.txt
app.get('/api/sites', (req, res) => {
  const filePath = path.join(__dirname, 'sites.txt'); // sites.txt na raiz
  fs.readFile(filePath, 'utf8', (err, data) => {
    if (err) return res.status(500).json({ error: err.message });

    // Cada linha não vazia é um site
    const sites = data.split('\n').filter(Boolean);
    res.json(sites);
  });
});

// Rota para consultar Wayback Machine
app.get('/api/wayback', async (req, res) => {
  try {
    const url = req.query.url;
    if (!url) return res.status(400).json({ error: 'url query required' });

    const limit = parseInt(req.query.limit) || 200;
    const cdx = `https://web.archive.org/cdx/search/cdx?url=${encodeURIComponent(url)}&output=json&fl=timestamp,original&filter=statuscode:200&limit=${limit}&collapse=timestamp:8`;
    const r = await fetch(cdx);
    const data = await r.json();

    const snapshots = [];
    for (let i = 0; i < data.length; i++) {
      const row = data[i];
      if (i === 0 && row[0].toLowerCase().includes('timestamp')) continue;
      snapshots.push({ timestamp: row[0], original: row[1] });
    }

    res.json({ snapshots });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});
app.listen(PORT, "0.0.0.0", () => {
  console.log(`Servidor rodando em http://0.0.0.0:${PORT}`);
});

