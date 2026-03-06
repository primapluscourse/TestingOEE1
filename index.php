<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OEE Digital Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Semua CSS tetap sama */
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body, #app-wrapper {
            height: 100%;
            width: 100%;
        }
        
        #app-wrapper {
            overflow-y: auto;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            transition: background 0.3s ease;
        }
        
        .theme-toggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .theme-toggle:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
        }
        
        .theme-toggle:active {
            transform: translateY(-2px) scale(1.02);
        }
        
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent-primary: #667eea;
            --accent-secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --border: #e2e8f0;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }
        
        [data-theme="dark"] {
            --bg-primary: #1e293b;
            --bg-secondary: #0f172a;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-primary: #818cf8;
            --accent-secondary: #a78bfa;
            --success: #34d399;
            --warning: #fbbf24;
            --danger: #f87171;
            --border: #334155;
            --gradient-start: #4c1d95;
            --gradient-end: #1e1b4b;
        }
        
        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }
        
        .card {
            background: var(--bg-primary);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .card-header {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        
        .card-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-header-right {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent-primary);
            background: rgba(102, 125, 234, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
            white-space: nowrap;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 125, 234, 0.3);
        }
        
        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2525;
        }
        
        .input-field {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(102, 125, 234, 0.1);
        }
        
        .label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .scorecard {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid var(--border);
            text-align: center;
        }
        
        .scorecard-value {
            font-size: 36px;
            font-weight: 900;
            margin: 8px 0;
        }
        
        .scorecard-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .scorecard.good .scorecard-value {
            color: var(--success);
        }
        
        .scorecard.bad .scorecard-value {
            color: var(--danger);
        }
        
        .nav-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            background: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .nav-tab {
            padding: 14px 28px;
            background: linear-gradient(135deg, rgba(102, 125, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 2px solid transparent;
            border-radius: 12px;
            font-weight: 700;
            color: var(--text-primary);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .nav-tab:hover {
            background: linear-gradient(135deg, rgba(102, 125, 234, 0.2), rgba(118, 75, 162, 0.2));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 125, 234, 0.3);
        }
        
        .nav-tab.active {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            border-color: var(--accent-primary);
            box-shadow: 0 6px 20px rgba(102, 125, 234, 0.4);
            transform: translateY(-2px);
        }
        
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -24px;
            padding: 0 24px;
            width: calc(100% + 48px);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }
        
        th {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 300px;
            min-width: 80px;
        }
        
        tr:hover {
            background: var(--bg-secondary);
        }
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        
        .toast.success {
            background: var(--success);
        }
        
        .toast.error {
            background: var(--danger);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .spinner {
            border: 3px solid rgba(102, 125, 234, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-primary);
            width: 40px;
            height: 40px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
            gap: 16px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg-secondary);
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid var(--border);
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .checkbox-item.active {
            background: rgba(102, 125, 234, 0.1);
            border-color: var(--accent-primary);
        }
        
        .checkbox-item.exceed {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning);
        }
        
        .filter-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 16px;
        }
        
        .chart-container-horizontal {
            position: relative;
            height: 400px;
            margin-top: 16px;
            padding-right: 60px;
        }
        
        .status-good {
            color: var(--success);
            font-weight: 600;
        }
        
        .status-bad {
            color: var(--danger);
            font-weight: 600;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        
        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .target-box {
            background: linear-gradient(135deg, rgba(102, 125, 234, 0.15), rgba(118, 75, 162, 0.15));
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 2px solid var(--accent-primary);
            box-shadow: 0 4px 12px rgba(102, 125, 234, 0.2);
        }
        
        .odt-exceed-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid var(--warning);
            border-radius: 8px;
            padding: 8px 12px;
            margin-top: 8px;
            font-size: 12px;
            color: var(--warning);
            font-weight: 600;
        }
        
        .wrap-text {
            white-space: normal !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 200px;
            min-width: 100px;
        }
        
        .nowrap {
            white-space: nowrap;
        }
        
        .ellipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .notes-cell {
            max-width: 250px !important;
            min-width: 150px !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
        }
        
        .odt-actual-time {
            width: 90px !important;
            padding: 4px 6px !important;
            font-size: 12px !important;
            text-align: center !important;
        }
        
        .disabled-for-zero-target {
            background-color: rgba(239, 239, 239, 0.3) !important;
            color: var(--text-secondary) !important;
            cursor: not-allowed !important;
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 16px;
            }
            .nav-tabs {
                padding: 12px;
            }
            .nav-tab {
                padding: 10px 16px;
                font-size: 13px;
            }
            .chart-container-horizontal {
                padding-right: 40px;
            }
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .clickable-row:hover {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary)) !important;
            color: white;
        }
        
        .clickable-row:hover td {
            color: white;
        }
        
        .clickable-row:hover .status-good,
        .clickable-row:hover .status-bad {
            color: white;
        }
        
        .active-filter-row {
            background: linear-gradient(135deg, rgba(102, 125, 234, 0.2), rgba(118, 75, 162, 0.2)) !important;
            border-left: 4px solid var(--accent-primary);
        }
        
        .exceed-warning {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            color: var(--danger);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body class="h-full">
    <div id="app-wrapper" class="h-full w-full"></div>
    <button class="theme-toggle" onclick="toggleTheme()" id="theme-toggle-btn" aria-label="Toggle dark mode"> 🌙 </button>

    <script src="assets/js/config.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script>
        // Inisialisasi aplikasi dengan konfigurasi dari server
        const CONFIG = window.__CONFIG__ || {};
        const SUPABASE_URL = CONFIG.SUPABASE_URL;
        const SUPABASE_ANON_KEY = CONFIG.SUPABASE_ANON_KEY;
        const MANAGEMENT_PASSWORD = CONFIG.MANAGEMENT_PASSWORD || 'admin123';
        
        // Lanjutkan dengan inisialisasi aplikasi
        const { createClient } = supabase;
        const db = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        
        // Fungsi-fungsi aplikasi (semua kode JavaScript yang panjang)
        // ... (semua function dari kode sebelumnya, tapi tanpa hardcoded credentials)
    </script>
</body>
</html>
