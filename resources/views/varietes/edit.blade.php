@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('varietes.index') }}" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-green-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Modifier la variété</h2>
            <p class="text-gray-500">Modifiez les informations de la variété</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('varietes.update', $variete->variete_id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Message d'erreurs global -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Veuillez corriger les erreurs ci-dessous.</p>
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Produit Parent -->
            <div>
                <label for="produit_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Produit Parent
                </label>
                <div class="relative">
                    <select name="produit_id" id="produit_id" required
                        class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none transition @error('produit_id') border-red-500 @enderror">
                        <option value="" disabled>Sélectionnez un produit</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->produit_id }}" {{ old('produit_id', $variete->produit_id) == $produit->produit_id ? 'selected' : '' }}>
                                {{ $produit->nom_produit }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('produit_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nom de la Variété -->
            <div>
                <label for="nom_variete" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom de la Variété
                </label>
                <input type="text"
                       name="nom_variete"
                       id="nom_variete"
                       value="{{ old('nom_variete', $variete->nom_variete) }}"
                       required
                       placeholder="Ex : Chou vert, Oignon rouge..."
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('nom_variete') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Le nom doit être unique.</p>
                @error('nom_variete')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix de Vente Standard -->
            <div>
                <label for="prix_vente_standard" class="block text-sm font-semibold text-gray-700 mb-2">
                    Prix de Vente Standard (FCFA/kg)
                </label>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fas fa-coins"></i>
                    </span>
                    <input type="number"
                           name="prix_vente_standard"
                           id="prix_vente_standard"
                           value="{{ old('prix_vente_standard', $variete->prix_vente_standard) }}"
                           step="0.01"
                           required
                           placeholder="Ex : 850.00"
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('prix_vente_standard') border-red-500 @enderror">
                </div>
                <p class="mt-1 text-xs text-gray-500">Le prix doit être positif.</p>
                @error('prix_vente_standard')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-4 pt-6">
                <a href="{{ route('varietes.index') }}"
                   class="px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-green-800 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
