
@extends('racine')

@section('title')
   Gestion des Colis
@endsection


@section('style')
    <style>
    .orangeBadge{
        background-color: #FF5722;
    }
    .violetBadge{
        background-color: #ab03ca;
    }
    .cielBadge{
            background-color: #00BCD4;
    }
    .relanceBadge{
      background-color: #867f43;
    }
        .dropdown.dropdown-lg .dropdown-menu {
            margin-top: -1px;
            padding: 6px 20px;
        }
        .input-group-btn .btn-group {
            display: flex !important;
        }
        .btn-group .btn {
            border-radius: 0;
            margin-left: -1px;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .btn-group .form-horizontal .btn[type="submit"] {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        }
        .form-horizontal .form-group {
            margin-left: 0;
            margin-right: 0;
        }
        .form-group .form-control:last-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        @media screen and (min-width: 768px) {
            #adv-search {
                width: 500px;
                margin: 0 auto;
            }
            .dropdown.dropdown-lg {
                position: static !important;
            }
            .dropdown.dropdown-lg .dropdown-menu {
                min-width: 500px;
            }
        }
        .page-link {
            color: #f7941e !important;
        }
        .page-item.active .page-link {

            background-color: #f7941e !important;
            border-color: #f7941e !important;
            color: #fff !important;
        }
    </style>
@endsection


