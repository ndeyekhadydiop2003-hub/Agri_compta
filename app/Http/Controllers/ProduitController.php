<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Important pour logger les erreurs
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class ProduitController extends Controller
{
    // Affichage de tous les produits
   public function index(Request $request)
{
    $search = $request->query('search');

    $query = Produit::query();

    if ($search) {
        $query->whereRaw('UPPER(nom_produit) LIKE ?', ['%' . strtoupper($search) . '%']);
    }

    $produits = $query->withCount('varietes') // Compte le nombre de variétés automatiquement
    ->orderBy('produit_id', 'desc')
    ->get();



    return view('produits.index', compact('produits', 'search'));
}


    // Formulaire d'ajout
    public function create()
    {
        return view('produits.create');
    }

    // --- MÉTHODE STORE CORRIGÉE ---
    public function store(Request $request)
    {
        // On valide 'nom_produit' (minuscules) qui vient du formulaire
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:100|unique:PRODUIT,NOM_PRODUIT',
        ], [
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'nom_produit.unique'   => 'Ce nom de produit existe déjà.',
        ]);

        try {
            // On utilise la transaction Laravel, c'est plus propre et plus sûr.
            // Elle gère automatiquement le COMMIT et le ROLLBACK.
            DB::transaction(function () use ($validated) {

                Produit::create([
                    'nom_produit' => $validated['nom_produit']
                ]);

            });

            return redirect()->route('produits.index')
                ->with('success', 'Produit "' . $validated['nom_produit'] . '" ajouté avec succès !');

        } catch (\Throwable $e) { // On attrape toutes les erreurs possibles

            // Le ROLLBACK est automatique avec DB::transaction.

            Log::error("Erreur lors de l'ajout du produit : " . $e->getMessage()); // On log l'erreur pour le debug

            return back()->withInput()
                ->with('error', 'Une erreur est survenue. Le produit n\'a pas pu être ajouté.');
        }
    }

    // Affichage d'un produit
    public function show(Produit $produit)
    {
        $produit->load('varietes');
        return view('produits.show', compact('produit'));
    }

    // Formulaire d'édition
    public function edit(Produit $produit)
    {
        return view('produits.edit', compact('produit'));
    }



 public function update(Request $request, Produit $produit)
{
    $validated = $request->validate([
        'nom_produit' => [
            'required',
            'string',
            'max:100',
            Rule::unique('PRODUIT', 'NOM_PRODUIT')->ignore($produit->produit_id, 'PRODUIT_ID'),
        ],
    ], [
        'nom_produit.required' => 'Le nom du produit est obligatoire.',
        'nom_produit.unique'   => 'Ce nom de produit est déjà utilisé.',
    ]);

    $produit->update([
        'nom_produit' => $validated['nom_produit']  // Laravel mappe automatiquement vers NOM_PRODUIT en Oracle
    ]);

    return redirect()->route('produits.index')
        ->with('success', 'Produit modifié avec succès !');
}
public function destroy(Produit $produit)
{
    try {
        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');

    } catch (\Illuminate\Database\QueryException $e) {

        $msg = $e->getMessage();

        if (str_contains($msg, 'ORA-20001')) {
            $error = 'Suppression impossible : produit utilisé par des variétés.';
        } else {
            $error = 'Erreur de base de données lors de la suppression.';
        }

        return redirect()->route('produits.index')
            ->with('error', $error);
    }
}

}
