import mysql from 'mysql2/promise';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const envPath = path.resolve(__dirname, '../.env');

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

async function runCli() {
    console.log('---------------------------------------------------------');
    console.log('📦 SENJA INVENTORY MANAGEMENT SYSTEM - NODE.JS CLI TOOL 📦');
    console.log('---------------------------------------------------------');
    
    const env = getEnvConfig();
    try {
        const connection = await mysql.createConnection(env);
        console.log(`✅ Connected to MySQL Database [${env.database}] via Node.js ${process.version}`);
        
        const [products] = await connection.query('SELECT name, sku, current_stock, minimum_stock, unit, purchase_price FROM products LIMIT 10');
        console.log('\n📋 Top Inventory Products Overview:');
        console.table(products.map(p => ({
            'SKU': p.sku,
            'Product Name': p.name,
            'Current Stock': `${p.current_stock} ${p.unit}`,
            'Min Stock': `${p.minimum_stock} ${p.unit}`,
            'Price (Rp)': `Rp ${Number(p.purchase_price).toLocaleString('id-ID')}`,
            'Status': Number(p.current_stock) <= Number(p.minimum_stock) ? '⚠️ LOW STOCK' : 'OK'
        })));
        
        const [lowStock] = await connection.query('SELECT COUNT(*) as count FROM products WHERE current_stock <= minimum_stock');
        console.log(`\n🚨 Alert Summary: ${lowStock[0].count} products currently under minimum stock threshold.`);
        
        await connection.end();
        console.log('\n---------------------------------------------------------');
        console.log('Execution completed successfully.');
    } catch (err) {
        console.error('❌ Node.js CLI Error:', err.message);
    }
}

runCli();
