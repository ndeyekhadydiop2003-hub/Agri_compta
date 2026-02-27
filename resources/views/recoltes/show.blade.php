@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('recoltes.index') }}" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-green-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Détails de la Récolte</h2>
            {{-- CORRECTION : On utilise la clé en minuscule --}}
            <p class="text-gray-500">Récolte #{{ $recolte->recolte_id }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 space-y-4">
            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-500">ID de la Récolte</span>
                <span class="font-bold text-gray-800">#{{ $recolte->recolte_id }}</span>
            </div>
            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-500">Date de la récolte</span>
                {{-- CORRECTION : On utilise la clé en minuscule --}}
                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($recolte->date_recolte)->format('d F Y') }}</span>
            </div>
            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-500">Variété</span>
                {{-- CORRECTION : On utilise les clés en minuscules --}}
                <span class="font-bold text-green-700">{{ $recolte->variete?->nom_variete ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-500">Produit</span>
                {{-- CORRECTION : On utilise les clés en minuscules --}}
                <span class="font-bold text-gray-800">{{ $recolte->variete?->produit?->nom_produit ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center pb-4">
                <span class="text-gray-500">Quantité Récoltée</span>
                {{-- CORRECTION : On utilise la clé en minuscule --}}
                <span class="font-bold text-2xl text-green-800">{{ number_format($recolte->quantite_kg, 2, ',', ' ') }} kg</span>
            </div>
        </div>
        <div class="bg-gray-50 p-6 flex justify-end space-x-4">
            <a href="{{ route('recoltes.index') }}" class="px-6 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 transition">
                Retour à la liste
            </a>
            <a href="{{ route('recoltes.edit', $recolte) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition">
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection

