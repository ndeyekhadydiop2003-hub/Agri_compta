@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('ventes.index') }}" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-green-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nouvelle Vente</h2>
            <p class="text-gray-500">Enregistrez une vente à partir d'une récolte disponible</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <form action="{{ route('ventes.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-sm text-red-700">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Sélection de la récolte -->
            <div>
                <label for="recolte_id" class="block text-sm font-semibold text-gray-700 mb-2">Récolte vendue</label>
                <div class="relative">
                    <select name="recolte_id" id="recolte_id" required
    class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none transition @error('recolte_id') border-red-500 @enderror">
    <option value="" disabled selected>Choisissez une récolte avec stock disponible</option>
    @foreach($recoltes as $recolte)
        @if($recolte->variete && $recolte->variete->produit)
            @php
                $vendu = $recolte->ventes->sum('quantite_vendue_kg') ?? 0;
                $disponible = $recolte->quantite_kg - $vendu;
            @endphp
            @if($disponible > 0)
                <option value="{{ $recolte->recolte_id }}">
                    {{ $recolte->variete->nom_variete }} ({{ $recolte->variete->produit->nom_produit }})
                    - {{ \Carbon\Carbon::parse($recolte->date_recolte)->format('d/m/Y') }}
                    - Stock disponible : {{ number_format($disponible, 2) }} kg
                </option>
            @endif
        @endif
    @endforeach
</select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('recolte_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date de vente -->
            <div>
                <label for="date_vente" class="block text-sm font-semibold text-gray-700 mb-2">Date de la vente</label>
                <input type="date" name="date_vente" id="date_vente" value="{{ old('date_vente', now()->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 transition @error('date_vente') border-red-500 @enderror">
                @error('date_vente')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantité vendue -->
            <div>
                <label for="quantite_vendue_kg" class="block text-sm font-semibold text-gray-700 mb-2">Quantité vendue (kg)</label>
                <input type="number" name="quantite_vendue_kg" id="quantite_vendue_kg" step="0.01" min="0.01" value="{{ old('quantite_vendue_kg') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 transition @error('quantite_vendue_kg') border-red-500 @enderror">
                @error('quantite_vendue_kg')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix unitaire -->
            <div>
                <label for="prix_unitaire_kg" class="block text-sm font-semibold text-gray-700 mb-2">Prix unitaire (FCFA/kg)</label>
                <input type="number" name="prix_unitaire_kg" id="prix_unitaire_kg" step="0.01" min="0" value="{{ old('prix_unitaire_kg') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 transition @error('prix_unitaire_kg') border-red-500 @enderror">
                @error('prix_unitaire_kg')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('ventes.index') }}" class="px-6 py-3 text-gray-500 hover:text-gray-700 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-green-800 text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:bg-green-700 transition">
                    Enregistrer la vente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
