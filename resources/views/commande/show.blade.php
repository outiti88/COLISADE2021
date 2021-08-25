@extends('racine')

@section('title')
N: {{$commande->numero}}
@endsection


@section('style')
    <style>
        .emp-profile{
            padding: 3%;
            margin-top: 3%;
            margin-bottom: 3%;
            border-radius: 0.5rem;
            background: #fff;

        }
        .profile-img{
            text-align: center;
        }
        .profile-img img{
            width: 70%;
            height: 100%;
        }
        .profile-img .file {
            position: relative;
            overflow: hidden;
            margin-top: -20%;
            width: 70%;
            border: none;
            border-radius: 0;
            font-size: 1em;
            background: #212529b8;
        }
        .profile-img .file input {
            position: absolute;
            opacity: 0;
            right: 0;
            top: 0;
        }
        .profile-head h5{
            color: #333;
        }
        .profile-head h6{
            color: #f7941e;
        }
        .profile-edit-btn{
            border: none;
            border-radius: 1.5rem;
            width: 70%;
            padding: 2%;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
        }
        .proile-rating{
            font-size: 0.75em;
            color: #818182;
            margin-top: 5%;
        }
        .proile-rating span{
            color: #495057;
            font-size: 0.75em;
            font-weight: 600;
        }
        .profile-head .nav-tabs{
            margin-bottom:5%;
        }
        .profile-head .nav-tabs .nav-link{
            font-weight:600;
            border: none;
        }
        .profile-head .nav-tabs .nav-link.active{
            border: none;
            border-bottom:2px solid #f7941e;
        }
        .profile-work{
            padding: 14%;
            margin-top: -15%;
        }
        .profile-work p{
            font-size: 0.75em;
            color: #818182;
            font-weight: 600;
            margin-top: 10%;
        }
        .profile-work a{
            text-decoration: none;
            color: #495057;
            font-weight: 600;
            font-size: 0.75em;
        }
        .profile-work ul{
            list-style: none;
        }
        .profile-tab label{
            font-weight: 600;
        }
        .profile-tab p{
            font-weight: 600;
            color: #f7941e;
        }
        a {
            color: #f7941e;
        }
        a:hover {
            color: #f7941e;
        }

        #home .row{
            border-bottom-color: #cacaca;
            border-bottom-style: solid;
            border-bottom-width: 2px;
            padding-top: 10px;
        }
    </style>
@endsection






