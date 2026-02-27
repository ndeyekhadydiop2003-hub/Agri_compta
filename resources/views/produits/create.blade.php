@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un nouveau produit</h2>

    <div class="bg-white rounded-xl shadow p-8">
        <form action="{{ route('produits.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="NOM_PRODUIT" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom du produit
                </label>
                <input type="text"
                    name="nom_produit"
                    id="nom_produit"
                    value="{{ old('nom_produit') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                @error('nom_produit')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('produits.index') }}"
                   class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow">
                    Ajouter le produit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
