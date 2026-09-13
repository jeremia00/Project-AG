import http from 'http';
import mysql from 'mysql2/promise';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const envPath = path.resolve(__dirname, '../../.env');

function getEnvConfig() {
    const config = {
        host: '127.0.0.1',
        port: 3306,
        database: 'senja_inventory',
        user: 'root',
        password: ''
    };
    if (fs.existsSync(envPath)) {
        const content = fs.readFileSync(envPath, 'utf8');
        content.split('\n').forEach(line => {
            if (line.startsWith('DB_HOST=')) config.host = line.split('=')[1].trim();
            if (line.startsWith('DB_PORT=')) config.port = parseInt(line.split('=')[1].trim());
            if (line.startsWith('DB_DATABASE=')) config.database = line.split('=')[1].trim();
            if (line.startsWith('DB_USERNAME=')) config.user = line.split('=')[1].trim();
            if (line.startsWith('DB_PASSWORD=')) config.password = line.split('=')[1].trim();
        });
    }
    return config;
}

const PORT = 3000;
const envConfig = getEnvConfig();

const server = http.createServer(async (req, res) => {
    res.setHeader('Content-Type', 'application/json');
    res.setHeader('Access-Control-Allow-Origin', '*');

    try {
        const connection = await mysql.createConnection(envConfig);

        if (req.url === '/api/node/status') {
            const [products] = await connection.query('SELECT COUNT(*) as total FROM products');
            const [suppliers] = await connection.query('SELECT COUNT(*) as total FROM suppliers');
            const [openPOs] = await connection.query("SELECT COUNT(*) as total FROM purchase_orders WHERE status IN ('Draft', 'Ordered', 'Partially Received')");
            const [lowStock] = await connection.query('SELECT COUNT(*) as total FROM products WHERE current_stock <= minimum_stock');

            res.writeHead(200);
            res.end(JSON.stringify({
                status: 'online',
                service: 'Senja Inventory Node.js API Service',
                engine: 'Node.js ' + process.version,
                database: envConfig.database,
                timestamp: new Date().toISOString(),
                metrics: {
                    totalProducts: products[0].total,
                    totalSuppliers: suppliers[0].total,
                    openPurchaseOrders: openPOs[0].total,
                    lowStockAlerts: lowStock[0].total
                }
            }, null, 2));
        } else if (req.url === '/api/node/low-stock') {
            const [rows] = await connection.query('SELECT id, name, sku, category, unit, current_stock, minimum_stock FROM products WHERE current_stock <= minimum_stock');
            res.writeHead(200);
            res.end(JSON.stringify({
                service: 'Node.js Low Stock Real-Time Alert',
                count: rows.length,
                products: rows
            }, null, 2));
        } else if (req.url === '/api/node/inventory-summary') {
            const [products] = await connection.query('SELECT id, name, sku, unit, current_stock, purchase_price, (current_stock * purchase_price) as total_valuation FROM products');
            const totalValuation = products.reduce((acc, item) => acc + parseFloat(item.total_valuation || 0), 0);
            
            res.writeHead(200);
            res.end(JSON.stringify({
                service: 'Node.js Inventory Valuation Summary',
                totalProducts: products.length,
                totalInventoryValuationRp: totalValuation,
                items: products
            }, null, 2));
        } else {
            res.writeHead(404);
            res.end(JSON.stringify({
                error: 'Endpoint not found.',
                availableEndpoints: [
                    '/api/node/status',
                    '/api/node/low-stock',
                    '/api/node/inventory-summary'
                ]
            }, null, 2));
        }

        await connection.end();
    } catch (err) {
        res.writeHead(500);
        res.end(JSON.stringify({ error: 'Node.js DB Error: ' + err.message }));
    }
});

server.listen(PORT, () => {
    console.log(`🚀 Node.js Inventory API Microservice running at http://localhost:${PORT}`);
});
