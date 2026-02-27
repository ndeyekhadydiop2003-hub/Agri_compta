@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Enregistrer une perte</h2>

    <div class="bg-white rounded-2xl shadow p-8">

        {{-- Affichage des erreurs du trigger --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form action="{{ route('pertes.store') }}" method="POST">
            @csrf

            {{-- Variété --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Variété <span class="text-red-500">*</span></label>
                <select name="variete_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Sélectionnez une variété</option>
                    @foreach($varietes as $variete)
                        <option value="{{ $variete->variete_id }}" {{ old('variete_id') == $variete->variete_id ? 'selected' : '' }}>
                            {{ $variete->nom_variete }} ({{ $variete->produit->nom_produit }})
                        </option>
                    @endforeach
                </select>
                @error('variete_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date de perte --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de la perte <span class="text-red-500">*</span></label>
                <input type="date" name="date_perte" value="{{ old('date_perte', now()->format('Y-m-d')) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                @error('date_perte')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantité --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantité perdue (kg) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="quantite_kg" value="{{ old('quantite_kg') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                @error('quantite_kg')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Motif --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif de la perte</label>
                <select name="motif_perte" id="motif_perte" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option value="">Non spécifié</option>
                    <option value="Pourriture" {{ old('motif_perte') == 'Pourriture' ? 'selected' : '' }}>Pourriture</option>
                    <option value="Insectes" {{ old('motif_perte') == 'Insectes' ? 'selected' : '' }}>Insectes</option>
                    <option value="Mauvais temps" {{ old('motif_perte') == 'Mauvais temps' ? 'selected' : '' }}>Mauvais temps</option>
                    <option value="Transport" {{ old('motif_perte') == 'Transport' ? 'selected' : '' }}>Transport</option>
                    <option value="Mauvaise conservation" {{ old('motif_perte') == 'Mauvaise conservation' ? 'selected' : '' }}>Mauvaise conservation</option>
                    <option value="Autre" {{ old('motif_perte') == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            {{-- Motif autre --}}
            <div id="motif_autre_container" class="mt-4 {{ old('motif_perte') == 'Autre' ? '' : 'hidden' }}">
                <label for="motif_autre" class="block text-sm font-medium text-gray-700 mb-2">Précisez le motif <span class="text-red-500">*</span></label>
                <input type="text" name="motif_autre" id="motif_autre" value="{{ old('motif_autre') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('pertes.index') }}" class="px-6 py-3 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Annuler</a>
                <button type="submit" class="px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition shadow">
                    Enregistrer la perte
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectMotif = document.getElementById('motif_perte');
    const containerAutre = document.getElementById('motif_autre_container');
    const inputAutre = document.getElementById('motif_autre');

    function toggleAutre() {
        if (selectMotif.value === 'Autre') {
            containerAutre.classList.remove('hidden');
            inputAutre.setAttribute('required', 'required');
        } else {
            containerAutre.classList.add('hidden');
            inputAutre.removeAttribute('required');
            inputAutre.value = '';
        }
    }

    toggleAutre();
    selectMotif.addEventListener('change', toggleAutre);
});
</script>
@endsection
