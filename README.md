# BileMo

1> Installation du Repository

Pour installer le **Repository**, il vous suffit de cloner le projet graçe à la commande :

    git clone https://github.com/Silverfabien/BileMo.git
    
---

2> Vérification des paramètre de la BDD

Avant de faire quoique ce soit, vérifier si les donneés ci-dsessous correspondent avec votre BDD

    #app\config\parameters.yml
    
     database_host: 127.0.0.1
     database_port: null
     database_name: BileMo
     database_user: root
     database_password: null
     
Si vous avez une BDD au nom de BileMo, modifiez cette ligne :

    database_name: BileMo
    
---

3> Initialisation des données en BDD

Pour créer la BDD faites cette commande :

    php bin/console doctrine:database:create
    
Ensuite faites cette commande pour ajouter les tables :

    php bin/console doctrine:schema:update --force
    
Puis ajouter les données correspondante au site en ajoutant les **Fixture**

    php bin/console doctrine:fixtures:load
    
---
