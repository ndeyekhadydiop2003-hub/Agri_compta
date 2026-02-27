@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-600 mt-1">Vue d'ensemble de votre production agricole</p>
        </div>



        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Total Récoltes</p>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRecoltes, 0, ',', ' ') }} kg</p>
               <p class="text-sm text-green-600 mt-2">
    @if($variationRecoltes !== null)
        {{ $variationRecoltes > 0 ? '+' : '' }}{{ $variationRecoltes }}% par rapport à l'année précédente
    @else
        ----------------------------------------
    @endif
</p>                <p class="text-xs text-gray-500">Quantité totale récoltée</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Total Ventes</p>
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalVentes, 0, ',', ' ') }} kg</p>
 <p class="text-sm text-green-600 mt-2">
    @if($variationRecoltes !== null)
        {{ $variationRecoltes > 0 ? '+' : '' }}{{ $variationRecoltes }}% par rapport à l'année précédente
    @else
        ----------------------------------------
    @endif
</p>                <p class="text-xs text-gray-500">Quantité vendue</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Chiffre d'affaires</p>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</p>
<p class="text-sm text-green-600 mt-2">
    @if($variationRecoltes !== null)
        {{ $variationRecoltes > 0 ? '+' : '' }}{{ $variationRecoltes }}% par rapport à l'année précédente
    @else
        ----------------------------------------
    @endif
</p>                <p class="text-xs text-gray-500">Revenus totaux</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Stock Actuel</p>
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stockActuel, 0, ',', ' ') }} kg</p>
                <p class="text-sm text-red-600 mt-2">Pertes : {{ number_format($totalPertes, 0, ',', ' ') }} kg</p>
            </div>
        </div>

        <!-- Production mensuelle -->
<div class="bg-white rounded-2xl shadow-sm p-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Production mensuelle</h3>
    <canvas id="productionChart" class="h-96"></canvas> <!-- Augmenté de h-80 à h-96 -->
</div>

<!-- Stock & Pertes par Variété -->
<div class="bg-white rounded-2xl shadow-sm p-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Stock & Pertes par Variété</h3>
    <canvas id="stockPertesChart" class="h-96"></canvas> <!-- Augmenté de h-80 à h-96 -->
</div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Répartition par Produit</h3>
                <canvas id="repartitionChart" class="h-80"></canvas>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Activité Récente</h3>
                <div class="space-y-4">
                    @foreach($activitesRecentes as $activite)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full bg-{{ $activite['couleur'] }}-100 flex items-center justify-center">
                                    <div class="w-6 h-6 bg-{{ $activite['couleur'] }}-500 rounded-full"></div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $activite['texte'] }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($activite['date'])->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <p class="font-bold text-{{ $activite['couleur'] }}-700">
                                {{ $activite['detail'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Production mensuelle
    new Chart('productionChart', {
        type: 'line',
        data: {
            labels: {!! json_encode($mois) !!},
            datasets: [
                {
                    label: 'Récoltes',
                    data: {!! json_encode($recoltesMensuelles) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.3)',
                    borderColor: '#16a34a',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Ventes',
                    data: {!! json_encode($ventesMensuelles) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.3)',
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

   // Stock & Pertes par Variété - AMÉLIORÉ avec double axe Y
    new Chart('stockPertesChart', {
        type: 'bar',
        data: {
            labels: {!! json_encode($varietesLabels) !!},
            datasets: [
                {
                    label: 'Stock',
                    data: {!! json_encode($stockData) !!},
                    backgroundColor: '#f59e0b',
                    borderColor: '#d97706',
                    borderWidth: 1,
                    yAxisID: 'y-stock'  // Axe gauche
                },
                {
                    label: 'Pertes',
                    data: {!! json_encode($pertesData) !!},
                    backgroundColor: '#ef4444',
                    borderColor: '#dc2626',
                    borderWidth: 1,
                    yAxisID: 'y-pertes'  // Axe droit
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' kg';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                'y-stock': {  // Axe gauche pour Stock
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Stock (kg)',
                        color: '#f59e0b',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#f59e0b',
                        font: {
                            size: 12
                        }
                    }
                },
                'y-pertes': {  // Axe droit pour Pertes
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false  // Pas de grille pour ne pas polluer
                    },
                    title: {
                        display: true,
                        text: 'Pertes (kg)',
                        color: '#ef4444',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#ef4444',
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Répartition par Produit
    new Chart('repartitionChart', {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($produitsLabels) !!},
            datasets: [{
                data: {!! json_encode($repartitionData) !!},
                backgroundColor: ['#16a34a', '#f59e0b', '#f97316', '#ef4444', '#8b5cf6', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
