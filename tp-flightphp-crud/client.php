<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Clients</title>
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
    input[type="datetime-local"], /* Ajout du style pour datetime-local */
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

    /* Styles pour les champs en lecture seule */
    input[readonly] {
      background-color: #e9ecef;
      cursor: not-allowed;
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
      input[type="datetime-local"], /* Ajout du style pour datetime-local */
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

  <h1>Gestion des Clients</h1>

  <div>
    <!-- Champ caché pour l'ID du client (utilisé pour la modification) -->
    <input type="hidden" id="client_id_client">

    <!-- Champs de saisie pour les informations du client -->
    <input type="text" id="client_nom" placeholder="Nom" required>
    <input type="text" id="client_prenom" placeholder="Prénom" required>
    <input type="date" id="client_date_naissance" placeholder="Date de Naissance">
    <input type="text" id="client_adresse" placeholder="Adresse">
    <input type="text" id="client_telephone" placeholder="Téléphone">
    <input type="email" id="client_email" placeholder="Email">
    <input type="text" id="client_profession" placeholder="Profession">
    <input type="number" id="client_revenu_mensuel" placeholder="Revenu Mensuel" step="0.01">
    <!-- Nouveau champ pour la date d'inscription, en lecture seule -->
    <input type="datetime-local" id="client_date_inscription" placeholder="Date d'Inscription" readonly>

    <!-- Boutons pour ajouter/modifier et réinitialiser le formulaire -->
    <button onclick="ajouterOuModifierClient()">Ajouter / Modifier Client</button>
    <button onclick="resetFormClient()" style="background-color: #95a5a6;">Réinitialiser</button>
  </div>

  <!-- Tableau pour afficher la liste des clients -->
  <table id="table-clients">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date de Naissance</th>
        <th>Adresse</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>Profession</th>
        <th>Revenu Mensuel</th>
        <th>Date Inscription</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Les données des clients seront insérées ici par JavaScript -->
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

    // Fonctions pour les Clients
    /**
     * Charge tous les clients depuis l'API et les affiche dans le tableau.
     */
    function chargerClients() {
      ajax("GET", "/clients", null, (data) => {
        const tbody = document.querySelector("#table-clients tbody");
        tbody.innerHTML = ""; // Vider le tableau avant de le remplir
        data.forEach(client => {
          const tr = document.createElement("tr");
          // Formater la date de naissance et d'inscription si elles existent
          const dateNaissance = client.date_naissance ? new Date(client.date_naissance).toLocaleDateString('fr-FR') : '';
          const dateInscription = client.date_inscription ? new Date(client.date_inscription).toLocaleDateString('fr-FR', { year: 'numeric', month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';

          tr.innerHTML = `
            <td>${client.id_client}</td>
            <td>${client.nom}</td>
            <td>${client.prenom}</td>
            <td>${dateNaissance}</td>
            <td>${client.adresse || ''}</td>
            <td>${client.telephone || ''}</td>
            <td>${client.email || ''}</td>
            <td>${client.profession || ''}</td>
            <td>${parseFloat(client.revenu_mensuel).toFixed(2)}</td>
            <td>${dateInscription}</td>
            <td>
              <button class="action-button" onclick='remplirFormulaireClient(${JSON.stringify(client)})'>✏️</button>
              <button class="action-button delete-button" onclick='supprimerClient(${client.id_client})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }, (errorResponse) => {
        alert("Impossible de charger les clients: " + (errorResponse.error || "Erreur inconnue"));
      });
    }

    /**
     * Ajoute un nouveau client ou modifie un client existant.
     * Détermine l'action (POST ou PUT) en fonction de la présence de l'ID du client.
     */
    function ajouterOuModifierClient() {
      const id_client = document.getElementById("client_id_client").value;
      const nom = document.getElementById("client_nom").value;
      const prenom = document.getElementById("client_prenom").value;
      const date_naissance = document.getElementById("client_date_naissance").value;
      const adresse = document.getElementById("client_adresse").value;
      const telephone = document.getElementById("client_telephone").value;
      const email = document.getElementById("client_email").value;
      const profession = document.getElementById("client_profession").value;
      const revenu_mensuel = document.getElementById("client_revenu_mensuel").value;
      // date_inscription n'est pas envoyée car elle est gérée par la DB

      // Validation simple des champs requis
      if (!nom || !prenom) {
        alert("Veuillez remplir les champs 'Nom' et 'Prénom'.");
        return;
      }

      // Construction des données à envoyer
      const data = `nom=${nom}&prenom=${prenom}&date_naissance=${date_naissance}&adresse=${adresse}&telephone=${telephone}&email=${email}&profession=${profession}&revenu_mensuel=${revenu_mensuel}`;

      console.log("Données envoyées pour la requête AJAX:", data); // DEBUG: Affiche les données envoyées

      if (id_client) {
        // Si un ID est présent, c'est une modification (PUT)
        ajax("PUT", `/clients/${id_client}`, data, (response) => {
          alert(response.message);
          resetFormClient();
          chargerClients();
        }, (errorResponse) => {
          alert("Erreur lors de la modification du client: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      } else {
        // Sinon, c'est un nouvel ajout (POST)
        ajax("POST", "/clients", data, (response) => {
          alert(response.message);
          resetFormClient();
          chargerClients();
        }, (errorResponse) => {
          alert("Erreur lors de l'ajout du client: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      }
    }

    /**
     * Remplit le formulaire avec les données d'un client sélectionné pour modification.
     * @param {object} client - L'objet client à utiliser pour remplir le formulaire.
     */
    function remplirFormulaireClient(client) {
      document.getElementById("client_id_client").value = client.id_client;
      document.getElementById("client_nom").value = client.nom;
      document.getElementById("client_prenom").value = client.prenom;
      document.getElementById("client_date_naissance").value = client.date_naissance || ''; // Assurez-vous que la date est au format YYYY-MM-DD
      document.getElementById("client_adresse").value = client.adresse || '';
      document.getElementById("client_telephone").value = client.telephone || '';
      document.getElementById("client_email").value = client.email || '';
      document.getElementById("client_profession").value = client.profession || '';
      document.getElementById("client_revenu_mensuel").value = parseFloat(client.revenu_mensuel).toFixed(2);

      // Remplir le champ de date d'inscription (en lecture seule)
      if (client.date_inscription) {
        // Convertir la date de la DB (DATETIME) au format attendu par input type="datetime-local" (YYYY-MM-DDThh:mm)
        const dateObj = new Date(client.date_inscription);
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        document.getElementById("client_date_inscription").value = `${year}-${month}-${day}T${hours}:${minutes}`;
      } else {
        document.getElementById("client_date_inscription").value = '';
      }
    }

    /**
     * Supprime un client de la base de données.
     * @param {int} id - L'ID du client à supprimer.
     */
    function supprimerClient(id) {
      const confirmDelete = window.confirm("Êtes-vous sûr de vouloir supprimer ce client ?");
      if (confirmDelete) {
        ajax("DELETE", `/clients/${id}`, null, (response) => {
          alert(response.message);
          chargerClients();
        }, (errorResponse) => {
          alert("Erreur lors de la suppression du client: " + (errorResponse.error || JSON.stringify(errorResponse)));
        });
      }
    }

    /**
     * Réinitialise tous les champs du formulaire client à leurs valeurs par défaut.
     */
    function resetFormClient() {
      document.getElementById("client_id_client").value = "";
      document.getElementById("client_nom").value = "";
      document.getElementById("client_prenom").value = "";
      document.getElementById("client_date_naissance").value = "";
      document.getElementById("client_adresse").value = "";
      document.getElementById("client_telephone").value = "";
      document.getElementById("client_email").value = "";
      document.getElementById("client_profession").value = "";
      document.getElementById("client_revenu_mensuel").value = "";
      document.getElementById("client_date_inscription").value = ""; // Réinitialiser aussi le champ de date d'inscription
    }

    // Charger les données des clients au chargement initial de la page
    document.addEventListener('DOMContentLoaded', chargerClients);
  </script>

</body>
</html>
