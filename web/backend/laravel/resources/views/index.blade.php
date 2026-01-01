<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NETES - Network Connection Status</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</head>

<body class="bg-gray-900 text-green-400 font-mono">

<div class="bg-gray-900 p-4 text-yellow-500 text-xl font-mono">
    <h1>
       NETES - Network Connection Status
    </h1>
</div>

<div class="p-4 border-b border-green-700">
    <div
        hx-get="/status"
        hx-trigger="every 3s"
        hx-swap="outerHTML"
        class="text-xl">
        Checking...
    </div>
</div>

<div class="p-4">
    <pre
        hx-get="/logs"
        hx-trigger="every 3s"
        hx-swap="innerHTML"
        class="text-sm bg-black p-3 rounded overflow-auto h-96">
Loading logs...
    </pre>
</div>

</body>
</html>
