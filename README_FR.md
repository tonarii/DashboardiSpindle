iSpindle Dashboard est un outil Bootstrap pour visualiser les données de son iSpindle.
Vous pouvez afficher la Densité, la température, la charge de la batterie et pas mal d'autres petites choses.
Il est aussi possible d'exporter la base de donnée dans un fichier csv, ainsi que de la vider directement depuis le dashboard.
C'est créé pour être hébergé en ligne sur un site perso par exemple et combiné avec une base de donnée sql.
Mais l'ensemble fonctionne aussi très bien sur un réseau local.

Enjoy. -Nikko-

ps : je ne suis pas dev, c'est donc livré tel quel, avec du code pas bien propre et quelques petites choses inutiles...
Je pourrais nettoyer tout ça dans le futur, ou pas ^^

## NOUVELLES FONCTIONS

- NOUVEAU : Dans la partie réglages, vous pouvez entrer les données spécifiques de chaque iSpindel.
- Maintenant les pages "Angle", "Densité"" et "Température", affichent les données depuis la date de création de la première donnée
- Les courbes de ces pages sont zoomables. Vous pouvez ainsi voir de manière plus précise les différentes valeurs
  (il suffit de faire une "sélection" avec la souris, sur la zone souhaitée. Un bouton "reset" permet de retrouver l'affichage normal)
- Les courbes de la page "Dashboard" quand à elles, affichent les données des dernières 24h uniquement
- Dans la partie "Réglages" il est maintenant possible de choisir quel iSpindel afficher. Il vous suffit par exemple de nommer vos iSpindel ainsi : iSpindel000 , iSpindel001, iSpindel002...
- Le bouton pour effacer la table de la bdd, n'efface maintenant que le iSPindel activé. C'est à dire celui validé via le bouton dans les réglages.
Je vous conseille de faire une sauvegarde avant d'utiliser cette fonction.

## VERSION

- V 1.1.6 (Ajout de nouvelles fonctions)
- V 1.1.5 (Optimisations, le bouton choix du iSpindel affiche maintenant bien l'appareil sélectionné)
- V 1.1.4 (Ajout des nouvelles fonctions dans la version publique)
- V 1.1.3 BETA (Correction de bug, il en reste encore quelques un, je suis dessus)
- V 1.1.0 BETA (Grosse mise à jour, avec ajout de nouvelles fonctionalités)
- V 1.0.6 (Correction problème de compatibilité avec PHP 7)
- V 1.0.5 (Mise à jour du code pour les utilisateurs qui sont sur un réseau local)
- V 1.0.4


## INSTALLATION

- Vous devez créer les table dans votre base de donnée, utilisez l'exemple présent dans le fichier "creationTablesSQL.php"
- Ensuite lancez votre iSpindle en mode configuration, comme nom entrez "iSpindel000" et dans "Service Type" utilisez HTTP, pour "Server Adress" entrez l'adresse de votre website (ex: monwebsite.com) et pour "Server URL" l'url de votre dossier (ex: /myfolder/)
- Editez ensuite les fichiers "index.php" , "common_db.php" et "csvexport.php" avec les informations de connexion à votre base de donnée
- Dans le fichier "csvexport.php" vous pouvez aussi changer "$f = fopen('php://memory', 'w');" par "$f = fopen('../csv/FILE_NAME.csv', 'w');" si vous souhaitez exporter en même temps votre fichier csv sur votre FTP
- Dans le fichier "settings.php" à la ligne numéro 57, rempalcez "MONPASSWORD" par le mot de passe de vitre choix (n'utilisez pas le même que pour votre base de donnée !)
- Enfin, envoyez le tout sur votre FTP (en conservant bien la structure des dossiers !)
- Et pour finir rendez vous à l'adresse de votre dashboard et entrez les réglages que vous souhaitez dans la partie "réglages"
- Si vous utilisez plusieurs iSpindel en même temps, veuillez en premier lieu valider le nom (sans remplir les autres champs) du boitier pour lequel vous souhaitez entrer les informations. Ensuite seulement vous pourrez entrer le reste des informations de votre brassin.
- Pour entrer les informations d'un autre boitier, procédez de la même manière, valider en premier lieu le nom (sans tenir compte des informations déjà inscrites dans les autres champs)

## COPYRIGHTS

- Tout a été customisé et mixé par Nikko
- Basé sur le travail de DottoreTozzi (https://github.com/DottoreTozzi/iSpindel-TCP-Server)
- Code de base du dashbaord par Creative Tim (https://www.creative-tim.com/)
  | Licensed under MIT (https://github.com/creativetimofficial/black-dashboard/issues/blob/master/LICENSE.md)
- Charts par Highcharts (https://www.highcharts.com) et Fusioncharts (https://www.fusioncharts.com)


## LIENS UTILES

- [iSpindel](https://github.com/universam1/iSpindel)
- [TCP Server](https://github.com/DottoreTozzi/iSpindel-TCP-Server)

## IMAGES

![Screenshot](DeleteMe.gif)