@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-6">
            <h4 class="page-title">Gestion des Colis</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Colisade</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="/commandes">Colis</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$commande->numero}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-6">
            <div class="row float-right">
                @can('fournisseur')
                <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalReclamation"><i class="fab fa-buffer"></i> <span class="quick-action">Réclamer </span></a>
                <div class="modal fade" id="modalReclamation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form  method="POST" action="{{route('reclamation.store')}}">
                            @csrf
                            <input type="hidden" name="commande" value="{{ $commande->id }}"/>

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <h3 style="text-align:center; position: relative;top: -30px;" class="modal-title" id="exampleModalLabel">Soumettre une nouvelle réclamation.</h3>

                        <div class="modal-body" style="padding-bottom: 0;padding-top:0; text-align:center">
                            <h5>
                                <b>Commande numero : </b> {{$commande->numero}}
                            </h5>
                            <h5>
                                <b>Statut Actuel de la commande : </b> {{$commande->statut}}

                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="objet" class="col-sm-12">Objet de la Réclamation</label>
                                <div class="col-sm-12">
                                   <input type="text" name="objet" value="{{ old('objet') }}" id="objet" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Reclamation" class="col-sm-12">Réclamation :</label>
                                <div class="col-sm-12">
                                   <textarea name="description" rows="8" id="Reclamation" class="form-control" required>
                                    {{ old('description') }}
                                   </textarea>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary text-white m-r-5">Soumettre</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endcan


                    @can('livreur')
                    @if (( $commande->statut === "Pas de Réponse" || $commande->statut === "Livré" || $commande->statut === "Injoignable" || $commande->statut === "En cours" || $commande->statut === "Refusée" || $commande->statut === "Modifiée" || $commande->statut === "Annulée" || $commande->statut === "Relancée" || $commande->statut === "Reporté" ))
                    <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormStatut"><i class="fas fa-edit"></i><span class="quick-action">Statut </span></a>
                    @endif
                    @endcan
                    @can('manage-users')
                        @if ( $commande->statut !== "Retour en stock")
                        <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormStatut"><i class="fas fa-edit"></i><span class="quick-action">Statut</span></a>
                        @endif
                        <a  class="btn btn-dark text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormLivreur"><i class="fas fa-user"></i> <span class="quick-action"> Affecter</span></a>

                        <div class="modal fade" id="modalSubscriptionFormLivreur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <form  method="POST" action="{{route('commande.livreur',['id' => $commande->id])}}">
                                    @csrf
                                    @method('PATCH')
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Choisissez le livreur au quel vous voulez affecter cette commande ?</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <h5>
                                        Commande numero: {{$commande->numero}}
                                    </h5>
                                    <h5>
                                        Nom de livreur: {{$livreur->name}}
                                    </h5>
                                    <div class="form-group row">
                                        <label for="livreur" class="col-sm-4">Livreur :</label>
                                        <div class="col-sm-8">
                                            <select name="livreur" id="livreur" class="form-control form-control-line" value="{{ old('livreur') }}">
                                                <option value=""  selected >Choisissez le livreur</option>
                                                <option selected value="{{$livreur->id}}" class="rounded-circle">
                                                    {{$livreur->name}} => ({{count(App\Commande::where('livreur',$livreur->id)->get())}} Commandes)
                                                </option>
                                                @foreach ($livreurs as $livreur)
                                                    <option value="{{$livreur->id}}" class="rounded-circle">
                                                        {{$livreur->name}} => ({{count(App\Commande::where('livreur',$livreur->id)->get())}} Commandes)
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">

                                  </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary text-white m-r-5">Affecter</button>
                                </div>
                                </form>
                              </div>
                            </div>
                          </div>
                    @endcan





                    @can('delete-commande')
                    @if ($commande->statut === "envoyée" || $modify === 1  )
                    <a  class="btn btn-primary text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormEdit"><i class="fas fa-edit"></i><span class="quick-action"> Modifier</span></a>
                        @if ($commande->statut === "envoyée" )
                        <a class="btn btn-secondary text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormDelete"><i class="fas fa-trash-alt"></i><span class="quick-action"> Supprimer</span></a>
    
                                    <div class="modal fade" id="modalSubscriptionFormDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir supprimer cette commande ?</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>
                                                    Commande numero: {{$commande->numero}}
                                                </h5>
                                                <p class="proile-rating">Date : {{date_format($commande->created_at,"Y/m/d")}}<span> {{date_format($commande->created_at,"H:i:s")}}</span></p>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la suppression
    
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                              </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                              <form method="POST" action="{{ route('commandes.destroy',['commande'=> $commande->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary text-white m-r-5">Ok</button>
                                            </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
    
                        @endif
                    @endif

                    @endcan

            </div>
        </div>

    </div>
