# Atelier git avance
Atelier git avancé (rebase, patch, stash, cherry-pick)

## Requirements

- PHP 8.X
- git 2.x

Pour connaître votre version de php et de git, saissisez les commandes suivantes :


```bash
git --version
php --version
```


Si vous n'avez pas `php8` vous pouvez saisir la commande suivante pour l'installer :

```bash
sudo apt install php8.0 -y
```

Quand vous voudrez tester le fichier `index.php` il faudra utiliser la commande `php8.0` et non `php`

## Comment tester le code durant cet atelier ?

Vous devez utiliser la commande `php` ainsi :

```bash
php index.php
```

**Remarque** : Si vous avez installé `php8.0` ou plus, vous devez remplacer la commande `php` par `php8.0` :

```bash
php8.0 index.php
```

## Gérer l'historique git avec `rebase`

La commande `rebase` permet de gérer l'historique de votre git.
Il est possible de réaliser les actions suivantes :

- Changer le titre et le contenu d'un ou plusieurs commits avec `reword`
- Revenir vers un ou plusieurs commits pour modifier les fichiers avec `edit`
- Fusionner des commits pour en avoir qu'un seul avec `squash`
- Supprimer des commits `drop`
- Changer l'ordre des commits (très rare et peux perturber votre git)

Autre possibilité : vous pouvez récupérer des commits pour mettre à jours votre branche de fonctionnalités.

Exemple :

```bash
git switch -c feature/ajouter-des-sorts

# 3 mois plus tard...
# La branche feature/ajouter-des-sorts est en retard 
# par rapport à la branche principale (main / master)

# depuis la branche feature/ajouter-des-sorts
git rebase main
```

## Faire un "couper/coller" avec `stash`

Il arrive qu'on nous demande de réparer un bug rapidement alors qu'on est entrain de coder une fonctionnalité.
Au lieu de faire un commit pour sauvegarder à moitié notre fonctionnalité, on peut utiliser la commande `git stash` qui a un effet "couper/coller".

```bash
# on coupe les changements en cours sur "feature/ajouter-des-sorts"
git stash

# on se positionne sur la branche main
git switch main

# on crée une branche de type fix
git switch -c fix/bug-typage

# on code le fix, on commit/push et on crée une PR
# ...

# on retourne sur notre branche de features
git switch feature/ajouter-des-sorts

# on "colle" nos dernières modifications
git stash pop
```

## Réaliser des fix en une seule fois avec `patch`

Lorsqu'on a un bug qui survient et qu'il faut appliquer un correctif sur plusieurs versions stables, il peut arriver qu'il y ait une marge d'erreur entre le correctif pour la version 1.2.X et la version 2.4.X.

C'est là qu'intervient `git patch` ! `git patch` n'est pas vraiment une commande, mais un ensemble de commande comme `format-patch` et `am`.

Voici comment on l'utilise :

1. On se crée une branche de type "fix" : `git checkout -b fix/<nom-du-fix>`
    - **Attention** : Il ne faut pas `push` la branche et ne pas créer de Pull Request.
2. Vous codez votre fixture.
3. Une fois terminé, vous rassembler vos commits `git format-patch main -o patches`. Cette commande va créer un dossier `patches` où il y aura X dossiers pour X commits que vous avez créé pour réparer votre bug.
4. Quand votre dossier `patches` a bien été créé, vous pouvez aller sur la branche `main`, créer une branche de type `fix` : `git switch -c fix/<nom-du-fix>/<numero-issue>/main` puis vous appliquez les commits de votre dossier `patches` avec la commande suivante : `git am patches/*`.
5. Vos correctifs sont appliqués ! Vous `push` votre branche et vous créez votre *Pull Request*
6. Il ne vous reste plus qu'à appliquer les correcifs dans les autres branches "stable".
    - Exemple: 
    ```bash
    # Appliquer les correctifs pour la version 2.1
    git switch stablev2.1
    git switch -c fix/creation-personnage/555/stablev2.1
    git am patches/*

    # Appliquer les correcifs pour la version 3.3
    git switch stablev3.1
    git switch -c fix/creation-personnage/555/stablev3.1
    git am patches/*
    ```

## Appliquer une fonctionnalité pour une version précise de votre application avec `cherry-pick`

Le contexte est simple : vous codez votre fonctionnalité, c'est cool vous avez mergé sur la branche `main`, mais, maintenant vous voulez appliquer cette nouvelle fonctionnalité dans la version 2.X et 3.X.

Pour cela, il faut aller dans la branche "stable" désirée et utiliser la commande cherry-pick pour **appliquer** les commits de la nouvelle fonctionnalité dans la branche stable.

```bash
# depuis la branche main
git switch -c feature/ajout-de-sort-pour-magicien

# code de la feature, vous avez push, créé une PR et vous l'avez mergé dans la branche main

# on récupère le code qui est à jour
git pull origin main

# on applique la feature dans la branche v2.1
git switch stablev2.1
git switch -c backport/ajout-de-sort-pour-magicien/666/stablev2.1
git cherry-pick 75f3185 7d5fe15 a10f63b
# Après vous faite un git push + une Pull Request

# on applique la feature dans la branche v3.3
git switch stablev3.3
git switch -c backport/ajout-de-sort-pour-magicien/666/stablev3.3
git cherry-pick 75f3185 7d5fe15 a10f63b
# Après vous faite un git push + une Pull Request
```

Plusieurs remarques dans cet exemple de ligne de commande :

1. J'utilise le terme "backport" comme type de branche pour ajouter la nouvelle feature à la branche cible (stablev2.1 et stable3.3).
2. On a utilisé la commande `git cherry-pick 75f3185 7d5fe15 a10f63b` pour appliquer les numéros de commit `75f3185 7d5fe15 a10f63b` dans votre branche courante.