@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Archive des Commandes</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Colisade</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="/commandes">Colis</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            <div class="m-r-5" style="margin-right: 10px;">
                <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalSearchForm"><i class="fa fa-search"></i></a>
            </div>

        </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        @if (session()->has('search'))
        <div class="alert alert-dismissible alert-warning col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Aucun résultat trouvé !</strong> Il n'existe aucun numero de commande et aucun statut avec : {{session()->get('search')}}  </a>.
          </div>
        @endif
        @if (session()->has('statut'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succès !</strong> La commande a été bien enregistrée <a  href="commandes/{{session()->get('statut')}}" class="alert-link">(Voir la commande)</a>.
          </div>
        @endif

        @if (session()->has('delete'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> La commande numero {{session()->get('delete')}} à été bien supprimée !
          </div>
        @endif

        @if (session()->has('stock_insuf'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong> Le stock de l'article {{session()->get('stock_insuf')}} est insuffisant !
          </div>
        @endif

        @if (session()->has('produit_required'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong> Il faut mentionner les produits de la commande
          </div>
        @endif

        @if (session()->has('edit'))
        <div class="alert alert-dismissible alert-info col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> Le statut de la commande numero {{session()->get('edit')}} à été bien edité !
          </div>
        @endif
        @if (session()->has('noedit'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong>vous ne pouvez pas changer le statut La commande numero {{session()->get('noedit')}}
          </div>
        @endif

        @if (session()->has('nonExpidie'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>Commande déjà traitée  {{session()->get('nonExpidie')}} <br>
                vous pouvez modifier que les statuts des commandes qui ont le statut <b>envoyée</b>
        </div>
        @endif
        @if (session()->has('blgenere'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numero {{session()->get('blgenere')}} <br>
                => le bon de livraison pour cette commande à été déjà généré
        </div>
        @endif
        @if (session()->has('blNongenere'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numero {{session()->get('blNongenere')}} sans générer le bon de livraison <br>

        </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Les Commandes Archivées</h4>
                    <h6 class="card-subtitle">Nombre total des commandes archivées : <code>{{$total}} Commandes</code> .</h6>
                    <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 0.72em;">
                        <thead>
                            <tr>
                                @can('edit-users')
                                <th scope="col">Client</th>
                                @endcan
                                <th scope="col">Numero Commande</th>
                                <th scope="col">Nom Complet</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Adresse</th>
                                <th scope="col">Montant</th>
                                @cannot('livreur')
                                <th scope="col">Prix de Livraison</th>
                                @endcannot
                                <th scope="col">Date</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Ticket</th>

                            </tr>
                        </thead>
                        <tbody id="myTable">
                           @forelse ($commandes as $index => $commande)
                           <tr>
                            @can('edit-users')
                            <th scope="row">
                                <a title="{{$users[$index]->name}}" class=" text-muted waves-effect waves-dark pro-pic
                                    @if($users[$index]->statut)
                                        vip
                                    @endif

                                    "

                                            @can('edit-users')
                                                href="{{route('admin.users.edit',$users[$index]->id)}}"
                                            @endcan

                                    >
                                    <img src="{{$users[$index]->image}}" alt="user" class="rounded-circle" width="31">
                                </a>
                            </th>
                            @endcan
                            <th scope="row">

                                @if ($commande->facturer != 0)

                                    <a href="{{route('facture.infos',$commande->facturer)}}" style="color: white; background-color: #f7941e"
                                    class="badge badge-pill" >
                                    <span style="font-size: 1.25em">Facturée</span>
                                    </a>
                                    <br>
                                @else
                                    @if ($commande->traiter != 0)

                                    <a href="{{route('bon.infos',$commande->traiter)}}" style="color: white"
                                    class="badge badge-pill badge-dark">
                                    <span style="font-size: 1.25em">Bon livraison</span>
                                    </a>
                                    <br>
                                    @endif
                                @endif
                                {{$commande->numero}}

                            </th>
                            <td>{{$commande->nom}}</td>
                            <td>{{$commande->telephone}}</td>
                            <td>{{$commande->ville}}</td>
                            <td>{{$commande->adresse}}</td>
                            @if ($commande->montant > 0)
                            <td>{{$commande->montant}} DH</td>
                            @else
                            <td> <i class="far fa-credit-card"></i> CARD PAYMENT
                            </td>
                            @endif
                            @cannot('livreur')
                            <td>{{$commande->prix}} DH</td>
                            @endcannot
                            <td>{{$commande->updated_at}}</td>
                            <td>

                                <a  style="color: white"
                                    class="badge badge-pill
                                    @switch($commande->statut)
                                    @case("envoyée")
                                    badge-warning"
                                    @can('ramassage-commande')
                                    title="Rammaser la commande"
                                     href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                    @endcan
                                        @break
                                    @case("Reporté")
                                      orangeBadge"
                                    @break
                                    @case("Pas de Réponse")
                                    violetBadge"
                                    @break
                                    @case("Modifiée")
                                    cielBadge"
                                    @break
                                    @case("Relancée")
                                    relanceBadge"
                                    @break
                                    @case("En cours")
                                    badge-info"
                                        @if ($commande->traiter > 0)
                                        title="Voir le bon de livraison"
                                        href="{{route('bon.gen',$commande->traiter)}}"
                                        target="_blank"
                                        @else
                                        title="Générer le bon de livraison"
                                        href="{{route('bonlivraison.index')}}"
                                        @endif

                                        @break
                                    @case("Ramassée")
                                    badge-secondary"
                                        @can('ramassage-commande')
                                        title="Recevoir la commande"
                                         href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                        @endcan
                                    @break
                                    @case("Reçue")
                                    badge-dark"
                                    @can('ramassage-commande')
                                    title="Envoyer la commande"
                                     href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                    @endcan
                                @break
                                    @case("Expidiée")
                                        badge-primary"
                                        @can('ramassage-commande')
                                        title="Valider la commande"
                                         href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                        @endcan
                                    @break
                                    @case("Livré")
                                    badge-success"
                                    @if ($commande->facturer > 0)
                                        title="Voir la facture"
                                        href="{{route('facture.gen',$commande->facturer)}}"
                                        target="_blank"
                                        @else
                                        title="Générer la facture"
                                        href="{{route('facture.index')}}"
                                        @endif
                                        @break
                                    @default
                                    badge-danger"
                                @endswitch

                                     >
                                     <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                </a>
                                <br>
                                @if ($commande->statut == "Reporté" || $commande->statut == "Relancée")
                                    Pour le: <br>{{$commande->postponed_at}}
                                @else
                                ({{\Carbon\Carbon::parse($commande->updated_at)->diffForHumans()}})

                                @endif
                            </td>
                           <td style="font-size: 1.5em"><a title="Voir le detail" style="color: #f7941e" href="/commandes/{{$commande->id}}"><i class="mdi mdi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center">Aucune commande enregistrée!</td>
                        </tr>

                           @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$commandes ->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="container my-4">
    <div class="modal fade" id="modalSearchForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les commandes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="GET" action="{{route('archive.filter')}}">
                                @csrf
                                @can('manage-users')
                                <div class="form-group row">
                                    <label for="client" class="col-sm-4">Fournisseur :</label>
                                    <div class="col-sm-8">
                                        <select name="client" id="client" class="form-control form-control-line" value="{{ old('client') }}">
                                            <option value="" disabled selected>Choisissez le fournisseur</option>
                                            @foreach ($clients as $client)
                                        <option value="{{$client->id}}" class="rounded-circle">
                                            {{$client->name}}
                                        </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="livreur" class="col-sm-4">Livreur :</label>
                                    <div class="col-sm-8">
                                        <select name="livreur" id="livreur" class="form-control form-control-line" value="{{ old('livreur') }}">
                                            <option value="" disabled selected>Choisissez le livreur</option>
                                            @foreach ($livreurs as $livreur)
                                        <option value="{{$livreur->id}}" class="rounded-circle">
                                            {{$livreur->name}}
                                        </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>
                                @endcan

                                <div class="form-group row">
                                    <label class="col-md-4">Nom et Prénom:</label>
                                    <div class="col-md-8">
                                        <input  value="{{ old('nom') }}" name="nom" type="text" placeholder="Nom & Prénom" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4">Téléphone:</label>
                                    <div class="col-md-8">
                                        <input  value="{{ old('telephone') }}" name="telephone" type="text" placeholder="Téléphone" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Statut de commande:</label>
                                    <div class="col-sm-8">
                                        <select name="statut" class="form-control form-control-line">
                                            <option selected disabled>Choisissez le statut</option>
                                            <option>Livré</option>
                                            <option>Retour</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-date-input" class="col-4 col-form-label">Date Min</label>
                                    <div class="col-8">
                                      <input class="form-control" name="dateMin" type="date" value="{{now()}}" id="example-date-input">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="example-date-input" class="col-4 col-form-label">Date Max</label>
                                    <div class="col-8">
                                      <input class="form-control" name="dateMax"  type="date" value="{{now()}}" id="example-date-input">
                                    </div>
                                  </div>
                                  @cannot('livreur')

                                  <div class="form-group row">
                                    <label class="col-sm-4">Ville :</label>
                                    <div class="col-sm-8">
                                        <select name="ville" class="form-control form-control-line">
                                            <option selected disabled>Choisissez la ville</option>
                                            @foreach ($villes as $ville)
                                            <option value="{{$ville->name}}" class="rounded-circle">
                                                {{$ville->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endcannot
                                <div class="form-group row">
                                    <label for="example-date-input" class="col-3 col-form-label">Montant Min</label>
                                    <div class="col-3">
                                      <input class="form-control" name="prixMin" type="number" value="0" id="example-date-input">
                                    </div>
                                    <label for="example-date-input" class="col-3 col-form-label">Montant Max</label>
                                    <div class="col-3">
                                      <input class="form-control" type="number" name="prixMax" value="0" id="example-date-input">
                                    </div>
                                  </div>

                                  <div class="from-group row">
                                      <label for="bl" class="col-sm-3">BL générée</label>
                                      <div class="col-3">
                                        <input class="form-control" name="bl" type="checkbox" value="1" id="bl">
                                      </div>
                                      <label for="facture" class="col-sm-3">Facturée</label>
                                      <div class="col-3">
                                        <input class="form-control" name="facturer" type="checkbox" value="1" id="facture">
                                      </div>
                                  </div>

                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button type="submit" class="btn btn-warning"><i class="fa fa-search"></i> Rechercher</button>

                                    </div>
                                </div>
                            </form>
                        </div>

                      </div>
                    </div>
    </div>
</div>








@endsection

