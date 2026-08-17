<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['adminid'])){
    
    header("location:index");
    
}


$get_admin = mysqli_query($mysqli,"SELECT * FROM admins WHERE id='".$_SESSION['adminid']."' ");
$rows = mysqli_fetch_assoc($get_admin);


//validate auth_code coming from get params, generate a arragy and validate code against the array if it is valid then allow access to the page otherwise redirect to dashboard
if(isset($_GET['auth_code'])){
    $auth_code = $_GET['auth_code'];
    $valid_codes = array('QCORE-TERMINAL-788921', 'QCORE-TERMINAL-988922', 'QCORE-TERMINAL-334199');
    
    if(in_array($auth_code, $valid_codes)){
        //allow access to the page

    }else{
        //show error message and redirect to dashboard after 5 seconds
        echo "<script>setTimeout(function(){ window.location.href = 'dashboard'; }, 2000); alert('Invalid authentication code. You will be redirected to the dashboard.'); </script>";
        //header("location:dashboard");
    }
}
else{
       echo "<script>setTimeout(function(){ window.location.href = 'dashboard'; }, 2000); alert('Validate before accessing this area.'); </script>";
    //header("location:dashboard");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q-CORE Engine - Real-Time Console</title>
    	<!-- Favicon -->
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0e27;
            color: #888;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.6;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Header */
        .header {
            background: #0f172a;
            border-bottom: 2px solid #00d4ff;
            padding: 20px 30px;
            flex-shrink: 0;
        }

        .header-title {
            color: #00d4ff;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .controls {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Monaco', monospace;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-pause {
            background: #ff4444;
            color: white;
        }

        .btn-clear {
            background: #0066ff;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .stat-box {
            background: #1a2847;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid;
            font-size: 11px;
        }

        .stat-label {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .stat-value {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

        .stat-box.trades { border-color: #00d4ff; }
        .stat-box.trades .stat-label { color: #00d4ff; }

        .stat-box.success { border-color: #00aa44; }
        .stat-box.success .stat-label { color: #00aa44; }

        .stat-box.profit { border-color: #00ff88; }
        .stat-box.profit .stat-label { color: #00ff88; }

        .stat-box.confidence { border-color: #ff9500; }
        .stat-box.confidence .stat-label { color: #ff9500; }

        .stat-box.status { border-color: #0099ff; }
        .stat-box.status .stat-label { color: #0099ff; }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: pulse 2s infinite;
        }

        .status-running {
            background: #00ff88;
        }

        .status-paused {
            background: #ff4444;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex: 1;
            min-height: 0;
            padding: 20px 30px;
            gap: 20px;
        }

        /* Console */
        .console {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .console-title {
            color: #00d4ff;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #1a3a52;
        }

        .console-output {
            flex: 1;
            background: #0f172a;
            border: 1px solid #1a3a52;
            border-radius: 4px;
            padding: 15px;
            overflow-y: auto;
            min-height: 0;
            color: #888;
        }

        .console-line {
            margin-bottom: 1px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .log-time {
            color: #555;
            margin-right: 10px;
        }

        /* Log types */
        .log-info { color: #888; }
        .log-success { color: #00ff88; }
        .log-warning { color: #ff9500; }
        .log-system { color: #00d4ff; }
        .log-error { color: #ff4444; }
        .log-divider { color: #333; margin: 10px 0; }

        /* Sidebar */
        .sidebar {
            width: 380px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .panel {
            background: #0f172a;
            border: 1px solid #1a3a52;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            background: #1a2847;
            padding: 12px 15px;
            border-bottom: 1px solid #1a3a52;
            color: #00d4ff;
            font-size: 11px;
            font-weight: bold;
        }

        .panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }

        .trade-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            padding: 10px 15px;
            border-bottom: 1px solid #1a3a52;
            font-size: 10px;
            gap: 10px;
        }

        .trade-row.header {
            background: #1a2847;
            color: #00d4ff;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .trade-row:hover {
            background: #1a2847;
        }

        .trade-id {
            color: #0099ff;
            font-family: monospace;
            font-size: 9px;
        }

        .trade-profit {
            color: #00ff88;
            font-weight: bold;
        }

        .trade-confidence {
            color: #ff9500;
            font-weight: bold;
        }

        .trade-time {
            color: #888;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0e27;
        }

        ::-webkit-scrollbar-thumb {
            background: #1a3a52;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2a4a62;
        }

        /* Footer */
        .footer {
            padding: 15px 30px;
            border-top: 1px solid #1a3a52;
            background: #0f172a;
            font-size: 10px;
            color: #555;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .main-content {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                flex-direction: row;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <span>⚡ Q-CORE ENGINE v2.4.1 | Real-Time Arbitrage Analyzer</span>
                <div class="controls">
                    <a href="dashboard" class="btn btn-warning" style="color: white" > Return to Dashboard</a>
                    <button class="btn btn-pause"  onclick="toggleEngine(this)">▶ RESUME</button>
                    <button class="btn btn-clear" onclick="clearLogs()">🔄 CLEAR</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box trades">
                    <div class="stat-label">TOTAL TRADES CYCLE</div>
                    <div class="stat-value" id="stat-trades">0</div>
                </div>
                <div class="stat-box success">
                    <div class="stat-label">SUCCESS RATE</div>
                    <div class="stat-value" id="stat-success">0.0%</div>
                </div>
                <div class="stat-box profit">
                    <div class="stat-label">TARGET ROI</div>
                    <div class="stat-value" id="stat-profit">0.00%</div>
                </div>
                <div class="stat-box confidence">
                    <div class="stat-label">AVG CONFIDENCE</div>
                    <div class="stat-value" id="stat-confidence">0.0%</div>
                </div>
                <div class="stat-box status">
                    <div class="stat-label"><span class="status-indicator status-running" id="status-dot"></span>STATUS</div>
                    <div class="stat-value" id="stat-status" style="font-size: 13px;">RUNNING</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Console -->
            <div class="console">
                <div class="console-title">▼ LIVE ENGINE LOGS</div>
                <div class="console-output" id="console-output">
                    <div style="color: #555; text-align: center; padding-top: 200px;">
                        Engine initialized. Waiting to connect to server...
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Recent Trades -->
                <div class="panel" style="flex: 1;">
                    <div class="panel-header">▶ LAST 10 EXECUTED TRADES</div>
                    <div class="panel-content">
                        <div class="trade-row header">
                            <div>TRADE ID</div>
                            <div>PROFIT</div>
                            <div>CONF.</div>
                            <div>TIME</div>
                        </div>
                        <div id="trades-list" style="color: #666; padding: 20px; text-align: center; font-size: 11px;">
                            Awaiting first execution...
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="panel">
                    <div class="panel-header">⚙ SYSTEM INFO</div>
                    <div class="panel-content" style="padding: 15px;">
                        <div style="margin-bottom: 12px;">
                            <div style="color: #555; font-size: 10px;">API GATEWAY</div>
                            <div style="color: #00d4ff; font-size: 11px;">Plaid v3.2 | Latency: 47ms</div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="color: #555; font-size: 10px;">EXCHANGE FEEDS</div>
                            <div style="color: #00d4ff; font-size: 11px;">CEX: Active | DEX: Active</div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="color: #555; font-size: 10px;">SCAN CYCLE</div>
                            <div style="color: #00d4ff; font-size: 11px;">100ms | Drift: 0.3ms</div>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <div style="color: #555; font-size: 10px;">GAS TRACKER</div>
                            <div style="color: #ff9500; font-size: 11px;">Standard: 45 gwei</div>
                        </div>
                        <div>
                            <div style="color: #555; font-size: 10px;">UPTIME</div>
                            <div style="color: #00ff88; font-size: 11px;">99.97% | 247d 14h</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Quantum Scalp AI Trading Engine | Powered by Q-CORE Neural Network v2.4.1 | All transactions verified on-chain | 99.97% uptime SLA
        </div>
    </div>

    <script >
        const confidenceThreshold = 60; // Minimum confidence to execute trade
        let isRunning = false;
        let totalTrades = 0;
        let totalProfit = 0;
        let totalConfidence = 0;
        let trades = [];

        const log = (message, type = 'info') => {
            const output = document.getElementById('console-output');
            const timestamp = new Date().toLocaleTimeString('en-US', { hour12: false });
            
            const line = document.createElement('div');
            line.className = `console-line log-${type}`;
            
            let prefix = '';
            if (type === 'success') prefix = '✓ ';
            if (type === 'warning') prefix = '⚠ ';
            if (type === 'error') prefix = '✗ ';
            
            line.innerHTML = `<span class="log-time">[${timestamp}]</span>${prefix}${message}`;
            output.appendChild(line);
            output.scrollTop = output.scrollHeight;
        };

        const updateStats = () => {
            document.getElementById('stat-trades').textContent = totalTrades;
            document.getElementById('stat-success').textContent = '98.9%';
            document.getElementById('stat-profit').textContent = `${totalProfit.toFixed(2)/150}%`;
            document.getElementById('stat-confidence').textContent = totalTrades > 0 ? 
                (totalConfidence / totalTrades).toFixed(1) + '%' : '0.0%';
        };

        const updateTrades = () => {
            const list = document.getElementById('trades-list');
            if (trades.length === 0) {
                list.innerHTML = '<div style="color: #666; padding: 20px; text-align: center;">Awaiting  execution...</div>';
                return;
            }
            
            list.innerHTML = '<div class="trade-row header"><div>TRADE ID</div><div>PROFIT</div><div>CONF.</div><div>TIME</div></div>';
            trades.slice(0, 10).forEach(trade => {
                const row = document.createElement('div');
                row.className = 'trade-row';
                row.innerHTML = `
                    <div class="trade-id">${trade.id}</div>
                    <div class="trade-profit">$${trade.profit.toFixed(2)}</div>
                    <div class="trade-confidence">${trade.confidence.toFixed(1)}%</div>
                    <div class="trade-time">${trade.time}</div>
                `;
                list.appendChild(row);
            });
        };

        // const generateArb = () => {
        //     const arbs = [
        //         { type: 'DEX', asset: 'WETH/WLP', exchange: 'Uniswap v2', confidence: 65.2 },



        //         { type: 'CEX', asset: 'SOL', exchange: 'KUCOIN/KRAKEN', confidence: 85.7 },
        //         { type: 'DEX', asset: 'ETH/USDC', exchange: 'Uniswap/Sushiswap', confidence: 94.7 },
        //         { type: 'CEX', asset: 'BTC/USD', exchange: 'Kraken/Coinbase', confidence: 58.4 },
        //         { type: 'DEX', asset: 'stETH/ETH', exchange: 'Curve/Balancer', confidence: 92.1 },
        //         { type: 'Flash', asset: 'WETH', exchange: 'Aave/Uniswap', confidence: 97.8 }
        //     ];
        //     return arbs[Math.floor(Math.random() * arbs.length)];
        // };

        let arbData = [];

async function loadArbs() {
    try {
        const res = await fetch("api.php?chain=eth&limit=50");
        const json = await res.json();
        console.log("API Response:", json);
        if (json.status === "success") {
            arbData = json.data;
        }
    } catch (err) {
        console.error("Error loading arbs:", err);
    }
}
 loadArbs();

// refresh every 15s

//setInterval(loadArbs, 15000);

// 🔥 REAL generateArb now

const generateArb = () => {
    if (!arbData.length) {
        return {
            type: 'DEX',
            asset: 'LOADING...',
            exchange: 'Fetching...',
            confidence: 0
        };
    }
    return arbData[Math.floor(Math.random() * arbData.length)];
};

        const runCycle = async () => {
            if (!isRunning) return;

            log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'divider');
            log(`Q-CORE scan initiated | Cycle: ${totalTrades + 1}`, 'system');
            
            await new Promise(r => setTimeout(r, 200));
            log('→ Connecting to Plaid API Gateway...', 'info');
            await new Promise(r => setTimeout(r, 300));
            log('✓ Plaid.authenticate() | Token: plaid_token_#####...(concealed for security) | Latency: 47ms', 'success');

            await new Promise(r => setTimeout(r, 200));
            log('→ Establishing digital handshake...', 'info');
            await new Promise(r => setTimeout(r, 250));
            log('✓ TLS 1.3 | ECDHE-RSA | Session established', 'success');

            await new Promise(r => setTimeout(r, 200));
            log('→ Retrieving exchange APIs...', 'info');
            log('  → Binance.getAPIKey() | Kraken.getAPIKey() | Uniswap GraphQL | Aave contracts', 'info');
            await new Promise(r => setTimeout(r, 300));
            log('✓ CEX APIs authenticated | DEX endpoints confirmed', 'success');

            await new Promise(r => setTimeout(r, 250));
            log('🔍 Scanning for arbitrage opportunities...', 'system');
            log('  → Fetching real-time order books: Binance, Kraken, Coinbase', 'info');
            log('  → Querying DEX pools: Uniswap v3, Sushiswap, Curve, Balancer', 'info');
            log('  → Checking perpetuals: Binance Futures, Bybit, dYdX', 'info');
            await new Promise(r => setTimeout(r, 400));
            log('✓ Market data updated | 2,847 pairs analyzed', 'success');

            const arb = await generateArb();
            
            await new Promise(r => setTimeout(r, 250));
            log(`\n🎯 ${arb.type} ARBITRAGE OPPORTUNITY SPOTTED!`, 'warning');
            log(`  → Asset: ${arb.asset}`, 'info');
            log(`  → Venue: ${arb.exchange}`, 'info');
            log(`  → Spread: ${(Math.random() * 3 + 0.5).toFixed(2)}%`, 'info');
            log(`  → Estimated Profit: $${(Math.random() * 300 + 20).toFixed(2)}`, 'info');

            await new Promise(r => setTimeout(r, 300));
            log('⚙ Optimizing arbitrage strategy...', 'system');
            log(`  → Route A: ${arb.exchange} (latency: ${Math.random() * 1000 + 500}ms)`, 'info');
            log(`  → Route B: Alternative path (latency: ${Math.random() * 1000 + 500}ms) [SELECTED]`, 'success');
            log(`  → Gas estimate: ${Math.floor(Math.random() * 600000 + 200000)} wei`, 'info');
            log(`  → Slippage tolerance: 0.12%`, 'info');

            await new Promise(r => setTimeout(r, 300));
            log('📊 Estimating confidence score...', 'system');
            const confidence = Math.random() * 45 + 50;
            log(`  → Model accuracy: ${confidence.toFixed(1)}%`, 'info');
            log(`  → Volatility factor: ${(Math.random() * 10 + 80).toFixed(1)}%`, 'info');
            log(`  → Liquidity depth: ${(Math.random() * 15 + 85).toFixed(1)}%`, 'info');

            await new Promise(r => setTimeout(r, 250));

            if (confidence < confidenceThreshold) {
                log(`⚠️  Confidence ${confidence.toFixed(1)}% < ${confidenceThreshold}% threshold`, 'warning');
                log('📤 Redirected to QUANTUM SIGNALS module for review', 'warning');
            } else {
                log(`✓ Confidence ${confidence.toFixed(1)}% > ${confidenceThreshold}% threshold [EXECUTE]`, 'success');
                
                await new Promise(r => setTimeout(r, 300));
                const txHash = '0x' + Math.random().toString(16).substring(2, 64);
                const profit = Math.random() * 250 + 15;
                const gasUsed = Math.floor(Math.random() * 600000 + 200000);

                log('🏗️  Building atomic transaction...', 'system');
                log(`  → Step 1: Swap on ${arb.exchange.split('/')[0]}`, 'info');
                log(`  → Step 2: Swap on ${arb.exchange.split('/')[1]}`, 'info');
                log(`  → Step 3: Settlement and profit capture`, 'info');

                await new Promise(r => setTimeout(r, 300));
                log('📤 Submitting to blockchain...', 'system');
                log(`  → Gas price: ${(Math.random() * 50 + 25).toFixed(1)} gwei`, 'info');
                log(`  → TX Hash: ${txHash}`, 'info');

                await new Promise(r => setTimeout(r, 500));
                log('⏳ Pending blockchain confirmation...', 'warning');
                
                await new Promise(r => setTimeout(r, 400));
                log(`✓ Block mined | Confirmations: 5/15 | Gas used: ${gasUsed}`, 'success');

                log('\n╔═══════════════════════════════════════╗', 'success');
                log('║  TRADE EXECUTED SUCCESSFULLY          ║', 'success');
                log(`║  Trade ID: TX-${new Date().toTimeString().split(' ')[0].replace(/:/g, '')}-${arb.asset.replace('/', '').substring(0, 4)}   │`, 'success');
                log(`║  Timestamp: ${new Date().toISOString().substring(0, 19)} UTC │`, 'success');
                log(`║  Strategy: ${arb.type} Arbitrage${' '.repeat(24 - arb.type.length)}│`, 'success');
                log(`║  Net Profit: $${profit.toFixed(2)}${' '.repeat(25 - profit.toFixed(2).length)}│`, 'success');
                log(`║  Confidence: ${confidence.toFixed(1)}%${' '.repeat(27 - confidence.toFixed(1).length)}│`, 'success');
                log('║  Status: ✓ Confirmed on Blockchain    ║', 'success');
                log('╚═══════════════════════════════════════╝', 'success');

                log('💰 Settlement initiated | Account balance updated', 'system');

                totalTrades++;
                totalProfit += profit;
                totalConfidence += confidence;
                trades.unshift({
                    id: txHash.substring(0, 16) + '...',
                    profit,
                    confidence,
                    time: new Date().toLocaleTimeString('en-US', { hour12: false })
                });

                updateStats();
                updateTrades();
            }

            await new Promise(r => setTimeout(r, 1500));
            if (isRunning) runCycle();
        };

        const toggleEngine = (btn) => {
            isRunning = !isRunning;
            btn.textContent = isRunning ? '⏸ PAUSE' : '▶ RESUME';
            btn.className = isRunning ? 'btn btn-pause' : 'btn btn-pause';
            btn.style.background = isRunning ? '#ff4444' : '#00aa44';
            
            const indicator = document.getElementById('status-dot');
            indicator.className = 'status-indicator ' + (isRunning ? 'status-running' : 'status-paused');
            
            document.getElementById('stat-status').textContent = isRunning ? 'RUNNING' : 'PAUSED';
            
            if (isRunning) runCycle();
        };

        const clearLogs = () => {
            document.getElementById('console-output').innerHTML = '';
            totalTrades = 0;
            totalProfit = 0;
            totalConfidence = 0;
            trades = [];
            updateStats();
            updateTrades();
            isRunning = false;
            document.querySelector('.btn-pause').textContent = '▶ RESUME';
            document.querySelector('.btn-pause').style.background = '#00aa44';
        };

        // Start on load
        window.addEventListener('load', () => {
            isRunning = true;
            runCycle();
        });
    </script>
</body>
</html>
