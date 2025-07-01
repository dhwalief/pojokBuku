<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membaca: {{ $book->title }}</title>
    <style>
        /* Reset dan base styling */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #2c2c2c;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Container untuk PDF */
        .pdf-container {
            width: 100%;
            height: 100%;
            position: relative;
            background: #2c2c2c;
            display: flex;
            flex-direction: column;
        }

        /* Canvas container */
        .canvas-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: auto;
            background: #1a1a1a;
            position: relative;
        }

        /* PDF Canvas */
        #pdfCanvas {
            max-width: 100%;
            max-height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            background: white;
        }

        /* Controls */
        .pdf-controls {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .control-button, .nav-button {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .control-button:hover, .nav-button:hover {
            background: rgba(0, 0, 0, 0.95);
            transform: translateY(-1px);
        }

        .control-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Page info */
        .page-info {
            position: fixed;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 16px;
            border-radius: 25px;
            font-size: 14px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Copyright notice */
        .copyright-notice {
            position: fixed;
            bottom: 15px;
            right: 15px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 1000;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Loading indicator */
        .loading-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 16px;
            text-align: center;
        }

        .loading-spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Zoom controls */
        .zoom-controls {
            position: fixed;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            background: rgba(0, 0, 0, 0.8);
            padding: 5px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .zoom-btn {
            background: transparent;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
        }

        .zoom-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pdf-controls {
                top: 10px;
                left: 10px;
                right: 10px;
                justify-content: space-between;
            }
            
            .control-button, .nav-button {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .page-info {
                top: 60px;
                right: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Controls -->
    <div class="pdf-controls">
        <a href="{{ route('books.show', $book->id) }}" class="nav-button">← Kembali</a>
        <button class="control-button" onclick="prevPage()" id="prevBtn">◀ Prev</button>
        <button class="control-button" onclick="nextPage()" id="nextBtn">Next ▶</button>
    </div>

    <!-- Page info -->
    <div class="page-info" id="pageInfo">
        Loading...
    </div>

    <!-- Zoom controls -->
    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoomOut()">-</button>
        <span id="zoomLevel" style="color: white; padding: 0 10px;">100%</span>
        <button class="zoom-btn" onclick="zoomIn()">+</button>
        <button class="zoom-btn" onclick="fitWidth()">Fit</button>
    </div>

    <!-- Copyright notice -->
    <div class="copyright-notice">
        📚 {{ Str::limit($book->title, 20) }} - Dilindungi Hak Cipta
    </div>

    <!-- PDF Container -->
    <div class="pdf-container">
        <div class="loading-indicator" id="loadingIndicator">
            <div class="loading-spinner"></div>
            Memuat dokumen PDF...
        </div>
        
        <div class="canvas-container" id="canvasContainer" style="display: none;">
            <canvas id="pdfCanvas"></canvas>
        </div>
    </div>

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <script>
        // PDF.js configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.2;
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');

        // Load PDF
        async function loadPDF() {
            try {
                const loadingTask = pdfjsLib.getDocument({
                    url: '{{ route("books.pdf.stream", ["book" => $book->id]) }}',
                    httpHeaders: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                pdfDoc = await loadingTask.promise;
                document.getElementById('pageInfo').textContent = `Page ${pageNum} of ${pdfDoc.numPages}`;
                
                // Hide loading, show canvas
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('canvasContainer').style.display = 'flex';
                
                // Render first page
                renderPage(pageNum);
                
            } catch (error) {
                console.error('Error loading PDF:', error);
                document.getElementById('loadingIndicator').innerHTML = 
                    '<div style="color: #ff6b6b;">Error loading PDF. Please refresh the page.</div>';
            }
        }

        // Render page
        function renderPage(num) {
            pageRendering = true;
            
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                const renderTask = page.render(renderContext);
                
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            // Update page info
            document.getElementById('pageInfo').textContent = `Page ${num} of ${pdfDoc.numPages}`;
            
            // Update button states
            document.getElementById('prevBtn').disabled = (num <= 1);
            document.getElementById('nextBtn').disabled = (num >= pdfDoc.numPages);
        }

        // Queue rendering
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // Navigation functions
        function prevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function nextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        // Zoom functions
        function zoomIn() {
            scale += 0.2;
            updateZoomLevel();
            queueRenderPage(pageNum);
        }

        function zoomOut() {
            if (scale <= 0.4) return;
            scale -= 0.2;
            updateZoomLevel();
            queueRenderPage(pageNum);
        }

        function fitWidth() {
            const container = document.getElementById('canvasContainer');
            const containerWidth = container.clientWidth - 40; // padding
            
            if (pdfDoc) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    const viewport = page.getViewport({scale: 1});
                    scale = containerWidth / viewport.width;
                    updateZoomLevel();
                    queueRenderPage(pageNum);
                });
            }
        }

        function updateZoomLevel() {
            document.getElementById('zoomLevel').textContent = Math.round(scale * 100) + '%';
        }

        // Security measures
        document.addEventListener('DOMContentLoaded', function() {
            // Disable right-click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+S, Ctrl+P, F12, etc.
                if ((e.ctrlKey && (e.key === 's' || e.key === 'p' || e.key === 'a')) || 
                    e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
                    (e.ctrlKey && e.key === 'u')) {
                    e.preventDefault();
                    return false;
                }
                
                // Arrow keys for navigation
                if (e.key === 'ArrowLeft') {
                    prevPage();
                } else if (e.key === 'ArrowRight') {
                    nextPage();
                }
            });

            // Disable drag
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable text selection on canvas
            canvas.addEventListener('selectstart', function(e) {
                e.preventDefault();
                return false;
            });

            // Load PDF
            loadPDF();
        });

        // Prevent iframe usage
        if (window.self !== window.top) {
            window.top.location.href = "{{ route('books.show', $book->id) }}";
        }

        // Disable console
        if (typeof console !== 'undefined') {
            console.log = console.warn = console.error = console.info = console.debug = function() {};
        }

        // Window blur protection
        window.addEventListener('blur', function() {
            document.body.style.filter = 'blur(10px)';
        });

        window.addEventListener('focus', function() {
            document.body.style.filter = 'none';
        });
    </script>
</body>
</html>