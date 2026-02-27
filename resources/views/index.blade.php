@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-gray-800">Gestion Production Agricole</h1>

    <!-- KPI Cards (grid cols-4, taille p-4, bg-*-100) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-100 p-4 rounded-lg shadow text-center">
            <h3 class="text-lg font-semibold">Total Récoltes</h3>
            <p class="text-2xl">{{ $totalRecoltes }} kg</p>
            <span class="text-green-500 text-sm">+12%</span>
        </div>
        <div class="bg-green-100 p-4 rounded-lg shadow text-center">
            <h3 class="text-lg font-semibold">Chiffre d'Affaires</h3>
            <p class="text-2xl">{{ $chiffreAffaires }} FCFA</p>
            <span class="text-green-500 text-sm">+8%</span>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg shadow text-center">
            <h3 class="text-lg font-semibold">Quantité Vendue</h3>
            <p class="text-2xl">{{ $quantiteVendue }} kg</p>
            <span class="text-green-500 text-sm">+5%</span>
        </div>
        <div class="bg-red-100 p-4 rounded-lg shadow text-center">
            <h3 class="text-lg font-semibold">Total Pertes</h3>
            <p class="text-2xl">{{ $totalPertes }} kg</p>
            <span class="text-green-500 text-sm">+3%</span>
        </div>
    </div>

    <!-- Graphs (grid cols-2, taille h-64) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Évolution de la Production</h2>
            <canvas id="evolutionChart" class="h-64"></canvas>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Répartition par Produit</h2>
            <canvas id="repartitionChart" class="h-64"></canvas>
        </div>
    </div>

    <!-- Script Graphs (Chart.js) -->
    <script>
        // Évolution (ligne)
        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($evolutions['labels']) !!},
                datasets: [
                    { label: 'Récoltes', data: {!! json_encode($evolutions['recoltes']) !!}, borderColor: 'blue', fill: false },
                    { label: 'Ventes', data: {!! json_encode($evolutions['ventes']) !!}, borderColor: 'green', fill: false },
                    { label: 'Pertes', data: {!! json_encode($evolutions['pertes']) !!}, borderColor: 'red', fill: false }
                ]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        // Répartition (pie)
        new Chart(document.getElementById('repartitionChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($repartition['labels']) !!},
                datasets: [{ data: {!! json_encode($repartition['data']) !!}, backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'] }]
            }
        });
    </script>

    <!-- Activités Récentes (table w-full, p-2) -->
    <div class="bg-white p-4 rounded-lg shadow mb-8">
        <h2 class="text-xl font-semibold mb-2">Activités Récentes</h2>
        <table class="w-full text-left">
            <tbody>
                @foreach($activites as $activite)
                    <tr class="border-b">
                        <td class="p-2">{{ $activite->date }}</td>
                        <td class="p-2">{{ $activite->description }}</td>
                        <td class="p-2">{{ $activite->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- État des Stocks (table w-full, p-2) -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">État des Stocks</h2>
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Variété</th>
                    <th class="p-2">Produit</th>
                    <th class="p-2">Récolté (kg)</th>
                    <th class="p-2">Vendu (kg)</th>
                    <th class="p-2">Stock (kg)</th>
                    <th class="p-2">Valeur (FCFA)</th>
                    <th class="p-2">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr class="border-b">
                        <td class="p-2">{{ $stock->variete }}</td>
                        <td class="p-2">{{ $stock->produit }}</td>
                        <td class="p-2">{{ $stock->recolte_kg }}</td>
                        <td class="p-2">{{ $stock->vendu_kg }}</td>
                        <td class="p-2">{{ $stock->stock_kg }}</td>
                        <td class="p-2">{{ $stock->valeur_fcfa }}</td>
                        <td class="p-2 {{ $stock->statut == 'Faible' ? 'text-yellow-500' : ($stock->statut == 'Épuisé' ? 'text-red-500' : 'text-green-500') }}">{{ $stock->statut }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
