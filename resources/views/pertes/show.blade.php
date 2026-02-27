@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-6">
        <!-- En-tête -->
        <div class="mb-8">
            <a href="{{ route('pertes.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Détails de la Perte</h1>
            <p class="text-gray-600 mt-2">Informations complètes sur la perte enregistrée.</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-lg shadow p-8">
            <!-- Informations principales -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 uppercase tracking-wide">ID de la Perte</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">#{{ $perte->perte_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 uppercase tracking-wide">Date de Perte</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $perte->date_perte ? \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section : Produit et Variété -->
            <div class="px-8 py-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Produit et Variété</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Produit</p>
                        <p class="text-lg font-medium text-gray-900 mt-1">
                            {{ optional($perte->variete?->produit)->nom_produit ?? 'Produit inconnu' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Variété</p>
                        <p class="text-lg font-medium text-gray-900 mt-1">
                            {{ optional($perte->variete)->nom_variete ?? 'Variété inconnue' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section : Quantité et Motif -->
            <div class="px-8 py-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails de la Perte</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Quantité Perdue</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ number_format((float)$perte->quantite_kg, 2, ',', ' ') }} kg
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Motif</p>
                        <p class="text-lg font-medium text-gray-900 mt-1">
                            {{ $perte->motif_perte ?? 'Non spécifié' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="px-8 py-6 border-t border-gray-200 flex justify-end space-x-4">
                <a href="{{ route('pertes.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Retour à la liste
                </a>
                <a href="{{ route('pertes.edit', $perte->perte_id) }}" class="px-6 py-3 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors">
                    Modifier
                </a>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-sm font-medium text-blue-900 mb-2">ℹ️ Informations Importantes</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Cette perte a été enregistrée pour des raisons de traçabilité.</li>
                <li>• La suppression est interdite par la base de données.</li>
                <li>• Contactez l’administrateur pour toute modification.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
