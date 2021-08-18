<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Http\Requests\StoreCommande;
use App\Notifications\newCommande;
use App\Notifications\statutChange;
use App\Statut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use App\Produit;
use App\Stock;
use Illuminate\Http\Request;

class ProduitController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = null;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ecom']);
        })->orderBy('name')->get();
        $users = [];
        $stock = [];
        if (!Gate::denies('ramassage-commande')) {
            //session administrateur donc on affiche tous les commandes
            $total = DB::table('produits')->count();
            $produits = DB::table('produits')->orderBy('created_at', 'DESC')->paginate(10);
            foreach ($produits as $produit) {
                if (!empty(User::withTrashed()->find($produit->user_id)))
                    $users[] =  User::withTrashed()->find($produit->user_id);
            }
            //dd($clients[0]->id);
        } else {
            $produits = DB::table('produits')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = DB::table('produits')->where('user_id', Auth::user()->id)->count();
        }
        foreach ($produits as $produit) {
            $dbStock = DB::table('stocks')->where('produit_id', $produit->id)->first();
            $stock[] =  $dbStock;
        }

        return view('produit.index', [
            'produits' => $produits, 'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'stock' => $stock,
            'data' => $data
        ]);
    }




    public function filter(Request $request)
    {

        $data = $request->all();
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ecom']);
        })->orderBy('name')->get();
        $produits = DB::table('produits')->orderBy('created_at', 'DESC');
        $users = [];
        $stock = [];
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();



        if ($request->filled('categorie') && $request->categorie != "Tous") {
            $produits->where('categorie', $request->categorie);
        }

        if ($request->filled('libelle')) {
            $produits->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if (!Gate::denies('ramassage-commande')) {
            if ($request->filled('client')) {
                $produits->where('user_id', $request->client);
            }
            $total = $produits->count();

            $produits = $produits->paginate(10);
            foreach ($produits as $produit) {
                if (!empty(User::withTrashed()->find($produit->user_id)))
                    $users[] =  User::withTrashed()->find($produit->user_id);
            }
            //dd($clients[0]->id);
        } else {
            $produits = $produits->where('user_id', Auth::user()->id)->paginate(10);
            $total = DB::table('produits')->where('user_id', Auth::user()->id)->count();
        }

        foreach ($produits as $produit) {
            $dbStock = DB::table('stocks')->where('produit_id', $produit->id)->first();
            $stock[] =  $dbStock;
        }




        return view('produit.index', [
            'nouveau' => $nouveau, 'produits' => $produits,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'stock' => $stock,
            'data' => $data
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('ecom')) {

            return redirect('/commandes');
        } else {
            $produit = new Produit();

            $produit->libelle = $request->libelle;
            $produit->prix = $request->prix;
            $produit->categorie = $request->categorie;
            $produit->description = $request->description;
            $produit->reference = bin2hex(substr($produit->libelle, -strlen($produit->libelle), 3)) . date("mdis");
            //dd($request);
            if ($request->hasfile('photo')) {
                //dd($request->file('photo'));
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension(); //getting image extension
                $filename = time() . '.' . $extension;
                $file->move('uploads/produit/', $filename);
                $produit->photo = $filename;
            } else {
                //$produit->photo =  $produit->categorie . '.png';
                $produit->photo = "product.jpg";
            }

            $produit->user()->associate(Auth::user())->save();

            $stock = new Stock();

            $stock->qte = 0;
            $stock->cmd = 0;
            $stock->etat = "Nouveau";
            $stock->produit()->associate($produit)->save();
            return redirect()->route('produit.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function show(Produit $produit)
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        if (Gate::denies('ramassage-commande')) {
            if ($produit->user_id !== Auth::user()->id)
                return redirect()->route('produit.index');
        }
        $stock = DB::table('stocks')->where('produit_id', $produit->id)->first();
        return view('produit.show', [
            'nouveau' => $nouveau, 'produit' => $produit,
            'stock' => $stock,
            'commandes' => $produit->commandes()->get(),
            'receptions' => $produit->receptions()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produit $produit)
    {
        if ((!Gate::denies('ecom') && $produit->user_id !== Auth::user()->id) || Gate::denies('gestion-stock')) {
            return redirect()->route('produit.index');
        } else {
            $produit->libelle = $request->libelle;
            $produit->prix = $request->prix;
            $produit->categorie = $request->categorie;
            $produit->description = $request->description;
            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension(); //getting image extension
                $filename = time() . '.' . $extension;
                $file->move('uploads/produit/', $filename);
                $produit->photo = $filename;
            }
            $produit->save();

            $request->session()->flash('produit', 'modifié');

            return redirect()->route('produit.show', ['produit' => $produit->id]);
        }


        // dd("salut");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Produit  $produit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produit $produit)
    {
        //
    }
}
