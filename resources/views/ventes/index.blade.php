@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Ventes</h2>
            <p class="text-gray-700 mt-1">Suivi des ventes de vos récoltes</p>

            @if(session('success'))
                <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <a href="{{ route('ventes.create') }}"
           class="bg-green-800 hover:bg-green-900 text-white font-medium px-6 py-3 rounded-full flex items-center space-x-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouvelle vente</span>
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-6">
        <form method="GET" action="{{ route('ventes.index') }}" class="relative">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Rechercher par produit ou variété..."
                   class="w-full pl-12 pr-16 py-4 bg-gray-100 border-0 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition shadow-sm text-sm">
            <button type="submit" class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @if(request('search'))
                <a href="{{ route('ventes.index') }}" class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-800 text-lg">
                    &times;
                </a>
            @endif
        </form>
    </div>

    <!-- Chiffre d'affaires -->
    <div class="grid grid-cols-1 md:grid-cols-1 mb-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Chiffre d'affaires total</p>
            <p class="text-3xl font-bold text-green-800 mt-2">
                {{ number_format($totalCA, 2, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    <!-- Tableau des ventes -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date vente</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produit</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Variété</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Quantité (kg)</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Prix unitaire</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Montant total</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($ventes as $vente)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-800">
                            {{ optional($vente->recolte?->variete?->produit)->nom_produit ?? 'Produit inconnu' }}
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-800">
                            {{ optional($vente->recolte?->variete)->nom_variete ?? 'Variété inconnue' }}
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ number_format($vente->quantite_vendue_kg, 2, ',', ' ') }} kg
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ number_format($vente->prix_unitaire_kg, 2, ',', ' ') }} FCFA
                        </td>
                        <td class="px-8 py-5 text-lg font-bold text-green-800">
                            {{ number_format($vente->quantite_vendue_kg * $vente->prix_unitaire_kg, 2, ',', ' ') }} FCFA
                        </td>
                        <td class="px-8 py-5 text-right space-x-4">
                            <a href="{{ route('ventes.edit', $vente->vente_id) }}" class="text-amber-700 hover:text-amber-900 transition" title="Modifier">
                                ✏️
                            </a>
                            <form action="{{ route('ventes.destroy', $vente->vente_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-700 hover:text-red-900 transition" title="Supprimer" onclick="return confirm('Supprimer cette vente ?')">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-500">
                            Aucune vente enregistrée pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
