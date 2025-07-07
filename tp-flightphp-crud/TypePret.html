<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Types de Prêt</title>
  <style>
    /* Styles généraux pour le corps de la page */
    body {
      font-family: 'Inter', sans-serif; /* Utilisation de la police Inter */
      padding: 20px;
      background-color: #f4f7f6; /* Couleur de fond légère */
      color: #333;
      line-height: 1.6;
    }

    /* Styles pour les titres */
    h1 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.5em;
      border-bottom: 2px solid #3498db;
      padding-bottom: 10px;
    }

    /* Styles pour le conteneur du formulaire */
    div {
      background-color: #ffffff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px; /* Espace entre les éléments du formulaire */
      justify-content: center;
      align-items: center;
    }

    /* Styles pour les champs de saisie et les sélecteurs */
    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="date"],
    select {
      flex: 1 1 calc(33% - 20px); /* 3 éléments par ligne sur grand écran, avec espace */
      padding: 12px;
      margin: 5px 0;
      border: 1px solid #dcdcdc;
      border-radius: 8px;
      box-sizing: border-box; /* Inclut le padding et la bordure dans la largeur totale */
      font-size: 1em;
      min-width: 150px; /* Largeur minimale pour les petits écrans */
    }

    /* Styles pour les boutons */
    button {
      padding: 12px 25px;
      margin: 5px;
      border: none;
      border-radius: 8px;
      background-color: #3498db; /* Couleur primaire pour les boutons */
      color: white;
      font-size: 1em;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }

    button:hover {
      background-color: #2980b9; /* Assombrir au survol */
      transform: translateY(-2px); /* Petit effet de soulèvement */
    }

    /* Styles spécifiques pour les boutons d'action dans le tableau */
    button.action-button {
      background-color: #2ecc71; /* Vert pour modifier */
      margin: 0 3px;
      padding: 8px 12px;
      font-size: 0.9em;
      box-shadow: none;
    }

    button.action-button:hover {
      background-color: #27ae60;
      transform: none;
    }

    button.delete-button {
      background-color: #e74c3c; /* Rouge pour supprimer */
    }

    button.delete-button:hover {
      background-color: #c0392b;
    }

    /* Styles pour le tableau */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background-color: #ffffff;
      border-radius: 12px;
      overflow: hidden; /* Pour que les coins arrondis s'appliquent */
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    th, td {
      border: 1px solid #ecf0f1; /* Bordures de cellules légères */
      padding: 15px;
      text-align: left;
      vertical-align: middle;
    }

    th {
      background-color: #34495e; /* En-tête de tableau foncé */
      color: white;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 0.9em;
    }

    tr:nth-child(even) {
      background-color: #f9fbfb; /* Lignes paires légèrement grisées */
    }

    tr:hover {
      background-color: #e8f6f8; /* Effet de survol sur les lignes */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      input[type="text"],
      input[type="number"],
      input[type="email"],
      input[type="date"],
      select {
        flex: 1 1 100%; /* Un élément par ligne sur les petits écrans */
      }
      div {
        flex-direction: column;
        align-items: stretch;
      }
      button {
        width: 100%;
        margin: 5px 0;
      }
    }
  </style>
