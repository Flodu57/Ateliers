<?php

namespace mygiftbox\views;
use mygiftbox\models\Prestation;
use mygiftbox\models\Categorie;

class OffersView extends View{

    public function render(){
        $app = \Slim\Slim::getInstance();
        $link = $this->getLink();
        $error = parent::error();  
        $listed_offers = $this->listOffers(Prestation::all());
        $listed_categories = $this->listCategories(Categorie::all());
        $html = <<<END
        <!DOCTYPE html>
            <html>
                $this->header
                <body>
                    <div class='container'>
                        $this->menu
                        $error
                        <div class='tri_categories'>
                            <p>Trier par catégories</p>
                            <i id='slide_arrow' class='fas fa-angle-down'></i>
                            <div id='cat_list' class='categories'>
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
        //$urlCateg = $app->urlFor('');
        $cat = "";
        foreach($categs as $categ) {
            $cat .= <<<END
            <div>
                <a href='#'>$categ->titre</a>
            </div>
END;
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
            $urlDelete = $app->urlFor('deleteOffer', ['id' => $offer->id]);
            $urlModify = $app->urlFor('modifyOffer', ['id' => $offer->id]);
            $urlLock = $app->urlFor('lockOffer', ['id' => $offer->id]);
            if($offer->suspendue){
                $lock = "<a href='$urlLock'><i class='fa fa-lock-open'></i></a>";
            } else {
                $lock = "<a href='$urlLock'><i class='fa fa-unlock-alt'></i></a>";
            }
            $pres .= <<<END
            <div class="offer">
            <a href='$urlDetailledOffer' >
                <img src='$link/assets/img/prestations/$offer->image'>
                <div class='offer_bottom'>
                    <h2>$offer->titre</h2>
                    <div class='offer_bottom_infos'>
                        <p>$categorie</p>
                        <p>$offer->prix €</p>
                        
                    </div>
                </div>
            </a>
            <a href='$urlDelete'><i class='fa fa-trash'></i></a>
            $lock
            <a href='$urlModify'><i class='fa fa-cog'></i></a>
            </div>
END;
        }
        return $pres;
    }

}