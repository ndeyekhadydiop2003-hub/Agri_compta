@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('recoltes.index') }}" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-green-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Modifier la Récolte</h2>
            <p class="text-gray-500">Mise à jour de la récolte #{{ $recolte->RECOLTE_ID }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Utilisation de l'URL directe pour éviter les erreurs de paramètres de route -->
        <form action="/recoltes/{{ $recolte->RECOLTE_ID }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Affichage des erreurs globales -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ $errors->first('error') ?? 'Veuillez corriger les erreurs ci-dessous.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Sélection de la Variété -->
            <div>
                <label for="VARIETE_ID" class="block text-sm font-semibold text-gray-700 mb-2">Variété récoltée</label>
                <div class="relative">
                    <select name="VARIETE_ID" id="VARIETE_ID" required
                        class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none transition @error('VARIETE_ID') border-red-500 @enderror">
                        @foreach($varietes as $variete)
                            <option value="{{ $variete->VARIETE_ID }}"
                                {{ (old('VARIETE_ID') ?? $recolte->VARIETE_ID) == $variete->VARIETE_ID ? 'selected' : '' }}>
                                {{ $variete->NOM_VARIETE }} ({{ $variete->produit->NOM_PRODUIT ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('VARIETE_ID')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date de Récolte -->
            <div>
                <label for="DATE_RECOLTE" class="block text-sm font-semibold text-gray-700 mb-2">Date de la Récolte</label>
                <input type="date" name="DATE_RECOLTE" id="DATE_RECOLTE"
                    value="{{ old('DATE_RECOLTE') ?? \Carbon\Carbon::parse($recolte->DATE_RECOLTE)->format('Y-m-d') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('DATE_RECOLTE') border-red-500 @enderror">
                @error('DATE_RECOLTE')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantité en KG -->
            <div>
                <label for="QUANTITE_KG" class="block text-sm font-semibold text-gray-700 mb-2">Quantité récoltée (kg)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fas fa-weight-hanging"></i>
                    </span>
                    <input type="number" name="QUANTITE_KG" id="QUANTITE_KG"
                        value="{{ old('QUANTITE_KG') ?? $recolte->QUANTITE_KG }}" step="0.01" required
                        placeholder="0.00"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('QUANTITE_KG') border-red-500 @enderror">
                </div>
                @error('QUANTITE_KG')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('recoltes.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-green-800 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Mettre à jour la récolte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


