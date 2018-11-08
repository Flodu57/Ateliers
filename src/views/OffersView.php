<?php

namespace mygiftbox\views;
use mygiftbox\models\Prestation;
use mygiftbox\models\Categorie;

class OffersView extends View{

    private $sorting_category;

    public function __construct($sorting_category = "all"){
        parent::__construct();
        $this->sorting_category = $sorting_category;
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $link = $this->getLink();
        $error = parent::error();

        if($this->sorting_category == "all")
            $listed_offers = $this->listOffers(Prestation::all());
        else
            $listed_offers = $this->listOffersByCategory(Prestation::all(), $this->sorting_category);

        $listed_categories = $this->listCategories(Categorie::all());
        $html = <<<END
        <!DOCTYPE html>
            <html>
                $this->header
                <body>
                    <div class='container'>
                        $this->menu
                        $error
                        <div class='menu_categories'>
                            <p>Trier par catégories</p>
                            <i id='slide_arrow' class='fas fa-angle-down'></i>
                            <div id='cat_list' class='cat_list'>
                                $listed_categories
                            </div>
                        </div>
                        <div class='offers'> 
                           $listed_offers
                        </div>
                    </div>
                    <script src='$link/assets/scripts/jquery.js'></script>
                    <script src='$link/assets/scripts/offers_sliding_sort.js'></script>
                </body>
            </html>
END;
        echo $html;
    }

    public function listCategories($categs){
        $app = \Slim\Slim::getInstance();
        $urlOffers = $app->urlFor('offers');
        if($this->sorting_category == 'all')
            $cat = "<a href='$urlOffers' class='selected_category'>Toutes</a>";
        else
            $cat = "<a href='$urlOffers'>Toutes</a>";
        
        foreach($categs as $categ) {
            $urlCateg = $app->urlFor('offers.category', ['category' => $categ->titre]);
            if($this->sorting_category == $categ->titre){
                $cat .= <<<END
                <a href='$urlCateg' class='selected_category'>$categ->titre</a>
END;
            }
            else {
                $cat .= <<<END
                <a href='$urlCateg'>$categ->titre</a>
END;
            }
        }
        return $cat;
    }

    public function listOffers($offers){
        $app = \Slim\Slim::getInstance();
        $link = $this->getLink();
        $pres = "";
        foreach($offers as $offer) {
            $urlDetailledOffer = $app->urlFor('offers.detailled', ['categorie' => $offer->categorie->titre, 'id' => $offer->id]);
            $categorie = $offer->categorie->titre;
            $pres .= <<<END
            <a href='$urlDetailledOffer' class='offer'>
                <img src='$link/assets/img/prestations/$offer->image'>
                <div class='offer_bottom'>
                    <h2>$offer->titre</h2>
                    <div class='offer_bottom_infos'>
                        <p>$categorie</p>
                        <p>$offer->prix €</p>
                    </div>
                </div>
            </a>
END;
        
        }
        return $pres;
    }

    public function listOffersByCategory($offers, $sorting_category){
        $app = \Slim\Slim::getInstance();
        $link = $this->getLink();
        $pres = "";
        foreach($offers as $offer) {
            if($offer->categorie->titre == $sorting_category){
                $urlDetailledOffer = $app->urlFor('offers.detailled', ['categorie' => $offer->categorie->titre, 'id' => $offer->id]);
                $categorie = $offer->categorie->titre;
                $pres .= <<<END
                <a href='$urlDetailledOffer' class='offer'>
                    <img src='$link/assets/img/prestations/$offer->image'>
                    <div class='offer_bottom'>
                        <h2>$offer->titre</h2>
                        <div class='offer_bottom_infos'>
                            <p>$categorie</p>
                            <p>$offer->prix €</p>
                        </div>
                    </div>
                </a>
END;
            }
        }
        return $pres;
    }

}