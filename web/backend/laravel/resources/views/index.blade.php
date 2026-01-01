<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NETES - Network Connection Status</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</head>

<body class="bg-gray-900 text-green-400 font-mono">

<!-- Header -->
<div class="bg-gray-900 p-4 text-yellow-500 text-xl font-mono">
    <h1>NETES - Network Connection Status</h1>
</div>

<!-- Status Section -->
<div class="p-4 border-b border-green-700">
    <div
        hx-get="/status"
        hx-trigger="every 3s"
        hx-swap="outerHTML"
        class="text-xl">
        Checking...
    </div>
</div>

<!-- Job Buttons -->
<div class="space-x-2 mb-6">
    <button onclick="runJob('python')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Run Python</button>
    <button onclick="runJob('rust')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Run Rust</button>
    <button onclick="runJob('java')" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Run Java</button>
</div>

<!-- Logs Section -->
<div id="log-container">
    <pre
        hx-get="/logs"
        hx-trigger="every 3s"
        hx-swap="outerHTML"
        class="text-sm bg-black text-white p-3 rounded overflow-auto h-96">
Loading logs...
    </pre>
</div>

<!-- JS for running jobs -->
<script>
function runJob(lang) {
    fetch('https://netes.onrender.com/run-job', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ language: lang })
    }).then(response => {
        if (!response.ok) {
            console.error('Failed to trigger job:', response.statusText);
        }
    }).catch(err => console.error('Error:', err));
}
</script>

</body>
</html>
