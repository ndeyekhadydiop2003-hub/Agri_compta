@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Récoltes</h2>
            <p class="text-gray-600 mt-1">Suivi des récoltes de votre exploitation</p>
        </div>

        <a href="{{ route('recoltes.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white font-medium px-6 py-3 rounded-full flex items-center space-x-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouvelle récolte</span>
        </a>
    </div>

    <!-- Stats KPI -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500">Total récolté</p>
            <p class="text-3xl font-bold text-green-700 mt-2">
                {{ number_format($totalHarvests, 0, ',', ' ') }} kg
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500">Nombre de récoltes</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $harvestsCount }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500">Moyenne par récolte</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ number_format($averageHarvest, 0, ',', ' ') }} kg
            </p>
        </div>
    </div>

    <!-- Tableau des récoltes -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Liste des récoltes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité (kg)</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recoltes as $recolte)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-5 text-sm text-gray-900">#{{ $recolte->recolte_id }}</td>
                            <td class="px-8 py-5 text-sm text-gray-600">
                                {{ $recolte->date_recolte ? \Carbon\Carbon::parse($recolte->date_recolte)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-8 py-5 text-sm text-gray-900">
                                {{ optional($recolte->variete?->produit)->nom_produit ?? 'Produit inconnu' }}
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-gray-900">
                                {{ optional($recolte->variete)->nom_variete ?? 'Variété inconnue' }}
                            </td>
                            <td class="px-8 py-5 text-lg font-bold text-green-700">
                                {{ number_format($recolte->quantite_kg, 0, ',', ' ') }} kg
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('recoltes.edit', $recolte->recolte_id) }}" class="text-amber-600 hover:text-amber-900" title="Modifier">
                                        ✏️
                                    </a>
                                    <form action="{{ route('recoltes.destroy', $recolte->recolte_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Supprimer cette récolte ?')">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-500">
                                Aucune récolte enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



