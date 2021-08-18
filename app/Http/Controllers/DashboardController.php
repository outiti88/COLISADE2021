<?php

namespace App\Http\Controllers;

use App\Commande;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function dash()
    {

        if (!Gate::denies('nouveau') || Gate::denies('valide')) {
            return redirect()->route('home');
        }
        if (Gate::denies('client-admin')) return redirect()->route('commandes.index');


        $users = [];
        $topCmdLivr = null;

        $todayCmd = 0;
        $lastdayCmd = 0;
        $ca = 0;
        $caFacturer = 0;
        $cmd = 0;
        $caLastMounth = 0;
        $caNonfacturer = 0;
        $caPercent = 0;

        $tabTotal = [];

        // dd(Carbon::now()->dayOfWeek == Carbon::TUESDAY);

        $chart2 = array(0,0,0,0,0,0,0 );


        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $c_total = DB::table('commandes')->whereIn('statut', ['En cours', 'Modifiée','Relancée'])->where('deleted_at', NULL);
        $l_total = DB::table('commandes')->where('statut', 'livré')->where('deleted_at', NULL);
        $r_total = DB::table('commandes')->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])->where('deleted_at', NULL);
        $e_total = DB::table('commandes')->where('statut', 'Expidiée')->where('deleted_at', NULL);
        $commandeGesture = Commande::where('deleted_at', NULL);
        //fournisseur
        if (Gate::denies('ramassage-commande')) {

        $commandePerDate = Commande::where('deleted_at', NULL)->where('user_id', Auth::user()->id)
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as n'))
        ->groupBy('date')
        ->get();

            $totalOrdersForPersentage = Commande::where('deleted_at',NULL)->where('user_id', Auth::user()->id)->count();
            $en = Commande::where('deleted_at',NULL)->where('statut','En cours')->where('user_id', Auth::user()->id)->count();
            $ex = Commande::where('deleted_at',NULL)->where('statut','Reporté')->where('user_id', Auth::user()->id)->count();
            $li = Commande::where('deleted_at',NULL)->where('statut','Livré')->where('user_id', Auth::user()->id)->count();
            $re = Commande::where('deleted_at',NULL)
                ->where(function ($q) {
                $q->where('statut' , 'refusée')
                ->where('user_id', Auth::user()->id)
                ->where('facturer', '=' , 0);
            })
            ->orWhere(function ($q) {
                $q->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])
                ->where('facturer', '>' , 0)
                ->where('user_id', Auth::user()->id);
            })
            ->count();

            //todays order
            $todayCmd = DB::table('commandes')->where('deleted_at', NULL)->whereDate('created_at', Carbon::now())->where('user_id', Auth::user()->id)->count();

            //last days order
            $lastdayCmd = DB::table('commandes')->where('deleted_at', NULL)->whereDate('created_at', Carbon::yesterday())->where('user_id', Auth::user()->id)->count();

            //Grands chiffres et pourcentage
            $commandeGesture = $commandeGesture
                ->select(DB::raw('sum(montant) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->where('user_id', Auth::user()->id);

            //Ca total
            $ca = $commandeGesture->first()->m - $commandeGesture->first()->p;

            //Pourcentage mois dernier
            $commandeLastMounth =  DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(montant) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->where('user_id', Auth::user()->id)->whereMonth('created_at', (date('m') - 1))->first();
            $caLastMounth = $commandeLastMounth->m - $commandeLastMounth->p;

            $commandeCurrentMounth =  DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(montant) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->where('user_id', Auth::user()->id)->whereMonth('created_at', (date('m')))->first();
            $caCurrentMounth = $commandeCurrentMounth->m - $commandeCurrentMounth->p;

            if ($caLastMounth != 0)
                $caPercent = number_format((($caCurrentMounth - $caLastMounth) / $caLastMounth) * 100, 2, '.', '');


            //Ca facturer
            $commandeGesturef = $commandeGesture->where('facturer', '>', '0');
            $caFacturer = $commandeGesturef->first()->m - $commandeGesturef->first()->p;

            //montant non facturer
            $caNonfacturer = $ca - $caFacturer;






            //statistique des etats de commandes
            $c_total = $c_total->where('user_id', Auth::user()->id);
            $l_total = $l_total->where('user_id', Auth::user()->id);
            $r_total = $r_total->where('user_id', Auth::user()->id);
            $e_total = $e_total->where('user_id', Auth::user()->id);

            //top 5 des clients
            $topCmd = DB::table('commandes')
                ->select(DB::raw('nom , count(*) as cmd , sum(colis) as colis , sum(montant) as m'))
                ->where('deleted_at', NULL)
                ->where('user_id', Auth::user()->id)
                ->where('statut', 'livré')
                ->groupBy('telephone')
                ->orderBy('m', 'DESC')
                ->take(5)->get();


            $cmdRefuser = DB::table('commandes')->where('deleted_at', NULL)
                ->where('user_id', Auth::user()->id)
                ->select(DB::raw('count(numero) as m '))
                ->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])
                ->first()->m;

            $cmdliv = DB::table('commandes')->where('deleted_at', NULL)
                ->where('user_id', Auth::user()->id)
                ->select(DB::raw('count(numero) as m '))
                ->first()->m;



            if ($cmdliv != 0)
                $cmdLivRefuser =  number_format(($cmdRefuser / $cmdliv) * 100, 2, '.', '');
            else $cmdLivRefuser = 0;
        }

        //Administrateur
        else {

            $commandePerDate = Commande::where('deleted_at', NULL)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as n'))
            ->groupBy('date')
            ->get();
            $totalOrdersForPersentage = Commande::where('deleted_at',NULL)->count();
            $en = Commande::where('deleted_at',NULL)->where('statut','En cours')->count();
            $ex = Commande::where('deleted_at',NULL)->where('statut','Reporté')->count();
            $li = Commande::where('deleted_at',NULL)->where('statut','Livré')->count();
            $re = Commande::where('deleted_at',NULL)
            ->where(function ($q) {
                $q->where('statut' , 'refusée')
                    ->where('facturer', '=' , 0);
            })
            ->orWhere(function ($q) {
                $q->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])
                ->where('facturer', '>' , 0)
                ->where('deleted_at', NULL);
            })
            ->count();

            //todays order
            $todayCmd = DB::table('commandes')->where('deleted_at', NULL)->whereDate('created_at', Carbon::now())->count();

            //last days order
            $lastdayCmd = DB::table('commandes')->where('deleted_at', NULL)->whereDate('created_at', Carbon::yesterday())->count();

            //Grands chiffres et pourcentage
            $commandeGesture = DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(livreurPart) as m , sum(prix) as p'))
                ->where('statut', 'Livré');

            //Commandes Refusées
            $commandeRefuser = DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('count(numero) as c ,sum(livreurPart) as p'))

                ->where(function ($q) {
                    $q->where('statut' , 'refusée')
                        ->where('facturer', '=' , 0);
                })
                ->orWhere(function ($q) {
                    $q->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])
                    ->where('facturer', '>' , 0);
                })

                ->first();

            //Ca total
            $ca = $commandeGesture->first()->p - $commandeGesture->first()->m + $commandeRefuser->c * 10 - $commandeRefuser->p;


            //Pourcentage mois dernier
            $commandeLastMounth =  DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(livreurPart) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->whereMonth('created_at', (date('m') - 1))->first();
            $caLastMounth = $commandeLastMounth->p - $commandeLastMounth->m;


            $commandeCurrentMounth =  DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(livreurPart) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->whereMonth('created_at', (date('m')))->first();
            $caCurrentMounth = $commandeCurrentMounth->p - $commandeCurrentMounth->m;


            if ($caLastMounth != 0)
                $caPercent = number_format((($caCurrentMounth - $caLastMounth) / $caLastMounth) * 100, 2, '.', '');

            //Chiffre d'affaire des commandes livrées
            $caFacturer  = DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(livreurPart) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->first()->p;

            //part des livreurs (commandes livrées)
            $caNonfacturer = DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('sum(livreurPart) as m , sum(prix) as p'))
                ->where('statut', 'Livré')->first()->m;



                $cmdRefuser = DB::table('commandes')->where('deleted_at', NULL)
                ->select(DB::raw('count(numero) as m , sum(livreurPart) as p , sum(refusePart) as r'))
                ->where(function ($q) {
                    $q->where('statut' , 'refusée')
                        ->where('facturer', '=' , 0);
                })
                ->orWhere(function ($q) {
                    $q->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])
                    ->where('facturer', '>' , 0)
                    ->where('deleted_at', NULL);
                })
                ->first();

            $cmdLivRefuser = $cmdRefuser->p;
            $cmdRefuser = $cmdRefuser->r;




            //Top 5 Cilent final
            $topCmd = DB::table('commandes')
                ->select(DB::raw('user_id, count(*) as cmd'))
                ->groupBy('user_id')
                ->orderBy('cmd', 'DESC')
                ->limit(5)->get();

             $topCmdLivr = DB::table('commandes')
                ->select(DB::raw('user_id, count(*) as cmd'))
                ->where('statut', 'livré')
                ->groupBy('user_id')
                ->orderBy('cmd', 'DESC')
                ->limit(5)->get();

            foreach ($topCmd as $index => $commande) {
                if (!empty(User::withTrashed()->find($commande->user_id)))
                    $users[] =  User::withTrashed()->find($commande->user_id);
            }
        } //end Admin

        //dd($users);

        //Statuts des commandes
        $c = $c_total->orderBy('updated_at', 'DESC')->limit(1)->get()->first();
        $l = $l_total->orderBy('updated_at', 'DESC')->limit(1)->get()->first();
        $r = $r_total->orderBy('updated_at', 'DESC')->limit(1)->get()->first();
        $e = $e_total->orderBy('updated_at', 'DESC')->limit(1)->get()->first();

        $tab =
            array(
                'en_cours' => array(
                    'nbr' => $c_total->whereDate('updated_at', Carbon::now())->count(),
                    'date' => ($c === NULL) ? "" : $c->updated_at
                ),
                'expidie' => array(
                    'nbr' => $e_total->whereDate('updated_at', Carbon::now())->count(),
                    'date' => ($e === NULL) ? "" : $e->updated_at
                ),
                'livré' => array(
                    'nbr' => $l_total->whereDate('updated_at', Carbon::now())->count(),
                    'date' => ($l === NULL) ? "" : $l->updated_at
                ),
                'retour' => array(
                    'nbr' => $r_total->whereDate('updated_at', Carbon::now())->count(),
                    'date' => ($r === NULL) ? "" : $r->updated_at
                )
            );




            $caPercent = number_format((($caCurrentMounth - $caLastMounth) / $caLastMounth) * 100, 2, '.', '');

            $tabTotal =
            array(

                'en_cours' => array(
                    'nbr' => $en,
                    'percentage' => ($totalOrdersForPersentage == 0) ? 0 :  number_format($en*100 /$totalOrdersForPersentage,2,'.','')
                ),
                'reporté' => array(
                    'nbr' => $ex,
                    'percentage' => ($totalOrdersForPersentage == 0) ? 0 :  number_format($ex *100 /$totalOrdersForPersentage,2,'.','')
                ),
                'livré' => array(
                    'nbr' => $li,
                    'percentage' => ($totalOrdersForPersentage == 0) ? 0 :  number_format($li *100 /$totalOrdersForPersentage,2,'.','')
                ),
                'refusé' => array(
                    'nbr' => $re,
                    'percentage' => ($totalOrdersForPersentage == 0) ? 0 :  number_format($re *100 /$totalOrdersForPersentage,2,'.','')
                )
            );



        //Chart commandes livré vs commandes retour
        $chart =
            array(
                'livre' => array(),
                'retour' => array()
            );
        $chart1 =
            array(
                'livrer' => array(),
                'nonLivrer'=> array()
            );

        if (Gate::denies('ramassage-commande')) {
            for ($i = 1; $i <= 12; $i++) {
                $getCmd = DB::table('commandes')->where('statut', 'livré')->where('deleted_at', NULL)->whereMonth('created_at', ($i))->where('user_id', Auth::user()->id);
                $chart['livre'][] = $getCmd->sum('montant') - $getCmd->sum('prix');
                $chart['retour'][] = DB::table('commandes')->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])->where('deleted_at', NULL)->whereMonth('created_at', ($i))->where('user_id', Auth::user()->id)->sum('montant');

                $chart1['livrer'][] = DB::table('commandes')->where('statut', 'livré')->where('deleted_at', NULL)->whereMonth('created_at', ($i))->where('user_id', Auth::user()->id)->count();
                $chart1['nonLivrer'][] = DB::table('commandes')->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])->where('deleted_at', NULL)->whereMonth('created_at', ($i))->where('user_id', Auth::user()->id)->count();

            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $getCmd = DB::table('commandes')->where('statut', 'livré')->where('deleted_at', NULL)->whereMonth('created_at', ($i));
                $chart['livre'][] = $getCmd->sum('prix') - $getCmd->sum('livreurPart');
                $chart['retour'][] = DB::table('commandes')->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])->where('deleted_at', NULL)->whereMonth('created_at', ($i))->sum('prix');

                $chart1['livrer'][] = DB::table('commandes')->where('statut', 'livré')->where('deleted_at', NULL)->whereMonth('created_at', ($i))->count();
                $chart1['nonLivrer'][] = DB::table('commandes')->whereIn('statut', ['retour en stock', 'retour', 'Refusée', 'Annulée', 'Injoignable', 'Pas de Réponse'])->where('deleted_at', NULL)->whereMonth('created_at', ($i))->count();

            }
        }

        foreach ($commandePerDate as $key => $cmd) {
            $chart2[Carbon::parse($cmd->date)->dayOfWeek] += $cmd->n;
        }

        foreach ($chart2 as $key => $ch) {
            $chart2[$key] = ($totalOrdersForPersentage == 0) ? 0 :  number_format($chart2[$key]*100/$totalOrdersForPersentage,2,'.','') ;
        }


        $chart2 = json_encode($chart2, JSON_NUMERIC_CHECK);

        $livre = json_encode($chart['livre'], JSON_NUMERIC_CHECK);
        $retour = json_encode($chart['retour'], JSON_NUMERIC_CHECK);

        $livrerChart1 = json_encode($chart1['livrer'], JSON_NUMERIC_CHECK);
        $nonLivrerChart1 = json_encode($chart1['nonLivrer'], JSON_NUMERIC_CHECK);

        return view('dashboard', [
            'nouveau' => $nouveau, 'tab' => $tab,
            'livre' => $livre, 'retour' => $retour,
            'topCmds' => $topCmd, 'users' => $users,
            'ca' => $ca, 'caFacturer' => $caFacturer, 'caNonfacturer' => $caNonfacturer, 'caPercent' => $caPercent,
            'cmdLivRefuser' => $cmdLivRefuser, 'cmdRefuser' => $cmdRefuser,
            'todayCmd' => $todayCmd, 'lastdayCmd' => $lastdayCmd ,'topCmdLivr'=>$topCmdLivr,
            'tabTotal' => $tabTotal, 'livrerChart1'=> $livrerChart1, 'nonLivrerChart1' => $nonLivrerChart1, 'chart2' => $chart2
        ]);
    }
}
