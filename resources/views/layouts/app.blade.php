<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flutter Wiki') — قاعدة المعرفة</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" id="hljs-dark">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-light.min.css" id="hljs-light" disabled>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/dart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/yaml.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
            mermaid.initialize({ startOnLoad: false, theme: 'dark' }); // False because we init manually after transform

            document.addEventListener('DOMContentLoaded', () => {
                // Transform mermaid code blocks to div.mermaid
                document.querySelectorAll('.code-block-wrapper').forEach(wrapper => {
                    const lang = wrapper.querySelector('.code-lang');
                    if (lang && lang.textContent.trim().toLowerCase() === 'mermaid') {
                        const code = wrapper.querySelector('code').textContent;
                        const div = document.createElement('div');
                        div.className = 'mermaid';
                        div.textContent = code;
                        wrapper.replaceWith(div);
                    }
                });
                
                // Also check for raw pre code blocks
                document.querySelectorAll('pre code.language-mermaid').forEach(block => {
                    const div = document.createElement('div');
                    div.className = 'mermaid';
                    div.textContent = block.textContent;
                    block.closest('pre').replaceWith(div);
                });

                mermaid.run();
            });
    </script>

    <style>
        /* ─── Theme Variables ─── */
        [data-theme="dark"] {
            --bg-primary: #0f0f1a;
            --bg-secondary: #1a1a2e;
            --bg-card: #16213e;
            --bg-card-hover: #1a2744;
            --accent: #6c63ff;
            --accent-light: #8b83ff;
            --accent-glow: rgba(108, 99, 255, 0.3);
            --text-primary: #e8e8f0;
            --text-secondary: #a0a0b8;
            --text-muted: #6b6b85;
            --border: #2a2a45;
            --success: #4ade80;
            --warning: #fbbf24;
            --danger: #f87171;
            --code-bg: #282c34;
            --info: #38bdf8;
        }

        [data-theme="light"] {
            --bg-primary: #f8f9fc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-card-hover: #f3f4f8;
            --accent: #6c63ff;
            --accent-light: #5b54e6;
            --accent-glow: rgba(108, 99, 255, 0.15);
            --text-primary: #1a1a2e;
            --text-secondary: #4a4a6a;
            --text-muted: #8b8ba8;
            --border: #e2e4ec;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --code-bg: #f5f5f5;
            --info: #0284c7;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.8;
            transition: background 0.3s, color 0.3s;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed;
            right: 0; top: 0;
            width: 270px;
            height: 100vh;
            background: var(--bg-secondary);
            border-left: 1px solid var(--border);
            padding: 20px 16px;
            overflow-y: auto;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: background 0.3s, border-color 0.3s;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #4f46e5);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar-logo h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .theme-toggle {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 8px;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            transition: all 0.2s;
        }
        .theme-toggle:hover { border-color: var(--accent); }

        .sidebar-search {
            position: relative;
            margin-bottom: 20px;
        }

        .sidebar-search input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .sidebar-search input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .sidebar-search .search-icon {
            position: absolute;
            left: 10px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .sidebar-nav { flex: 1; }

        .domain-switcher {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        .domain-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px;
            border-radius: 10px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            min-width: 50px;
            flex: 1 0 45%; /* Grow and take up roughly half width */
            transition: all 0.2s;
        }
        .domain-item:hover, .domain-item.active {
            border-color: var(--domain-color);
            color: var(--domain-color);
            background: color-mix(in srgb, var(--domain-color) 10%, transparent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--domain-color) 20%, transparent);
        }
        .domain-icon { font-size: 1.2rem; }

        .block-editor.drag-over {
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.1);
            border-style: dashed;
        }

        .sidebar-nav-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin: 16px 0 8px;
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--accent-glow);
            color: var(--accent-light);
        }

        .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        .nav-badge {
            margin-right: auto;
            background: var(--danger);
            color: #fff;
            font-size: 0.7rem;
            padding: 1px 7px;
            border-radius: 10px;
            font-weight: 700;
        }

        .sidebar-tags { margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); }

        .tag-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            text-decoration: none;
            margin: 0 0 5px 5px;
            transition: all 0.2s;
            border: 1px solid var(--tag-color, var(--border));
            color: var(--tag-color, var(--text-secondary));
            background: color-mix(in srgb, var(--tag-color, transparent), transparent 90%);
        }
        .tag-pill:hover {
            transform: translateY(-1px);
            background: var(--tag-color, var(--accent));
            color: #fff;
            box-shadow: 0 4px 12px color-mix(in srgb, var(--tag-color, transparent), transparent 60%);
            border-color: var(--tag-color, var(--accent));
        }

        .category-tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            background: var(--accent-glow);
            color: var(--accent-light);
            text-decoration: none;
            margin: 0 0 5px 5px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .category-tag:hover, .category-tag.active {
            background: var(--accent);
            color: #fff;
        }

        /* ─── Main Content ─── */
        .main-content {
            margin-right: 270px;
            padding: 32px 40px;
            min-height: 100vh;
        }

        /* ─── Alerts ─── */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            animation: slideIn 0.3s ease;
        }
        .alert-success {
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.3);
            color: var(--success);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── Page Headers ─── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .page-header h2 { font-size: 1.5rem; font-weight: 700; }
        .page-header .subtitle { color: var(--text-muted); font-size: 0.85rem; margin-top: 2px; }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border: none;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #4f46e5);
            color: #fff;
            box-shadow: 0 4px 15px var(--accent-glow);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px var(--accent-glow); }
        .btn-secondary { background: var(--bg-card); color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--bg-card-hover); color: var(--text-primary); }
        .btn-danger { background: rgba(248,113,113,0.15); color: var(--danger); border: 1px solid rgba(248,113,113,0.3); }
        .btn-danger:hover { background: rgba(248,113,113,0.25); }
        .btn-sm { padding: 5px 12px; font-size: 0.8rem; }
        .btn-icon { padding: 6px 8px; font-size: 1rem; }
        .btn-warning { background: rgba(251,191,36,0.15); color: var(--warning); border: 1px solid rgba(251,191,36,0.3); }

        .btn-group { display: flex; gap: 6px; flex-wrap: wrap; }

        /* ─── Stats ─── */
        .stats-bar { display: flex; gap: 16px; margin-bottom: 28px; flex-wrap: wrap; }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            min-width: 120px;
        }
        .stat-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .stat-number { font-size: 1.2rem; font-weight: 700; }
        .stat-label { font-size: 0.7rem; color: var(--text-muted); }

        /* ─── Topic Cards ─── */
        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }
        .topic-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
        }
        .topic-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 3px; height: 100%;
            background: linear-gradient(180deg, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .topic-card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .topic-card:hover::before { opacity: 1; }

        .topic-card .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .topic-card h3 { font-size: 1.05rem; font-weight: 700; }
        .pin-star { color: var(--warning); font-size: 0.9rem; }

        .card-body-preview {
            font-size: 0.82rem;
            color: var(--text-secondary);
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid var(--border);
            gap: 6px;
            flex-wrap: wrap;
        }
        .card-tags { display: flex; gap: 4px; flex-wrap: wrap; }
        .card-tag {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.68rem;
            font-weight: 600;
            color: #fff;
        }
        .card-date { font-size: 0.72rem; color: var(--text-muted); }

        /* ─── Topic Content ─── */
        .topic-content {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 32px;
        }
        .topic-body { font-size: 0.95rem; line-height: 2; color: var(--text-secondary); }
        .topic-body h1, .topic-body h2, .topic-body h3 {
            color: var(--text-primary);
            margin: 24px 0 12px;
            font-weight: 700;
        }
        .topic-body h2 { font-size: 1.3rem; border-bottom: 1px solid var(--border); padding-bottom: 8px; }
        .topic-body h3 { font-size: 1.1rem; }
        .topic-body p { margin-bottom: 12px; }
        .topic-body ul, .topic-body ol { padding-right: 24px; margin-bottom: 12px; }
        .topic-body li { margin-bottom: 4px; }
        .topic-body strong { color: var(--text-primary); }
        .topic-body table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .topic-body th, .topic-body td {
            padding: 10px 14px;
            border: 1px solid var(--border);
            text-align: right;
        }
        .topic-body th { background: var(--bg-secondary); font-weight: 600; }
        .topic-body blockquote {
            border-right: 3px solid var(--accent);
            padding: 12px 20px;
            background: var(--accent-glow);
            border-radius: 0 8px 8px 0;
            margin: 12px 0;
        }
        .topic-body hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        /* Wiki links */
        .wiki-link {
            color: var(--accent-light);
            text-decoration: none;
            border-bottom: 1px dashed var(--accent);
            padding-bottom: 1px;
            transition: all 0.2s;
        }
        .wiki-link:hover { color: var(--text-primary); border-bottom-style: solid; }
        .wiki-link-missing { color: var(--warning); border-bottom-color: var(--warning); }

        /* Inline code */
        .topic-body code:not([class]) {
            background: var(--code-bg);
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 0.85em;
            font-family: 'Fira Code', monospace;
            color: #e06c75;
            direction: ltr;
            unicode-bidi: embed;
        }

        /* Code blocks */
        .code-block-wrapper {
            margin: 16px 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
            direction: ltr;
        }
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px;
            background: #21252b;
            border-bottom: 1px solid var(--border);
        }
        [data-theme="light"] .code-block-header { background: #e8eaed; }

        .code-lang {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 0.72rem;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }
        .copy-btn:hover { background: var(--accent-glow); color: var(--accent-light); border-color: var(--accent); }
        .copy-btn.copied { color: var(--success); border-color: var(--success); }
        .code-block-wrapper pre { margin: 0; padding: 0; }
        .code-block-wrapper pre code {
            display: block;
            padding: 16px !important;
            font-size: 0.85rem;
            line-height: 1.6;
            font-family: 'Fira Code', monospace;
            background: var(--code-bg) !important;
        }

        /* ─── Backlinks ─── */
        .backlinks-section {
            margin-top: 24px;
            padding: 20px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .backlinks-section h3 {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .backlinks-list { display: flex; flex-wrap: wrap; gap: 6px; }
        .backlink-item {
            padding: 5px 14px;
            border-radius: 20px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--accent-light);
            text-decoration: none;
            font-size: 0.82rem;
            transition: all 0.2s;
        }
        .backlink-item:hover { background: var(--accent-glow); border-color: var(--accent); }

        /* ─── Forms ─── */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        textarea.form-control {
            min-height: 250px;
            line-height: 1.8;
            resize: vertical;
            font-family: 'Fira Code', 'Cairo', monospace;
            font-size: 0.88rem;
            direction: ltr;
            text-align: left;
        }
        .form-hint { margin-top: 6px; font-size: 0.78rem; color: var(--text-muted); }
        .form-hint code { background: var(--code-bg); padding: 2px 5px; border-radius: 4px; font-size: 0.78rem; color: var(--accent-light); }
        .error-msg { color: var(--danger); font-size: 0.8rem; margin-top: 4px; }

        /* ─── Editor with Preview ─── */
        .editor-container { display: flex; gap: 16px; margin-bottom: 20px; }
        .editor-pane, .preview-pane { flex: 1; min-width: 0; }
        .preview-pane {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            max-height: 500px;
            overflow-y: auto;
        }
        .preview-header {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        /* ─── Wiki Autocomplete ─── */
        .autocomplete-dropdown {
            position: absolute;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            max-height: 180px;
            overflow-y: auto;
            z-index: 200;
            display: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            min-width: 220px;
        }
        .autocomplete-dropdown.show { display: block; }
        .autocomplete-item {
            padding: 8px 14px;
            font-size: 0.85rem;
            cursor: pointer;
            color: var(--text-primary);
            transition: background 0.15s;
        }
        .autocomplete-item:hover, .autocomplete-item.active {
            background: var(--accent-glow);
            color: var(--accent-light);
        }

        /* ─── Search ─── */
        .search-page-input { display: flex; gap: 10px; margin-bottom: 28px; }
        .search-page-input input { flex: 1; }
        .search-result-item {
            padding: 18px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 10px;
            transition: border-color 0.2s;
        }
        .search-result-item:hover { border-color: var(--accent); }
        .search-result-item h3 a { color: var(--accent-light); text-decoration: none; font-size: 1rem; }
        .search-result-item h3 a:hover { text-decoration: underline; }
        .search-result-item .preview {
            color: var(--text-secondary);
            font-size: 0.82rem;
            margin-top: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ─── Empty State ─── */
        .empty-state { text-align: center; padding: 50px 20px; color: var(--text-muted); }
        .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5; }
        .empty-state h3 { font-size: 1rem; margin-bottom: 6px; color: var(--text-secondary); }
        .empty-state p { font-size: 0.85rem; margin-bottom: 16px; }

        /* ─── Version History ─── */
        .version-list { list-style: none; }
        .version-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 8px;
        }
        .version-info { display: flex; align-items: center; gap: 12px; }
        .version-badge {
            background: var(--accent-glow);
            color: var(--accent-light);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .version-note { font-size: 0.85rem; color: var(--text-secondary); }
        .version-date { font-size: 0.75rem; color: var(--text-muted); }

        /* ─── Block Editor (Drag & Drop) ─── */
        .block-editor-container { display: flex; gap: 16px; }
        .block-editor-pane { flex: 1; min-width: 0; }

        .block-editor {
            min-height: 300px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            background: var(--bg-primary);
            position: relative; /* Context for absolute positioning if needed */
        }

        .block-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 12px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 8px;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
            position: relative;
        }
        .block-item:hover { border-color: var(--accent); }
        .block-item.dragging {
            opacity: 0.5;
            border-color: var(--accent);
            box-shadow: 0 8px 25px var(--accent-glow);
            transform: scale(0.98);
        }
        .block-item.drag-over {
            border-top: 3px solid var(--accent);
            padding-top: 7px;
        }

        .block-handle {
            cursor: grab;
            color: var(--text-muted);
            font-size: 1.1rem;
            padding: 2px 4px;
            user-select: none;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            margin-top: 4px;
        }
        .block-handle:active { cursor: grabbing; }

        .block-content {
            flex: 1;
            min-width: 0;
        }

        .block-content textarea {
            width: 100%;
            min-height: 60px;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-family: 'Fira Code', 'Cairo', monospace;
            font-size: 0.85rem;
            line-height: 1.7;
            resize: vertical;
            outline: none;
            direction: ltr;
            text-align: left;
        }

        .block-type-badge {
            font-size: 0.6rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .block-actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-shrink: 0;
        }

        .block-action-btn {
            width: 24px; height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.75rem;
            transition: all 0.2s;
        }
        .block-action-btn:hover { background: var(--accent-glow); color: var(--accent-light); }
        .block-action-btn.danger:hover { background: rgba(248,113,113,0.15); color: var(--danger); }

        .add-block-area {
            position: relative;
            margin-top: 8px;
        }

        .add-block-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 8px;
            background: transparent;
            border: 1px dashed var(--border);
            border-radius: 10px;
            color: var(--text-muted);
            font-family: 'Cairo', sans-serif;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .add-block-btn:hover {
            border-color: var(--accent);
            color: var(--accent-light);
            background: var(--accent-glow);
        }

        /* Block Type Menu */
        .block-type-menu {
            position: absolute;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 100;
            display: none;
            min-width: 150px;
            flex-direction: column;
            gap: 2px;
        }
        .block-type-menu.show { display: flex; }
        .block-type-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 6px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            transition: all 0.1s;
        }
        .block-type-option:hover {
            background: var(--accent-glow);
            color: var(--accent-light);
        }
        .block-type-icon { width: 16px; text-align: center; font-size: 0.9rem; }

        /* Floating Toolbar */
        .floating-toolbar {
            position: absolute;
            background: #2d2d2d;
            border: 1px solid #444;
            border-radius: 6px;
            padding: 4px;
            display: flex;
            gap: 4px;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease, transform 0.15s ease;
            transform: translateY(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .floating-toolbar.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .floating-toolbar button {
            background: transparent;
            border: none;
            color: #ddd;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.1s;
        }
        .floating-toolbar button:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .sidebar { position: static; width: 100%; height: auto; border-left: none; border-bottom: 1px solid var(--border); }
            .main-content { margin-right: 0; padding: 20px 14px; }
            .topics-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .editor-container { flex-direction: column; }
            .block-editor-container { flex-direction: column; }
            .stats-bar { flex-direction: column; }
        }
    </style>
</head>
<body>

	    <aside class="sidebar">
	        <div class="sidebar-header">
	            <a href="{{ session('current_domain') ? route('topics.index', ['domain' => session('current_domain')]) : route('topics.index') }}" class="sidebar-logo">
	                <div class="logo-icon">📘</div>
	                <h1>{{ isset($currentDomain) ? $currentDomain->name : 'Home' }} Wiki</h1>
	            </a>
	            <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الوضع">🌙</button>
	        </div>

	        <form action="{{ route('search') }}" method="GET" class="sidebar-search">
	            @if(session('current_domain'))
	                <input type="hidden" name="domain" value="{{ session('current_domain') }}">
	            @endif
	            <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
	                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
	            </svg>
	            <input type="text" name="q" placeholder="ابحث في المواضيع..." value="{{ request('q') }}">
	        </form>

	        @php
	            $globalDomains = isset($domains) ? $domains : \App\Models\Domain::all();
	            $sessionDomainSlug = session('current_domain');
	            $sessionDomain = $sessionDomainSlug ? \App\Models\Domain::where('slug', $sessionDomainSlug)->first() : null;
	            $currDomainId = isset($currentDomain) ? $currentDomain->id : ($sessionDomain->id ?? null);
	        @endphp

	        <div class="domain-switcher">
	            @foreach($globalDomains as $domain)
	                <a href="{{ route('topics.index', ['domain' => $domain->slug]) }}" 
	                   class="domain-item {{ $currDomainId && $currDomainId == $domain->id ? 'active' : '' }}"
	                   style="--domain-color: {{ $domain->color }}">
	                   <span class="domain-icon">{{ $domain->icon }}</span>
	                   <span class="domain-name">{{ $domain->name }}</span>
	                </a>
	            @endforeach
	        </div>

	        <nav class="sidebar-nav">
	            <div class="sidebar-nav-title">التنقل</div>
	            <a href="{{ session('current_domain') ? route('topics.index', ['domain' => session('current_domain')]) : route('topics.index') }}" class="{{ request()->routeIs('topics.index') && !request('category') && !request('tag') ? 'active' : '' }}">
	                <span class="nav-icon">🏠</span> كل المواضيع
	            </a>
	            @if(session('current_domain'))
	            <a href="{{ route('topics.create', ['domain' => session('current_domain')]) }}" class="{{ request()->routeIs('topics.create') ? 'active' : '' }}">
	                <span class="nav-icon">✏️</span> موضوع جديد
	            </a>
	            @endif
	            <a href="{{ session('current_domain') ? route('topics.graph', ['domain' => session('current_domain')]) : route('topics.graph') }}" class="{{ request()->routeIs('topics.graph') ? 'active' : '' }}">
	                <span class="nav-icon">🕸️</span> خريطة المعرفة
	            </a>
	            <a href="{{ session('current_domain') ? route('topics.review', ['domain' => session('current_domain')]) : route('topics.review') }}" class="{{ request()->routeIs('topics.review') ? 'active' : '' }}">
	                <span class="nav-icon">🔄</span> مراجعة
	                @if(isset($reviewCount) && $reviewCount > 0)
	                    <span class="nav-badge">{{ $reviewCount }}</span>
	                @endif
	            </a>
            
            <a href="{{ route('admin.index') }}" class="{{ request()->is('admin*') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span> لوحة التحكم
            </a>

	            <a href="{{ session('current_domain') ? route('search', ['domain' => session('current_domain')]) : route('search') }}" class="{{ request()->routeIs('search') ? 'active' : '' }}">
	                <span class="nav-icon">🔍</span> بحث متقدم
	            </a>
	        </nav>

        @php
            if (!isset($tags)) {
                $tags = \App\Models\Tag::withCount('topics')->orderByDesc('topics_count')->get();
            }
            if (!isset($categories)) {
                $categories = \App\Models\Topic::whereNotNull('category')->distinct()->pluck('category');
            }
        @endphp

	        @if(isset($categories) && $categories->count())
	            <div class="sidebar-tags">
	                <div class="sidebar-nav-title">التصنيفات</div>
	                @foreach($categories as $cat)
	                    <a href="{{ route('topics.index', ['category' => $cat, 'domain' => (session('current_domain') ?? 'flutter')]) }}"
	                       class="category-tag {{ request('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
	                @endforeach
	            </div>
	        @endif

	        @if(isset($tags) && $tags->count())
	            <div class="sidebar-tags">
	                <div class="sidebar-nav-title">التاقات</div>
	                @foreach($tags as $tag)
	                    <a href="{{ route('topics.index', ['tag' => $tag->slug, 'domain' => (session('current_domain') ?? 'flutter')]) }}"
	                       class="tag-pill {{ request('tag') === $tag->slug ? 'active' : '' }}"
	                       style="border-color: {{ $tag->color }}; {{ request('tag') === $tag->slug ? 'background:' . $tag->color . '; color:#fff;' : '' }}">
	                        {{ $tag->name }}
	                        <small>({{ $tag->topics_count }})</small>
	                    </a>
	                @endforeach
	            </div>
	        @endif
	    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <div id="autocomplete-dropdown" class="autocomplete-dropdown"></div>

    <script>
        // Highlight.js init
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('pre code').forEach(block => hljs.highlightElement(block));
        });

        // Copy code
        function copyCode(button) {
            const code = button.closest('.code-block-wrapper').querySelector('code');
            navigator.clipboard.writeText(code.textContent).then(() => {
                button.classList.add('copied');
                const original = button.innerHTML;
                button.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20,6 9,17 4,12"/></svg> تم!';
                setTimeout(() => { button.classList.remove('copied'); button.innerHTML = original; }, 2000);
            });
        }

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);

            const btn = document.querySelector('.theme-toggle');
            btn.textContent = next === 'dark' ? '🌙' : '☀️';

            // Toggle highlight.js theme
            document.getElementById('hljs-dark').disabled = next !== 'dark';
            document.getElementById('hljs-light').disabled = next !== 'light';
        }

        // Load saved theme
        (function() {
            const saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
            const btn = document.querySelector('.theme-toggle');
            if (btn) btn.textContent = saved === 'dark' ? '🌙' : '☀️';
            const darkSheet = document.getElementById('hljs-dark');
            const lightSheet = document.getElementById('hljs-light');
            if (darkSheet) darkSheet.disabled = saved !== 'dark';
            if (lightSheet) lightSheet.disabled = saved !== 'light';
        })();

        // Wiki autocomplete
        // Wiki autocomplete
        let autocompleteActiveTextarea = null;
        
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('autocomplete-dropdown');
            if (dropdown && !dropdown.contains(e.target) && e.target !== autocompleteActiveTextarea) {
                dropdown.classList.remove('show');
                autocompleteActiveTextarea = null;
            }
        });

        function initAutocomplete(textarea) {
            const dropdown = document.getElementById('autocomplete-dropdown');
            let searchStart = -1;

            textarea.addEventListener('input', function(e) {
                const val = this.value;
                const pos = this.selectionStart;
                const before = val.substring(0, pos);
                const match = before.match(/\[\[([^\]]*?)$/);

                if (match) {
                    const query = match[1];
                    searchStart = pos - query.length;
                    
                    if (query.length >= 0) { // Changed to 0 to allow showing all on [[
                        fetch('/api/topics?q=' + encodeURIComponent(query))
                            .then(r => r.json())
                            .then(topics => {
                                if (topics.length === 0) { dropdown.classList.remove('show'); return; }
                                dropdown.innerHTML = topics.map(t =>
                                    '<div class="autocomplete-item" data-title="' + t.title + '">' + t.title + '</div>'
                                ).join('');
                                
                                // Calculate position relative to cursor (approximate) or textarea
                                // For simplicity, we stick to textarea bottom-left or use a library for cursor pos.
                                // Improvement: use textarea relative coordinates
                                const rect = textarea.getBoundingClientRect();
                                // Basic positioning: below the textarea
                                dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
                                dropdown.style.left = (rect.left + window.scrollX) + 'px';
                                
                                dropdown.classList.add('show');
                                autocompleteActiveTextarea = textarea;

                                dropdown.querySelectorAll('.autocomplete-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        const title = this.dataset.title;
                                        const afterCursor = val.substring(pos);
                                        const newVal = val.substring(0, searchStart) + title + ']]' + afterCursor;
                                        textarea.value = newVal;
                                        // Restore cursor position
                                        const newCursorPos = searchStart + title.length + 2;
                                        textarea.setSelectionRange(newCursorPos, newCursorPos);
                                        
                                        dropdown.classList.remove('show');
                                        autocompleteActiveTextarea = null;
                                        textarea.focus();
                                        textarea.dispatchEvent(new Event('input'));
                                    });
                                });
                            });
                    }
                } else {
                    if (dropdown && autocompleteActiveTextarea === textarea) {
                        dropdown.classList.remove('show');
                        autocompleteActiveTextarea = null;
                    }
                }
            });

            textarea.addEventListener('keydown', function(e) {
                const dropdown = document.getElementById('autocomplete-dropdown');
                if (!dropdown.classList.contains('show') || autocompleteActiveTextarea !== textarea) return;
                
                if (e.key === 'Escape') { 
                    dropdown.classList.remove('show');
                    autocompleteActiveTextarea = null;
                }
            });
        }

        // Live preview
        function initLivePreview(textarea, previewEl) {
            function updatePreview() {
                let text = textarea.value;
                const placeholders = [];

                // 1. Extract Mermaid Blocks
                text = text.replace(/```mermaid\n([\s\S]*?)\n```/g, (match, code) => {
                    placeholders.push('<div class="mermaid">' + code + '</div>');
                    return `__BLOCK_${placeholders.length - 1}__`;
                });

                // 2. Extract Code Blocks
                text = text.replace(/```(\w*)\n([\s\S]*?)\n```/g, (match, lang, code) => {
                    placeholders.push(`<div class="code-block-wrapper"><div class="code-block-header"><span class="code-lang">${lang}</span></div><pre><code class="language-${lang}">${code}</code></pre></div>`);
                    return `__BLOCK_${placeholders.length - 1}__`;
                });

                // 3. Formatting
                text = text.replace(/### (.*)/g, '<h3>$1</h3>');
                text = text.replace(/## (.*)/g, '<h2>$1</h2>');
                text = text.replace(/# (.*)/g, '<h1>$1</h1>');
                text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                text = text.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
                text = text.replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1" style="max-width:100%; border-radius: 8px;">');
                text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" style="text-decoration: underline; color: inherit;">$1</a>');
                text = text.replace(/`([^`]+)`/g, '<code>$1</code>');
                text = text.replace(/\[\[([^\]]+)\]\]/g, '<a class="wiki-link" href="#">$1</a>');
                text = text.replace(/\- (.*)/g, '<li>$1</li>');
                
                // 4. Newlines to BR (safe now)
                text = text.replace(/\n/g, '<br>');
                
                // 5. Restore Blocks
                text = text.replace(/__BLOCK_(\d+)__/g, (match, index) => placeholders[index]);

                previewEl.innerHTML = text;
                previewEl.querySelectorAll('pre code').forEach(block => hljs.highlightElement(block));
                try {
                    mermaid.run({ nodes: previewEl.querySelectorAll('.mermaid') });
                } catch (e) { console.error('Mermaid error:', e); }
            }
            textarea.addEventListener('input', updatePreview);
            updatePreview();
        }

        // ─── Block Editor (Drag & Drop) ───
        function initBlockEditor(hiddenTextarea, editorEl, previewEl) {
            let draggedBlock = null;

            // Block Types Configuration
            const defaultLang = window.defaultCodeLanguage || 'plaintext';
            const blockTypes = [
                { label: 'نص عادي', icon: '📝', template: '' },
                { label: 'عنوان رئيسي', icon: 'H1', template: '# ' },
                { label: 'عنوان فرعي', icon: 'H2', template: '## ' },
                { label: 'عنوان جانبي', icon: 'H3', template: '### ' },
                { label: 'قائمة نقطية', icon: '≣', template: '- ' },
                { label: 'كود برمجي', icon: '💻', template: '```' + defaultLang + '\n\n```' },
                { label: 'اقتباس', icon: '❝', template: '> ' },
                { label: 'صورة', icon: '🖼️', template: '![وصف الصورة](https://)' },
                { label: 'رسم بياني', icon: '📊', template: '```mermaid\ngraph TD;\n    A-->B;\n```' },
                { label: 'ملاحظة', icon: '💡', template: '> [!NOTE]\n> ' },
            ];

            function detectBlockType(text) {
                text = text.trim();
                if (text.startsWith('```')) return 'كود';
                if (text.startsWith('# ')) return 'عنوان رئيسي';
                if (text.startsWith('## ')) return 'عنوان فرعي';
                if (text.startsWith('### ')) return 'عنوان جانبي';
                if (text.startsWith('- ') || text.startsWith('* ')) return 'قائمة';
                if (text.startsWith('> ')) return 'اقتباس';
                if (text.startsWith('|')) return 'جدول';
                return 'نص';
            }

            function syncToTextarea() {
                const blocks = editorEl.querySelectorAll('.block-content textarea');
                const combined = Array.from(blocks).map(t => t.value.trim()).filter(v => v).join('\n\n');
                hiddenTextarea.value = combined;
                if (previewEl) {
                    hiddenTextarea.dispatchEvent(new Event('input'));
                }
            }

            function createBlockEl(text, focus = false) {
                const div = document.createElement('div');
                div.className = 'block-item';
                div.draggable = true;

                const type = detectBlockType(text);
                const lines = text.split('\n').length;
                const height = Math.max(50, lines * 22 + 20);

                div.innerHTML = `
                    <div class="block-handle" title="اسحب لنقل">☰</div>
                    <div class="block-content">
                        <div class="block-type-badge">${type}</div>
                        <textarea style="height:${height}px">${text}</textarea>
                    </div>
                    <div class="block-actions">
                        <button type="button" class="block-action-btn" onclick="openBlockMenu(this)" title="إضافة بلوك">＋</button>
                        <button type="button" class="block-action-btn danger" onclick="blockEditorRemove(this)" title="حذف">✕</button>
                    </div>
                `;

                // Drag events
                div.addEventListener('dragstart', (e) => {
                    draggedBlock = div;
                    div.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });
                div.addEventListener('dragend', () => {
                    div.classList.remove('dragging');
                    editorEl.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
                    draggedBlock = null;
                    syncToTextarea();
                });
                div.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    if (draggedBlock && draggedBlock !== div) {
                        div.classList.add('drag-over');
                    }
                });
                div.addEventListener('dragleave', () => div.classList.remove('drag-over'));
                div.addEventListener('drop', (e) => {
                    e.preventDefault();
                    div.classList.remove('drag-over');
                    if (draggedBlock && draggedBlock !== div) {
                        editorEl.insertBefore(draggedBlock, div);
                        syncToTextarea();
                    }
                });

                // Auto-resize & sync
                const ta = div.querySelector('textarea');
                ta.addEventListener('input', () => {
                    ta.style.height = 'auto';
                    ta.style.height = ta.scrollHeight + 'px';
                    div.querySelector('.block-type-badge').textContent = detectBlockType(ta.value);
                    
                    // Slash command
                    if (ta.value === '/') {
                        window.openBlockMenu(ta);
                    }

                    syncToTextarea();
                });

                // Keyboard Shortcuts
                ta.addEventListener('keydown', (e) => {
                    if (e.key === '/') {
                        // Already handled in input, but we might want to capture it here if needed
                    }
                    else if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        const newBlock = createBlockEl('', true);
                        div.after(newBlock);
                        syncToTextarea();
                    }
                    else if (e.key === 'Backspace' && ta.value === '') {
                        const prev = div.previousElementSibling;
                        if (prev && prev.classList.contains('block-item')) {
                            e.preventDefault();
                            blockEditorRemove(div.querySelector('.block-action-btn.danger')); // use existing remove logic
                            const prevTa = prev.querySelector('textarea');
                            prevTa.focus();
                            prevTa.setSelectionRange(prevTa.value.length, prevTa.value.length);
                        }
                    }
                    else if (e.key === 'ArrowUp' && ta.selectionStart === 0) {
                        const prev = div.previousElementSibling;
                        if (prev && prev.classList.contains('block-item')) {
                            e.preventDefault();
                            const prevTa = prev.querySelector('textarea');
                            prevTa.focus();
                        }
                    }
                    else if (e.key === 'ArrowDown' && ta.selectionStart === ta.value.length) {
                        const next = div.nextElementSibling;
                        if (next && next.classList.contains('block-item')) {
                            e.preventDefault();
                            const nextTa = next.querySelector('textarea');
                            nextTa.focus();
                        }
                    }
                });

                if (typeof initAutocomplete === 'function') {
                    initAutocomplete(ta);
                }

                if (focus) setTimeout(() => ta.focus(), 50);

                return div;
            }

            // Create Menu DOM
            const menu = document.createElement('div');
            menu.className = 'block-type-menu';
            blockTypes.forEach(t => {
                const item = document.createElement('div');
                item.className = 'block-type-option';
                item.innerHTML = `<span class="block-type-icon">${t.icon}</span> ${t.label}`;
                item.onclick = () => {
                    const template = t.template;
                    
                    if (window.targetBlockBtn) {
                        // Triggered via button
                        const newBlock = createBlockEl(template, true);
                        if (window.targetBlockBtn.tagName === 'TEXTAREA') {
                            // Triggered via Slash command in textarea
                             // Replace '/' with template, or if template is empty just clear '/'
                            const ta = window.targetBlockBtn;
                            const blockItem = ta.closest('.block-item');
                            
                            // If user selected generic text, just clear the slash
                            if (template === '') {
                                ta.value = '';
                            } else {
                                // If block was just '/', replace content with template
                                // If it had content (unlikely given check), append?
                                // Slash command usually transforms the CURRENT block.
                                ta.value = template;
                            }
                            // Trigger resize
                            ta.style.height = 'auto';
                            ta.style.height = ta.scrollHeight + 'px';
                            div.querySelector('.block-type-badge').textContent = detectBlockType(ta.value);
                            ta.focus();
                        } else {
                             // Triggered via (+) button next to block
                             // Logic: Insert NEW block after current
                             const newBlock = createBlockEl(template, true);
                             window.targetBlockBtn.closest('.block-item').after(newBlock);
                        }
                    } else {
                        // Insert at end (from main button)
                        const newBlock = createBlockEl(template, true);
                        const addArea = editorEl.querySelector('.add-block-area');
                        editorEl.insertBefore(newBlock, addArea);
                    }
                    menu.classList.remove('show');
                    syncToTextarea();
                    window.targetBlockBtn = null;
                };
                menu.appendChild(item);
            });
            document.body.appendChild(menu);

            // ... loadBlocks ... (omitted for brevity, handled by existing code region)

            // Global helpers
            window.openBlockMenu = function(target) {
                window.targetBlockBtn = target;
                const rect = target.getBoundingClientRect();
                
                if (target.tagName === 'TEXTAREA') {
                     // Position near the bottom-left of the textarea (slash command style)
                    menu.style.top = (rect.bottom + window.scrollY + 5) + 'px';
                    menu.style.left = (rect.left + window.scrollX) + 'px';
                } else {
                    // Button alignment
                    menu.style.top = (rect.bottom + window.scrollY + 5) + 'px';
                    menu.style.left = (rect.left + window.scrollX - 100) + 'px'; 
                }

                // Adjust if check fails
                 if (window.innerHeight - rect.bottom < 250) {
                    menu.style.top = (rect.top + window.scrollY - menu.offsetHeight - 5) + 'px';
                }

                menu.classList.add('show');
                event.stopPropagation();
            };

            // Initialize blocks from textarea content
            function loadBlocks() {
                editorEl.innerHTML = '';
                const text = hiddenTextarea.value || '';
                const blocks = [];
                let current = '';
                let inCode = false;
                text.split('\n').forEach(line => {
                    if (line.startsWith('```') && !inCode) {
                        if (current.trim()) { blocks.push(current.trim()); current = ''; }
                        inCode = true;
                        current = line + '\n';
                    } else if (line.startsWith('```') && inCode) {
                        current += line;
                        blocks.push(current.trim());
                        current = '';
                        inCode = false;
                    } else if (inCode) {
                        current += line + '\n';
                    } else if (line === '' && current.trim()) {
                        blocks.push(current.trim());
                        current = '';
                    } else {
                        current += line + '\n';
                    }
                });
                if (current.trim()) blocks.push(current.trim());

                blocks.forEach(b => editorEl.appendChild(createBlockEl(b)));

                // Add button area
                const addArea = document.createElement('div');
                addArea.className = 'add-block-area';
                
                const addBtn = document.createElement('button');
                addBtn.type = 'button';
                addBtn.className = 'add-block-btn';
                addBtn.innerHTML = '＋ إضافة بلوك جديد';
                addBtn.onclick = (e) => {
                    window.targetBlockBtn = null;
                    const rect = addBtn.getBoundingClientRect();
                    menu.style.top = (rect.bottom + window.scrollY + 5) + 'px';
                    menu.style.left = (rect.left + window.scrollX) + 'px';
                    // Adjust if too close to bottom
                     if (window.innerHeight - rect.bottom < 250) {
                        menu.style.top = (rect.top + window.scrollY - menu.offsetHeight - 5) + 'px';
                    }
                    menu.classList.add('show');
                    e.stopPropagation();
                };
                
                addArea.appendChild(addBtn);
                editorEl.appendChild(addArea);
            }

            // Global helpers


            window.blockEditorRemove = function(btn) {
                const blockItem = btn.closest('.block-item');
                if (editorEl.querySelectorAll('.block-item').length <= 0) return;
                blockItem.remove();
                syncToTextarea();
            };

            // Close menu on click outside
            document.addEventListener('click', () => {
                menu.classList.remove('show');
            });

            // --- Drag & Drop Image Upload ---
            const uploadImage = async (file) => {
                const formData = new FormData();
                formData.append('image', file);
                
                // Visualization
                const placeholderBlock = createBlockEl('![جاري الرفع...](https://via.placeholder.com/150?text=Uploading...)');
                const addArea = editorEl.querySelector('.add-block-area');
                if (addArea) {
                    editorEl.insertBefore(placeholderBlock, addArea);
                } else {
                    editorEl.appendChild(placeholderBlock);
                }
                
                try {
                    const response = await fetch('{{ route('upload.image') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.url) {
                        const newBlock = createBlockEl(`![صورة](${data.url})`);
                        editorEl.replaceChild(newBlock, placeholderBlock);
                        syncToTextarea();
                        // Trigger preview update
                        const newTextarea = newBlock.querySelector('textarea');
                        if (newTextarea) newTextarea.dispatchEvent(new Event('input'));
                    } else {
                        alert('فشل الرفع: ' + (data.error || 'خطأ غير معروف'));
                        placeholderBlock.remove();
                    }
                } catch (e) {
                    console.error(e);
                    alert('خطأ في الرفع');
                    placeholderBlock.remove();
                }
            };

            editorEl.addEventListener('dragover', (e) => {
                e.preventDefault();
                editorEl.classList.add('drag-over');
            });
            editorEl.addEventListener('dragleave', () => editorEl.classList.remove('drag-over'));
            editorEl.addEventListener('drop', (e) => {
                e.preventDefault();
                editorEl.classList.remove('drag-over');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    const file = e.dataTransfer.files[0];
                    if (file.type.startsWith('image/')) {
                        uploadImage(file);
                    }
                }
            });

            // Paste Image
            editorEl.addEventListener('paste', (e) => {
                if (e.clipboardData && e.clipboardData.files.length > 0) {
                    const file = e.clipboardData.files[0];
                    if (file.type.startsWith('image/')) {
                        e.preventDefault();
                        uploadImage(file);
                    }
                }
            });

            loadBlocks();
            // Force initial preview update
            syncToTextarea();
        }

        // Floating Toolbar
        function initFloatingToolbar() {
            const editorContainer = document.getElementById('block-editor');
            if (!editorContainer) return;

            let activeTextarea = null;

            // Create Toolbar DOM
            const toolbar = document.createElement('div');
            toolbar.className = 'floating-toolbar';
            
            const buttons = [
                { label: 'Bold', action: () => format('**', '**') },
                { label: 'Italic', action: () => format('*', '*') },
                { label: 'Code', action: () => format('`', '`') },
                { label: 'Link', action: () => formatLink() }
            ];

            buttons.forEach(btn => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = btn.label;
                b.onmousedown = (e) => e.preventDefault(); // Prevent focus loss
                b.onclick = btn.action;
                toolbar.appendChild(b);
            });
            document.body.appendChild(toolbar);

            function format(prefix, suffix) {
                if (!activeTextarea) return;
                const start = activeTextarea.selectionStart;
                const end = activeTextarea.selectionEnd;
                const text = activeTextarea.value;
                const selected = text.substring(start, end);
                activeTextarea.value = text.substring(0, start) + prefix + selected + suffix + text.substring(end);
                activeTextarea.dispatchEvent(new Event('input')); 
                toolbar.classList.remove('show');
                activeTextarea.focus();
                activeTextarea.setSelectionRange(start + prefix.length, start + prefix.length + selected.length);
            }
            
            function formatLink() {
                 if (!activeTextarea) return;
                 const start = activeTextarea.selectionStart;
                 const end = activeTextarea.selectionEnd;
                 const text = activeTextarea.value;
                 const selected = text.substring(start, end);
                 activeTextarea.value = text.substring(0, start) + '[' + selected + '](url)' + text.substring(end);
                 const newStart = start + 1 + selected.length + 2; 
                 const newEnd = newStart + 3; 
                 activeTextarea.setSelectionRange(newStart, newEnd);
                 activeTextarea.focus();
                 activeTextarea.dispatchEvent(new Event('input'));
                 toolbar.classList.remove('show');
            }

            // Show on mouseup
            editorContainer.addEventListener('mouseup', (e) => {
                 setTimeout(() => {
                     const active = document.activeElement;
                     if (!active || active.tagName !== 'TEXTAREA') return;
                     const start = active.selectionStart;
                     const end = active.selectionEnd;
                     
                     if (start !== end) {
                         activeTextarea = active;
                         toolbar.style.top = (e.pageY - 45) + 'px'; 
                         toolbar.style.left = Math.max(10, e.pageX - 80) + 'px';
                         toolbar.classList.add('show');
                     } else {
                         toolbar.classList.remove('show');
                     }
                 }, 10);
            });
            
            // Hide on typing
            editorContainer.addEventListener('input', () => toolbar.classList.remove('show'));
            // Hide on click outside
            document.addEventListener('mousedown', (e) => {
                if (!toolbar.contains(e.target) && (!activeTextarea || e.target !== activeTextarea)) {
                    toolbar.classList.remove('show');
                }
            });
        }


        // Call initFloatingToolbar
        document.addEventListener('DOMContentLoaded', () => {
            initFloatingToolbar();
        });
    </script>
</body>
</html>
