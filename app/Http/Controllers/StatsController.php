<?php

namespace App\Http\Controllers;

use App\Models\Recolte;
use App\Models\Vente;
use App\Models\Perte;
use App\Models\Produit;
use App\Models\Variete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Page principale des statistiques avec filtres
     */
    public function index(Request $request)
    {
        // Récupération des filtres
        $produitId = $request->input('produit_id');
        $varieteId = $request->input('variete_id');
        $periode  = $request->input('periode', 'mensuel'); // journalier, mensuel, annuel
        $dateDebut = $request->input('date_debut');
        $dateFin   = $request->input('date_fin');

        // Listes pour les filtres dropdown
        $produits = Produit::all();
        $varietes = $produitId ? Variete::where('PRODUIT_ID', $produitId)->get() : collect();

        // Construction de la requête de base pour récoltes
        $recoltesQuery = Recolte::selectRaw("
                variete_id,
                TO_CHAR(date_recolte, 'YYYY-MM-DD') as date_jour,
                TO_CHAR(date_recolte, 'YYYY-MM') as mois,
                TO_CHAR(date_recolte, 'YYYY') as annee,
                SUM(quantite_kg) as total_recolte_kg
            ")
            ->groupBy('variete_id', 'date_jour', 'mois', 'annee');

        // Appliquer les filtres
        if ($varieteId) {
            $recoltesQuery->where('variete_id', $varieteId);
        } elseif ($produitId) {
            $recoltesQuery->whereIn('variete_id', Variete::where('PRODUIT_ID', $produitId)->pluck('VARIETE_ID'));
        }

        if ($dateDebut) {
            $recoltesQuery->where('date_recolte', '>=', $dateDebut);
        }
        if ($dateFin) {
            $recoltesQuery->where('date_recolte', '<=', $dateFin);
        }

        // Récupération des données selon la période choisie
        $groupByField = $periode === 'journalier' ? 'date_jour' :
                       ($periode === 'annuel' ? 'annee' : 'mois');

        $statsRecoltes = (clone $recoltesQuery)
            ->groupBy('variete_id', $groupByField)
            ->selectRaw("$groupByField as periode, SUM(quantite_kg) as total_kg")
            ->orderBy('periode')
            ->get();

        // Ventes par période
        $statsVentes = DB::table('vente as v')
            ->join('recolte as r', 'v.recolte_id', '=', 'r.recolte_id')
            ->selectRaw("
                TO_CHAR(v.date_vente, ?) as periode,
                SUM(v.quantite_vendue_kg) as total_vendu_kg,
                SUM(v.montant_total) as total_ca
            ", [$periode === 'journalier' ? 'YYYY-MM-DD' : ($periode === 'annuel' ? 'YYYY' : 'YYYY-MM')])
            ->when($varieteId, fn($q) => $q->where('r.variete_id', $varieteId))
            ->when($produitId && !$varieteId, fn($q) => $q->whereIn('r.variete_id', Variete::where('PRODUIT_ID', $produitId)->pluck('VARIETE_ID')))
            ->when($dateDebut, fn($q) => $q->where('v.date_vente', '>=', $dateDebut))
            ->when($dateFin, fn($q) => $q->where('v.date_vente', '<=', $dateFin))
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

        // Pertes par période
        $statsPertes = Perte::selectRaw("
                TO_CHAR(date_perte, ?) as periode,
                SUM(quantite_kg) as total_perdu_kg
            ", [$periode === 'journalier' ? 'YYYY-MM-DD' : ($periode === 'annuel' ? 'YYYY' : 'YYYY-MM')])
            ->when($varieteId, fn($q) => $q->where('variete_id', $varieteId))
            ->when($produitId && !$varieteId, fn($q) => $q->whereIn('variete_id', Variete::where('PRODUIT_ID', $produitId)->pluck('VARIETE_ID')))
            ->when($dateDebut, fn($q) => $q->where('date_perte', '>=', $dateDebut))
            ->when($dateFin, fn($q) => $q->where('date_perte', '<=', $dateFin))
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

        // Calcul des invendus par période (récolte - ventes - pertes)
        $statsInvendus = $statsRecoltes->map(function ($recolte) use ($statsVentes, $statsPertes, $periode) {
            $periodeKey = $recolte->periode;

            $vente = $statsVentes->firstWhere('periode', $periodeKey);
            $perte = $statsPertes->firstWhere('periode', $periodeKey);

            return [
                'periode' => $periodeKey,
                'recolte_kg' => $recolte->total_kg,
                'vendu_kg'   => $vente->total_vendu_kg ?? 0,
                'perdu_kg'   => $perte->total_perdu_kg ?? 0,
                'invendu_kg' => $recolte->total_kg - ($vente->total_vendu_kg ?? 0) - ($perte->total_perdu_kg ?? 0),
                'ca'         => $vente->total_ca ?? 0,
            ];
        })->sortBy('periode');

        return view('stats.index', compact(
            'statsInvendus',
            'produits',
            'produitId',
            'varietes',
            'varieteId',
            'periode',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * Export CSV des statistiques (bonus pour la présentation)
     */
    public function exportCsv(Request $request)
    {
        // Réutilise la même logique que index(), mais génère un CSV
        // (Vous pouvez implémenter avec response()->streamDownload ou Laravel Excel)
        // Exemple simple :
        $stats = []; // Remplir avec les mêmes calculs que ci-dessus

        $filename = "stats_agricoles_" . now()->format('Y_m_d_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Période', 'Récolte (kg)', 'Vendu (kg)', 'Perdu (kg)', 'Invendu (kg)', 'CA (€)']);
            foreach ($stats as $row) {
                fputcsv($file, [
                    $row['periode'],
                    $row['recolte_kg'],
                    $row['vendu_kg'],
                    $row['perdu_kg'],
                    $row['invendu_kg'],
                    $row['ca']
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
