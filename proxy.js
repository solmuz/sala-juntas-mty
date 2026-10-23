const http  = require('http');
const https = require('https');

const N8N_URL     = 'https://n8n.srv923680.hstgr.cloud/webhook/sala-juntas';
const N8N_API_KEY = process.env.N8N_API_KEY;

const server = http.createServer((req, res) => {
  if (req.method !== 'GET' || !req.url.startsWith('/api.php')) {
    res.writeHead(404, {'Content-Type': 'application/json'});
    res.end(JSON.stringify({error: 'Not found'}));
    return;
  }
  res.setHeader('Access-Control-Allow-Origin', 'https://ivggroup.com.mx');
  res.setHeader('Content-Type', 'application/json');
  const options = new URL(N8N_URL);
  https.get({
    hostname: options.hostname,
    path:     options.pathname,
    headers:  { 'Accept': 'application/json', 'x-api-key': N8N_API_KEY }
  }, (n8nRes) => {
    let data = '';
    n8nRes.on('data', chunk => data += chunk);
    n8nRes.on('end', () => { res.writeHead(n8nRes.statusCode); res.end(data); });
  }).on('error', (e) => {
    res.writeHead(502, {'Content-Type': 'application/json'});
    res.end(JSON.stringify({error: 'Connection error'}));
  });
});

server.listen(3000, '0.0.0.0', () => {
  console.log('Proxy running on 0.0.0.0:3000');
});
