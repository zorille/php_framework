# GitHub Copilot

- Completer dans le style local: noms en francais, factories `creer_*`, `_initialise`, logs `onDebug/onError`.
- Ne pas suggerer automatiquement Composer, PHPUnit, PSR-4 ou framework tiers.
- Pour les DTO, suivre le pattern `data_model`: getters/setters, `formatArrayKey`, `toArray`, `convert`.
- Pour les clients REST, reutiliser `wsclient` et les methodes `prepare_connexion`, `prepare_html_entete`, `prepare_requete`.
- Eviter d'afficher secrets et mots de passe dans les suggestions de logs.
