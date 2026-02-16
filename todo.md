collecte et destribution de dont (BNGRC)
Les sinistrés sont répartis par ville dans une région. Les sinitrés ont des besoins : 
	olona niaramboina izay ao anatina ville maromaro .
	Sinistrés : ce sont les personnes qui ont subi un sinistre (inondation, cyclone, incendie…)dans une région donnée.
	Répartition par ville : chaque ville de la région a un certain nombre de sinistrés.

besoin des sinistres : 
	categorie : - nature :
			-riz
			-huile
			-...
		    -materiaux : 
		    	-tole
		    	-clou
		    	-...
		    -argent : 
		    
fonctionnement : 
		saisie de besoin sinistrer par ville : 
		
On saisie les besoins des sinistrés par ville (on n'identifie pas personnellement un sinistré). On saisie les dont : misafidy anle besoins ilaina par ville ary my inserer ireo dont 
	
			Fonctionnaliter : 
				on choisis une regions(par exemple 2 ) (liste deroulant )
				on choisis une ville qui doit etre configurer selon la region (liste deroulant )
				lorsqu'on a fini : on choisis les besoins avec les dont 
					-design de tableau avec un bouton enregistrer et pour finir un bouton valider
					
		
				
les vues : 
    accueil.php : une grande image popur le font 

git pull --no-rebase



-views:
    -header.php:
        -logo img de bngrc 
        -menu:
            -accueil (redirection vers accueil.php)
            -besoins (redirection vers insertion_besoin.php)
            -dons (redirection vers insertion_don.php)
            -attribition (redirection vers attribution.php)
    
    -footer.php:
        -logo img de bngrc
        -info de contact, email, adresse, numero de telephone
        -reseaux sociaux : facebook, twitter, instagram (juste iconne)
        -a droite, menu : 
            -accueil
            -besoins
            -dons
            -attribition
        -copyright : BNGRC 2026

    -accueil.php:
        - (header.php)
        -une grande image de fond (peut etre une image de sinistré ou une image de la région)
        -un message de bienvenue : "Bienvenue sur le site de collecte et distribution de dons du BNGRC"
        -bloc de chaque ville: (redirection vers la page de la ville correspondante => ville_details.php)
            -image de ville
            -titre nom de ville
        - (footer.php)

    -ville_detail.php:
        - (header.php)
        - nom de la ville
        -besoins initiaux:
            - bloque de chaque besoin : 
                -categorie : riz, huile, materiaux, argent
                -quantite
            -dons attribues:
                - bloque de chaque dons fait : 
                -categorie : riz, huile, materiaux, argent
                -quantite
            -Restant a attribuer : 
                - bloque de chaque besoin restant : 
                -categorie : riz, huile, materiaux, argent
                -quantite
        - (footer.php)

    -insertion_don.php:
        w tojo 
        -2em page (dons.php)
        Page donner les dons pour un besoin

        ok    -Form
                - dropdown (region, ville)   
                    > tableau dinamique (selection checkbox > creation field en dessous pour completer)
                        - besoin (cat et detail) (en forme de l ien qui va ouvrir un field)
                - field pour besoin argent 
                -Bouton valider
                
            -/Form

        ok    -info requis ! 
            > region, ville, sinistres, besoin > cat et detail  
            model > controller > route avec data > view
            
        ok    -Fonction (affichage)
                getRegion, getVilleByRegion, getSinistresByVille
                getBesoinBySinitres, getMateriauxBycat

        wait    -Fonction (insertion)

    -attribution.php:
        - (header.php)
        - bloc a gauche :
            - categorie : nature, materiaux, argent
            - bloque de chaque besoin par categorie: 
                -nature: riz, huile, ...
                -materiaux : tole, clou, ...
                -argent :
            -quantite
        - bloc a droite :
            - champ d'insertion de don: 
                -region : liste deroulante des regions
                -ville : liste deroulante des villes selon la region choisie
                -categorie : liste deroulante des categories de besoin (riz, huile, materiaux, argent)
                -quantite : input de type number
            -bouton "attribuer" : pour attribuer le don au besoin correspondant dans la base de données
            nb: On affiche une erreur si la quantité donnée est supérieur aux dons. Ex: on a 100000Ar, et on donne 300000Ar
            la logique de l'attribution de don: plus les don sont attribue aux sinistrer, plus le besoin restant a attribuer diminue et plus les dons attribués au depart diminuent
        - (footer.php)
        

            
            
        

