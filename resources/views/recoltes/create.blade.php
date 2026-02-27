@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('recoltes.index') }}" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-green-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nouvelle Récolte</h2>
            <p class="text-gray-500">Enregistrer une nouvelle récolte</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('recoltes.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Bloc global d'erreurs (seul endroit où les erreurs s'affichent) -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Veuillez corriger les erreurs ci-dessous.</p>

                        </div>
                    </div>
                </div>
            @endif

            <!-- Sélection de la Variété -->
            <div>
                <label for="variete_id" class="block text-sm font-semibold text-gray-700 mb-2">Variété récoltée</label>
                <div class="relative">
                    <select name="variete_id" id="variete_id" required
                        class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none transition">
                        <option value="" disabled selected>Choisir une variété...</option>
                        @foreach($varietes as $variete)
                            <option value="{{ $variete->variete_id }}" {{ old('variete_id') == $variete->variete_id ? 'selected' : '' }}>
                                {{ $variete->nom_variete }} ({{ $variete->produit?->nom_produit ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Date de Récolte -->
            <div>
                <label for="date_recolte" class="block text-sm font-semibold text-gray-700 mb-2">Date de la Récolte</label>
                <input type="date" name="date_recolte" id="date_recolte" value="{{ old('date_recolte') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
            </div>

            <div class="mt-2">
                @error('date_recolte')
                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantité en KG -->
            <div>
                <label for="quantite_kg" class="block text-sm font-semibold text-gray-700 mb-2">Quantité récoltée (kg)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fas fa-weight-hanging"></i>
                    </span>
                    <input type="number" name="quantite_kg" id="quantite_kg" value="{{ old('quantite_kg') }}" step="0.01" required
                        placeholder="0.00"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                </div>

                <div class="mt-2">
                    @error('quantite_kg')
                        <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('recoltes.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-green-800 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Enregistrer la récolte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



