<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\Variete;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $produits = Produit::orderBy('nom_produit')->get();

        // === KPI principaux via fonctions Oracle ===
        $totalRecoltes   = DB::selectOne("SELECT fn_total_recoltes_periode(TO_DATE('2025-01-01','YYYY-MM-DD'), TO_DATE('2025-12-31','YYYY-MM-DD')) as total FROM DUAL")->total;
        $totalVentes     = DB::selectOne("SELECT fn_total_ventes_periode(TO_DATE('2025-01-01','YYYY-MM-DD'), TO_DATE('2025-12-31','YYYY-MM-DD')) as total FROM DUAL")->total;
        $chiffreAffaires = DB::selectOne("SELECT fn_ca_periode(TO_DATE('2025-01-01','YYYY-MM-DD'), TO_DATE('2025-12-31','YYYY-MM-DD')) as ca FROM DUAL")->ca;
        $totalPertes     = DB::selectOne("SELECT fn_total_pertes_periode(TO_DATE('2025-01-01','YYYY-MM-DD'), TO_DATE('2025-12-31','YYYY-MM-DD')) as total FROM DUAL")->total;

        // === Stock global en temps réel ===
        $stockActuel = DB::selectOne("SELECT fn_stock_global() as stock FROM DUAL")->stock;

        // Variations (pas de données précédentes pour l’instant)
        $variationRecoltes = null;
        $variationVentes   = null;
        $variationCA       = null;

        // === Production mensuelle ===
        $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $recoltesMensuelles = [];
        $ventesMensuelles = [];
        for ($m = 1; $m <= 12; $m++) {
            $debutMois = "2025-" . str_pad($m, 2, '0', STR_PAD_LEFT) . "-01";
            $finMois   = Carbon::create(2025, $m, 1)->endOfMonth()->format('Y-m-d');

            $recoltesMensuelles[] = DB::selectOne("
                SELECT fn_total_recoltes_periode(TO_DATE(?, 'YYYY-MM-DD'), TO_DATE(?, 'YYYY-MM-DD')) as total FROM DUAL
            ", [$debutMois, $finMois])->total;

            $ventesMensuelles[] = DB::selectOne("
                SELECT fn_total_ventes_periode(TO_DATE(?, 'YYYY-MM-DD'), TO_DATE(?, 'YYYY-MM-DD')) as total FROM DUAL
            ", [$debutMois, $finMois])->total;
        }

        // === Stock & pertes par variété ===
        $varietes = Variete::orderBy('nom_variete')->get();
        $varietesLabels = [];
        $stockData = [];
        $pertesData = [];

        foreach ($varietes as $variete) {
            $stock = DB::selectOne("SELECT fn_calcul_stock_variete(?) as total FROM DUAL", [$variete->variete_id])->total;
            $perte = DB::selectOne("SELECT fn_total_pertes_variete(?) as total FROM DUAL", [$variete->variete_id])->total;

            $varietesLabels[] = $variete->nom_variete;
            $stockData[] = $stock;
            $pertesData[] = $perte;
        }

        // === Répartition par produit (nombre de variétés, total récolte, CA) ===
        $produitsStatsLabels = [];
        $nbVarietesData = [];
        $totalRecolteData = [];
        $caData = [];

        foreach ($produits as $produit) {
            $nbVarietes = DB::selectOne("SELECT fn_nb_varietes_produit(?) as total FROM DUAL", [$produit->produit_id])->total;
            $totalRecolte = DB::selectOne("SELECT fn_total_recolte_produit(?) as total FROM DUAL", [$produit->produit_id])->total;
            $ca = DB::selectOne("SELECT fn_ca_produit(?) as total FROM DUAL", [$produit->produit_id])->total;

            $produitsStatsLabels[] = $produit->nom_produit;
            $nbVarietesData[] = $nbVarietes;
            $totalRecolteData[] = $totalRecolte;
            $caData[] = $ca;
        }

        // On crée les variables attendues par Blade
        $produitsLabels = $produitsStatsLabels;
        $repartitionData = $totalRecolteData;

        // === Activité récente ===
        $activites = collect();

        \App\Models\Recolte::with('variete')
            ->orderByDesc('date_recolte')
            ->limit(3)
            ->get()
            ->each(function ($r) use ($activites) {
                $activites->push([
                    'date' => $r->date_recolte,
                    'texte' => $r->variete?->nom_variete ?? 'Variété inconnue',
                    'detail' => number_format($r->quantite_kg, 0, ',', ' ') . ' kg récoltés',
                    'couleur' => 'green'
                ]);
            });

        \App\Models\Vente::with('recolte.variete')
            ->orderByDesc('date_vente')
            ->limit(10)
            ->get()
            ->each(function ($v) use ($activites) {
                $activites->push([
                    'date' => $v->date_vente,
                    'texte' => $v->recolte?->variete?->nom_variete ?? 'Variété inconnue',
                    'detail' => number_format($v->quantite_vendue_kg * $v->prix_unitaire_kg, 0, ',', ' ') . ' FCFA',
                    'couleur' => 'yellow'
                ]);
            });

        \App\Models\Perte::with('variete')
            ->orderByDesc('date_perte')
            ->limit(3)
            ->get()
            ->each(function ($p) use ($activites) {
                $activites->push([
                    'date' => $p->date_perte,
                    'texte' => $p->variete?->nom_variete ?? 'Variété inconnue',
                    'detail' => number_format($p->quantite_kg, 0, ',', ' ') . ' kg perdus',
                    'couleur' => 'red'
                ]);
            });

        $activitesRecentes = $activites->sortByDesc('date')->take(10);

        // === Retour à la vue ===
        return view('dashboard.index', compact(
            'produits',
            'totalRecoltes', 'variationRecoltes',
            'totalVentes', 'variationVentes',
            'chiffreAffaires', 'variationCA',
            'stockActuel', 'totalPertes',
            'mois', 'recoltesMensuelles', 'ventesMensuelles',
            'varietesLabels', 'stockData', 'pertesData',
            'produitsLabels', 'repartitionData',
            'activitesRecentes'
        ));
    }
}
