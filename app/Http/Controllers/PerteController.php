<?php

namespace App\Http\Controllers;

use App\Models\Perte;
use App\Models\Variete;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Throwable;

class PerteController extends Controller
{

    public function index()
{
    // Récupération des pertes avec pagination et relations
    $pertes = Perte::with('variete.produit')
                   ->orderBy('perte_id', 'desc')
                   ->paginate(15);

    return view('pertes.index', compact('pertes'));
}
    public function create()
    {
        $varietes = Variete::with('produit')->orderBy('variete_id', 'asc')->get();
        return view('pertes.create', compact('varietes'));
    }

    public function store(Request $request)
{
    $request->validate([
        'variete_id'  => 'required|exists:variete,variete_id',
        'date_perte'  => 'required|date',
        'quantite_kg' => 'required|numeric|gt:0',
        'motif_perte' => 'nullable|string|max:255',
    ]);

    try {
        Perte::create($request->all());
        return redirect()->route('pertes.index')->with('success', 'Perte enregistrée avec succès.');

    } catch (\Throwable $e) {
        $error = $this->extractOraclePerteError($e);
        return back()->withInput()->with('error', $error);
    }
}

public function update(Request $request, $perte_id)
{
    $perte = Perte::findOrFail($perte_id);

    $request->validate([
        'variete_id'  => 'required|exists:variete,variete_id',
        'date_perte'  => 'required|date',
        'quantite_kg' => 'required|numeric|gt:0',
        'motif_perte' => 'nullable|string|max:255',
    ]);

    try {
        $perte->update($request->all());
        return redirect()->route('pertes.index')->with('success', 'Perte modifiée avec succès.');

    } catch (\Throwable $e) {
        $error = $this->extractOraclePerteError($e);
        return back()->withInput()->with('error', $error);
    }
}

/**
 * Extrait le message du trigger Oracle
 */
private function extractOraclePerteError(\Throwable $e): string
{
    $msg = $e->getMessage();

    if (str_contains($msg, 'ORA-20111')) {
        return 'La quantité perdue dépasse le stock disponible pour cette variété.';
    } elseif (str_contains($msg, 'ORA-21005')) {
        return 'La suppression d’une perte est interdite.';
    } elseif (str_contains($msg, 'ORA-20004')) {
        return 'Un motif est obligatoire pour une perte > 50 kg.';
    } else {
        return 'Erreur inattendue : ' . $msg;
    }
}

    public function edit($perte_id)
    {
        $perte = Perte::with('variete.produit')->findOrFail($perte_id);
        $varietes = Variete::with('produit')->orderBy('variete_id', 'asc')->get();
        return view('pertes.edit', compact('perte', 'varietes'));
    }

    public function destroy($perte_id)
    {
        $perte = Perte::findOrFail($perte_id);

        try {
            $perte->delete();
            return redirect()->route('pertes.index')
                             ->with('success', 'Perte supprimée (si autorisé).');
        } catch (QueryException $e) {
            $error = $this->handleTriggerPerte($e);
            return redirect()->route('pertes.index')
                             ->with('error', $error);
        }
    }

    public function show($perte_id)
{
    $perte = Perte::with('variete.produit')->findOrFail($perte_id);
    return view('pertes.show', compact('perte'));
}

}