</head>
<body>

  <h1>Gestion des Types de Prêt</h1>

  <div>
    <!-- Champ caché pour l'ID du type de prêt (utilisé pour la modification) -->
    <input type="hidden" id="tp_id_type_pret">

    <!-- Champs de saisie pour les informations du type de prêt -->
    <input type="text" id="tp_nom_type" placeholder="Nom du Type de Prêt" required>
    <input type="number" id="tp_taux_interet" placeholder="Taux d'Intérêt (%)" step="0.01" required>
    <input type="number" id="tp_duree_max" placeholder="Durée Max (mois)" required>
    <input type="number" id="tp_montant_min" placeholder="Montant Min" step="0.01">
    <input type="number" id="tp_montant_max" placeholder="Montant Max" step="0.01">
    <select id="tp_actif" required>
      <option value="1">Actif</option>
      <option value="0">Inactif</option>
    </select>
    <input type="text" id="tp_description" placeholder="Description">
    <!-- Le champ date_creation est supprimé ici pour correspondre à votre banque.sql -->

    <!-- Boutons pour ajouter/modifier et réinitialiser le formulaire -->
    <button onclick="ajouterOuModifierTypePret()">Ajouter / Modifier Type de Prêt</button>
    <button onclick="resetFormTypePret()" style="background-color: #95a5a6;">Réinitialiser</button>
  </div>

  <!-- Tableau pour afficher la liste des types de prêt -->
  <table id="table-types-pret">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Taux (%)</th>
        <th>Durée Max (mois)</th>
        <th>Montant Min</th>
        <th>Montant Max</th>
        <th>Actif</th>
        <th>Description</th>
        <!-- La colonne Date Création est supprimée ici -->
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Les données des types de prêt seront insérées ici par JavaScript -->
    </tbody>
  </table>

  <script>
    // URL de base de votre API FlightPHP
    const apiBase = "http://localhost/ExamenFinalWEB/tp-flightphp-crud/ws"; // Vérifiez ce chemin !

    /**
     * Fonction utilitaire pour effectuer des requêtes AJAX.
     * @param {string} method - La méthode HTTP (GET, POST, PUT, DELETE).
     * @param {string} url - L'URL de l'API (chemin relatif à apiBase).
     * @param {string|null} data - Les données à envoyer (format 'clé=valeur&...'). Null pour les requêtes GET/DELETE sans corps.
     * @param {function} callback - Fonction de rappel en cas de succès (reçoit les données JSON de la réponse).
     * @param {function} errorCallback - Fonction de rappel en cas d'erreur (reçoit l'objet d'erreur JSON et le statut HTTP).
     */
    function ajax(method, url, data, callback, errorCallback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) { // La requête est terminée
          console.log("Statut HTTP:", xhr.status); // Affiche le statut HTTP
          console.log("Réponse brute du serveur:", xhr.responseText); // Affiche la réponse brute

          if (xhr.status >= 200 && xhr.status < 300) { // Succès (2xx)
            try {
              callback(JSON.parse(xhr.responseText));
            } catch (e) {
              console.error("Erreur de parsing JSON:", e);
              if (errorCallback) errorCallback({ error: "Réponse du serveur non valide (non-JSON). " + xhr.responseText }, xhr.status);
            }
          } else { // Erreur (4xx ou 5xx)
            console.error(`Erreur AJAX: ${xhr.status} - ${xhr.statusText}`);
            try {
              const errorResponse = JSON.parse(xhr.responseText);
              if (errorCallback) errorCallback(errorResponse, xhr.status);
            } catch (e) {
              // Si la réponse n'est pas JSON, affiche la réponse brute dans l'alerte
              if (errorCallback) errorCallback({ error: "Erreur serveur inconnue ou réponse non JSON. Réponse brute: " + xhr.responseText }, xhr.status);
            }
          }
        }
      };
      xhr.send(data);
    }

    // Fonctions pour les Types de Prêt
    /**
     * Charge tous les types de prêt depuis l'API et les affiche dans le tableau.
     */
    function chargerTypesPret() {
      ajax("GET", "/types_pret", null, (data) => {
        const tbody = document.querySelector("#table-types-pret tbody");
        tbody.innerHTML = ""; // Vider le tableau avant de le remplir
        data.forEach(tp => {
          const tr = document.createElement("tr");
          // Pas de formatage de date ici car la colonne date_creation n'est pas dans la table
          tr.innerHTML = `
            <td>${tp.id_type_pret}</td>
            <td>${tp.nom_type}</td>
            <td>${parseFloat(tp.taux_interet).toFixed(2)}%</td>
            <td>${tp.duree_max}</td>
            <td>${parseFloat(tp.montant_min).toFixed(2)}</td>
            <td>${parseFloat(tp.montant_max).toFixed(2)}</td>
            <td>${tp.actif ? 'Oui' : 'Non'}</td>
            <td>${tp.description || ''}</td>
            <td>
              <button class="action-button" onclick='remplirFormulaireTypePret(${JSON.stringify(tp)})'>✏️</button>
              <button class="action-button delete-button" onclick='supprimerTypePret(${tp.id_type_pret})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }, (errorResponse) => {
        alert("Impossible de charger les types de prêt: " + (errorResponse.error || "Erreur inconnue"));
      });
    }

    /**
     * Ajoute un nouveau type de prêt ou modifie un type de prêt existant.
     * Détermine l'action (POST ou PUT) en fonction de la présence de l'ID du type de prêt.
     */
    function ajouterOuModifierTypePret() {
      const id_type_pret = document.getElementById("tp_id_type_pret").value;
      const nom_type = document.getElementById("tp_nom_type").value;
      const taux_interet = document.getElementById("tp_taux_interet").value;
      const duree_max = document.getElementById("tp_duree_max").value;
      const montant_min = document.getElementById("tp_montant_min").value;
      const montant_max = document.getElementById("tp_montant_max").value;
      const actif = document.getElementById("tp_actif").value;
      const description = document.getElementById("tp_description").value;
      // const date_creation = document.getElementById("tp_date_creation").value; // Supprimé

      // Validation simple des champs requis (adaptée à votre schéma)
      if (!nom_type || !taux_interet || !duree_max) {
        alert("Veuillez remplir au moins les champs 'Nom du Type', 'Taux d\'Intérêt' et 'Durée Max'.");
        return;
      }

      // Construction des données à envoyer
      const data = `nom_type=${nom_type}&taux_interet=${taux_interet}&duree_max=${duree_max}&montant_min=${montant_min}&montant_max=${montant_max}&actif=${actif}&description=${description}`;

      console.log("Données envoyées pour la requête AJAX (TypePret):", data); // DEBUG: Affiche les données envoyées

      if (id_type_pret) {
        // Si un ID est présent, c'est une modification (PUT)
        ajax("PUT", `/types_pret/${id_type_pret}`, data, (response) => {
          alert(response.message);
          resetFormTypePret();
          chargerTypesPret();
        }, (errorResponse) => {
          alert("Erreur lors de la modification du type de prêt: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      } else {
        // Sinon, c'est un nouvel ajout (POST)
        ajax("POST", "/types_pret", data, (response) => {
          alert(response.message);
          resetFormTypePret();
          chargerTypesPret();
        }, (errorResponse) => {
          alert("Erreur lors de l'ajout du type de prêt: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      }
    }

    /**
     * Remplit le formulaire avec les données d'un type de prêt sélectionné pour modification.
     * @param {object} tp - L'objet type de prêt à utiliser pour remplir le formulaire.
     */
    function remplirFormulaireTypePret(tp) {
      document.getElementById("tp_id_type_pret").value = tp.id_type_pret;
      document.getElementById("tp_nom_type").value = tp.nom_type;
      document.getElementById("tp_taux_interet").value = parseFloat(tp.taux_interet).toFixed(2);
      document.getElementById("tp_duree_max").value = tp.duree_max;
      document.getElementById("tp_montant_min").value = parseFloat(tp.montant_min).toFixed(2);
      document.getElementById("tp_montant_max").value = parseFloat(tp.montant_max).toFixed(2);
      document.getElementById("tp_actif").value = tp.actif ? '1' : '0';
      document.getElementById("tp_description").value = tp.description;
      // document.getElementById("tp_date_creation").value = tp.date_creation; // Supprimé
    }

    /**
     * Supprime un type de prêt de la base de données.
     * @param {int} id - L'ID du type de prêt à supprimer.
     */
    function supprimerTypePret(id) {
      // Utilisation d'un modal personnalisé au lieu de confirm()
      const confirmDelete = window.confirm("Êtes-vous sûr de vouloir supprimer ce type de prêt ?");
      if (confirmDelete) {
        ajax("DELETE", `/types_pret/${id}`, null, (response) => {
          alert(response.message);
          chargerTypesPret();
        }, (errorResponse) => {
          alert("Erreur lors de la suppression du type de prêt: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      }
    }

    /**
     * Réinitialise tous les champs du formulaire de type de prêt à leurs valeurs par défaut.
     */
    function resetFormTypePret() {
      document.getElementById("tp_id_type_pret").value = "";
      document.getElementById("tp_nom_type").value = "";
      document.getElementById("tp_taux_interet").value = "";
      document.getElementById("tp_duree_max").value = "";
      document.getElementById("tp_montant_min").value = "";
      document.getElementById("tp_montant_max").value = "";
      document.getElementById("tp_actif").value = "1"; // Défaut à Actif
      document.getElementById("tp_description").value = "";
      // document.getElementById("tp_date_creation").value = ""; // Supprimé
    }

    // Charger les données des types de prêt au chargement initial de la page
    document.addEventListener('DOMContentLoaded', chargerTypesPret);
  </script>

</body>
</html>