</div>
<div class="container-fluid">
    <div class="container emp-profile">

        <div class="row">
            @if (session()->has('cmdRefuser'))
            <div class="alert alert-dismissible alert-danger col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Vous ne pouvez pas changer le statut en  <b>"Retour en stock"</b>
                <br>
            <strong> Car: Le statut de la commande numero {{session()->get('edit')}} est Refusée et elle n'est pas encore facturée !</strong>
              </div>
              @endif
            @if (session()->has('statut'))
            <div class="alert alert-dismissible alert-success col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Succés !</strong> La commande à été bien Modifiée </a>.
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
        <strong>Attention !</strong> Vous ne pouvez pas changer le statut de La commande numero {{session()->get('noedit')}}
          </div>
        @endif
        @if (session()->has('nodelete'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong>vous ne pouvez pas supprimer La commande numero {{session()->get('nodelete')}}
          </div>
        @endif
        @if (session()->has('noupdate'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas modifier La commande numero {{session()->get('noupdate')}} <br>
                vous pouvez modifier que les commandes qui ont le statut <b>EXPIDIE</b>
        </div>
        @endif
        @if (session()->has('nonEncours'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numero {{session()->get('nonEncours')}} <br>
                vous pouvez modifier que les statuts des commandes qui ont le statut <b>En Cours</b>
        </div>
        @endif

            <div class="col-md-8">
                <div class="row">
                    <h5>
                     Nombre de relance : {{$Rtotal}}
                    </h5>
                </div>
                <div class="profile-head">
                            <h5>
                                Commande numero: <span style="color: #f7941e">{{$commande->numero}}</span>
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
                                        @case("Ramassée")
                                    badge-secondary"
                                    @can('ramassage-commande')
                                    title="Envoyer la commande"
                                     href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                    @endcan
                                        @break
                                        @case("Expidiée")
                                    badge-primary"
                                    @can('ramassage-commande')
                                    title="Rammaser la commande"
                                     href="{{ route('commandeStatut',['id'=> $commande->id]) }}"
                                    @endcan
                                        @break
                                    @case("En cours")
                                    @case("Modifiée")
                                    @case("Relancée")
                                    @case("Reporté")



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
                                    @case("Retour en stock")
                                        badge-danger"
                                        title="Retour enregistré en stock"
                                        @break
                                    @default
                                        badge-danger"
                                        title="Valider dans le stock"
                                       style="cursor:pointer"
                                        data-toggle="modal" data-target="#validRetour"
                                        @break

                                @endswitch
                                     >
                                     <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                </a>
                            </h5>
                            @can('manage-users')
                            @if ($client == true)
                            <div class="modal fade" id="validRetour" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Validation en stock</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Cliquez sur valider pour ajouter les produits de cette commande en stock
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                      <a  href="{{route('commande.valideRetour',$commande->id)}}" class="btn btn-primary">Valider le retour</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            @endif

                            @endcan

                            <p class="proile-rating">Date d'ajout : {{date_format($commande->created_at,"Y/m/d")}}<span> {{date_format($commande->created_at,"H:i:s")}}</span></p>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Informations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Historique</a>
                        </li>
                        @can('gestion-stock')
                        <li class="nav-item">
                            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
                        </li>
                        @endcan
                        @can('ramassage-commande')

                        <li class="nav-item">
                            <a class="nav-link" id="relances-tab" data-toggle="tab" href="#relances" role="tab" aria-controls="relances" aria-selected="false">Relances</a>
                        </li>

                        @endcan

                    </ul>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-info text-white m-r-5" data-toggle="modal" data-target="#ticketPrint"><i class="fas fa-print"></i> Imprimer</button>
            </div>
            @can('manage-users')
            <div class="col-md-2">
                <button type="button" style="background-color: #ffab01;" class="btn btn-secondary text-white m-r-5" data-toggle="modal" data-target="#horszone"><i class="fas fa-route"></i> Hors Zone</button>
            </div>
            @endcan

        </div>

        <div class="modal fade" id="ticketPrint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Choisissez le type de format</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-footer">
                    <a target="_blank" class="btn btn-info text-white m-r-5" href="{{ route('pdf.gen',['id'=> $commande->id]) }}">Format A6</a>
                    <a target="_blank" class="btn btn-primary text-white m-r-5" href="{{ route('pdf.genA8',['id'=> $commande->id]) }}">Format A8</a>
                </div>
              </div>
            </div>
          </div>
          @can('manage-users')
        <div class="modal fade" id="horszone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <form action="{{route('commande.outRange',['commande' => $commande])}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Veuillez mentionnez le prix de livraison sur cette zone</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body mx-3">
                        <div class="form-group col-md-12" >
                            <label for="horsZonePrice" class="col-md-12">Prix de livraison (DH) :</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control form-control-line" name="horsZone" id="horsZonePrice">
                            </div>
                        </div>
                      </div>
                      <div class="modal-body mx-3">
                        <div class="form-group col-md-12" >
                            <label for="horsZoneLivreurPart" class="col-md-12">Part de livreur (DH) :</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control form-control-line" name="horsZoneLivreurPart" id="horsZoneLivreurPart">
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit"class="btn btn-primary text-white m-r-5" >Valider</button>
                      </div>
                  </form>

              </div>
            </div>
          </div>
          @endcan


        <div class="row">

            <div class="col-md-12">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nom du destinataire : </label>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="text-transform: uppercase">{{$commande->nom}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Téléphone :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->telephone}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Adresse :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->adresse}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ville :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->ville}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Secteur :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->secteur}}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Montant :</label>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($commande->montant > 0)
                                        <p>{{$commande->montant}} DH</p>
                                        @else
                                        <p> <i class="far fa-credit-card"></i> CARD PAYMENT
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @can('livreur-admin')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>La part du livreur :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->livreurPart}} MAD</p>
                                    </div>
                                </div>
                                @endcan

                                @cannot('livreur')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Prix de livraison :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->prix}} DH</p>
                                    </div>
                                </div>
                                @endcannot

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre de colis :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->colis}}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Statut de la commande :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->statut}} ({{$commande->updated_at->diffForHumans()}})</p>
                                        <p class="proile-rating">Date : {{date_format($commande->updated_at,"Y/m/d")}}<span> {{date_format($commande->updated_at,"H:i:s")}}</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>La date d'ajout :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{date_format($commande->created_at,"Y/m/d H:i:s")}}</p>
                                        <p class="proile-rating">il y'a: {{$commande->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>STATUT</label>
                                    </div>
                                    <div class="col-md-4">
                                        <p>DATE</p>
                                    </div>
                                    @can('ramassage-commande')
                                    <div class="col-md-4">
                                        <p>PAR</p>
                                    </div>
                                    @endcan
                                </div>
                                @foreach ($statuts as $index => $statut)
                                <div class="row">
                                    <div class="col-md-4">
                                    <label>
                                        <a  style="color: white"
                                            class=
                                            @switch($statut->name)
                                                @case("envoyée")
                                                "badge badge-pill badge-warning"
                                                @break

                                                @case("Ramassée")
                                                    "badge badge-pill badge-secondary"
                                                @break

                                                @case("Expidiée")
                                                    "badge badge-pill badge-primary"
                                                @break

                                                @case("En cours")
                                                @case("Modifiée")
                                                @case("Relancée")
                                                @case("Reporté")
                                                @case("Pas de Réponse")
                                                    "badge badge-pill badge-info"
                                                @break

                                                @case("Livré")
                                                    "badge badge-pill badge-success"
                                                @break

                                                @default
                                                    "badge badge-pill badge-danger"
                                            @endswitch
                                        >
                                        <span style="font-size: 1.25em">{{$statut->name}}</span>

                                        </a>
                                    </label>
                                    @if ($statut->name == "Reporté" && $statut->postponed_at != null)
                                             <span class="badge">Pour le : {{\Carbon\Carbon::parse($statut->postponed_at)->format('j , m, Y')}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <p>{{$statut->created_at}}</p>
                                    </div>
                                    @can('ramassage-commande')
                                    <div class="col-md-4">
                                        <p>{{$par[$index+1]->name}}</p>
                                    </div>
                                    @endcan
                                </div>
                                @endforeach



                        <div class="row">
                            <div class="col-md-12">
                                <label>Commentaire</label><br/>
                                @if ($commande->commentaire)
                                    <p>{{$commande->commentaire}}</p>
                                @else
                                    <p>Sans Commentaire</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @can('gestion-stock')
                    <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                        @foreach ($produits as $index => $produit)
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="/produit/{{$produit->id}}" title="{{$produit->libelle}}" class=" text-muted waves-effect waves-dark pro-pic">
                                        <img src="/uploads/produit/{{$produit->photo}}" alt="user" class="rounded-circle" width="31">
                                    </a>
                                    <label>{{$produit->libelle}}</label>
                                </div>
                                <div class="col-md-4">
                                    <p style="text-transform: uppercase">Ref: {{$produit->reference}}</p>
                                </div>
                                <div class="col-md-4">
                                    <p style="text-transform: uppercase">QTE: {{$liaisons[$index]->qte}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endcan

                    @can('ramassage-commande')
                    <div class="tab-pane fade" id="relances" role="tabpanel" aria-labelledby="relances-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date de relance</label>
                            </div>
                            <div class="col-md-6">
                                <p>Commentaire</p>
                            </div>
                            <div class="col-md-3">
                                <p>Relancée par</p>
                            </div>
                        </div>
                        @forelse ($relances as $index => $relance)
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{$relance->created_at}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p style="text-transform: uppercase">{{$relance->comment}}</p>
                                </div>
                                <div class="col-md-3">
                                    <p style="text-transform: uppercase">{{$Rpar[$index]->name}}</p>
                                </div>
                            </div>
                            @empty
                            <div class="row">
                                <div class="col-md-12">
                                    Aucune Relance
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @endcan
                </div>
            </div>
        </div>

</div>
</div>

@can('delete-commande')
@if ($commande->statut === "envoyée" || $modify === 1 || $commande->statut === "Refusée")
<div class="container my-4">
    <div class="modal fade" id="modalSubscriptionFormEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Modifier la Commande</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('commandes.update',['commande' => $commande])}}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="col-md-12">Nom et Prénom du destinataire :</label>
                                    <div class="col-md-12">
                                        <input  value="{{ old('nom',$commande->nom) }}" name="nom" type="text" placeholder="Nom & Prénom" class="form-control form-control-line">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="example-email" class="col-md-12">Nombre de Colis :</label>
                                        <div class="col-md-12">
                                            <input  value="{{ old('colis',$commande->colis) }}" type="number" class="form-control form-control-line" name="colis" id="example-email">
                                        </div>
                                    </div>


                                      <fieldset class="form-group col-md-6">
                                        <div class="row">
                                          <legend class="col-form-label  pt-0">Mode de paiement :</legend>
                                          <div class="col-sm-12">
                                            <div class="form-check">
                                              <input  onclick="myFunctionEdit2(this.value)" class="form-check-input" type="radio" name="mode" id="cd" value="cd"
                                              @if ($commande->montant != 0)
                                                checked
                                                @endif
                                              >
                                              <label class="form-check-label" for="cd">
                                                à la livraison
                                              </label>
                                            </div>
                                            <div class="form-check">
                                              <input  onclick="myFunctionEdit2(this.value)" class="form-check-input" type="radio" name="mode" id="cp" value="cp"
                                              @if ($commande->montant == 0)
                                                checked
                                                @endif
                                              >
                                              <label class="form-check-label" for="cp">
                                                carte bancaire
                                              </label>
                                            </div>

                                          </div>
                                        </div>
                                      </fieldset>

                                      <div class="form-group col-md-12" id="montant2"
                                      @if ($commande->montant != 0)
                                      style="display: block"
                                      @else
                                      style="display: none"
                                      @endif


                                       >
                                        <label for="example-email" class="col-md-12">Montant (DH) :</label>
                                        <div class="col-md-12">
                                            <input  value="{{ old('montant',$commande->montant) }}" type="text" class="form-control form-control-line" name="montant" id="example-email">
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Téléphone :</label>
                                    <div class="col-md-12">
                                        <input value="{{ old('telephone',$commande->telephone) }}"  name="telephone" type="text" placeholder="+212 5393-07566" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Adresse :</label>
                                    <div class="col-md-12">
                                        <textarea  name="adresse" rows="5" class="form-control form-control-line">{{ old('adresse',$commande->adresse) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">Ville :</label>
                                    <div class="col-sm-12">
                                        <select name="ville" class="form-control form-control-line" value="{{ old('ville',$commande->ville) }}" onchange="myFunctionEdit1()" required>
                                        <option value="{{$commande->ville}}" checked>{{$commande->ville}}</option>
                                        @foreach ($villes as $ville)
                                        <option value="{{$ville->name}}" class="rounded-circle">
                                            {{$ville->name}}
                                        </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div   class="form-group" id="secteur2" style="display: none">
                                    <label class="col-sm-12">Secteur :</label>
                                    <div class="col-sm-12">
                                        <select  value="{{ old('secteur',$commande->secteur) }}" name="secteur" class="form-control form-control-line">
                                            <option value="{{$commande->secteur}}" checked>{{$commande->secteur}}</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="isOpen" value="1"
                                    @if ($commande->isOpen)
                                         checked
                                    @endif
                                    >
                                    <label class="custom-control-label" for="customCheckRegister">
                                      <span >J'accepte l'ouverture du colis par le client.</span>
                                    </label>
                                  </div>
                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-warning">Modifier</button>

                                    </div>
                                </div>
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                        <strong>{{$error}}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                              @endif
                        </div>

                      </div>
                    </div>
    </div>
</div>
@endif
@endcan

<div class="container my-4">
    <div class="modal fade" id="modalSubscriptionFormStatut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Changer le statut</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('commandeStatut',['id' => $commande->id])}}">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label for="etat" class="col-sm-12">Statut :</label>
                                    <div class="col-sm-12">
                                        <select id="etat" onchange="reporter()" name="statut" class="form-control form-control-line" value="{{ old('statut',$commande->statut) }}" required>
                                            @can('manage-users')
                                                <option>envoyée</option>
                                                <option>Ramassée</option>
                                                <option>Reçue</option>
                                                <option>Expidiée</option>
                                                <option>En cours</option>
                                                <option>Relancée</option>
                                            @endcan
                                                <option>Livré</option>
                                                <option>Injoignable</option>
                                                <option>Pas de Réponse</option>
                                                <option>Refusée</option>
                                            @cannot('livreur')
                                                <option>Retour</option>
                                            @endcannot
                                                <option>Reporté</option>
                                                <option>Annulée</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display: none" id="prevu">
                                    <label for="datePrevu" class="col-sm-12">Date Prévue :</label>
                                    <div class="col-sm-12">
                                      <input class="form-control" name="prevu_at" type="date" id="datePrevu">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">Commentaire :</label>
                                    <div class="col-sm-12">
                                        <textarea  name="commentaire" rows="5" class="form-control form-control-line">{{ old('commentaire') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-warning">Enregistrer</button>

                                    </div>
                                </div>
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                        <strong>{{$error}}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                              @endif
                        </div>

                      </div>
                    </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    var xx = document.getElementById("prevu");
    function reporter() {

    var test = document.getElementById("etat").value;
    //alert(test);
    if(test=='Reporté'){
        xx.style.display = "block";
    }
    else{
        xx.style.display = "none";
    }
    }
</script>

<script>

    function myFunctionEdit1() {
        var x = document.getElementById("secteur2");
    var test = document.getElementById("ville2").value;
    if(test=='Tanger'){
        x.style.display = "block";
    }
    else{
        x.style.display = "none";
    }
    }
</script>
<script>
    function myFunctionEdit2(mode) {
        var y = document.getElementById("montant2");
        if(mode == 'cd' && y.value != 0 ){
            y.style.display = "block";
        }
        else{
            y.style.display = "none";
        }
    }
</script>
<script>
    function myFunction() {
        var xx = document.getElementById("secteur");
    var test = document.getElementById("ville").value;
    if(test=='Tanger'){
        xx.style.display = "block";
    }
    else{
        xx.style.display = "none";
    }
    }
</script>

<script>
    function myFunction2(mode) {
        var yy = document.getElementById("montant");

        if(mode == 'cd'){
            yy.style.display = "block";
            console.log("cd");
        }
        else{
            yy.style.display = "none";
            console.log("cp");
        }
    }
</script>
@endsection
