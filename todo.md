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
        -dashboard (information):
            -ville 
            -besoin initiaux
            -dons actuels
            -restant
        -(footer.php)
        

