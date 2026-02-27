<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriCompta - Gestion Production Agricole</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-800 text-white flex flex-col">
            <div class="p-6 border-b border-green-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-2xl font-bold">
                        🌱
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">AgriCompta</h1>
                        <p class="text-xs opacity-90">Gestion Production Agricole</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end">
                    <span class="bg-green-600 text-xs px-3 py-1 rounded-full">Exploitation Active</span>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('/') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('produits.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('produits*') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-box"></i>
                            <span>Produits</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('varietes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('varietes*') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-seedling"></i>
                            <span>Variétés</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recoltes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('recoltes*') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-truck-pickup"></i>
                            <span>Récoltes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ventes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('ventes*') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Ventes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pertes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->is('pertes*') ? 'bg-green-700' : 'hover:bg-green-700' }} transition">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Pertes</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-green-700 text-xs opacity-80">
                Projet BDA - L3 GL<br>
                Semestre 5 - 2025/2026
            </div>
        </aside>

        <!-- Contenu principal -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
