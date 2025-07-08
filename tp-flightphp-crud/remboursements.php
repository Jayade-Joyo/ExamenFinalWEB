<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des remboursements</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Ajout de Font Awesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    .filters {
      background: #f5f5f5;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 15px;
    }
    .filter-group {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .filters label {
      font-weight: bold;
      margin-right: 5px;
    }
    .filters input, .filters select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 3px;
      width: 80px;
    }
    .filters button {
      padding: 8px 15px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      margin-left: 10px;
    }
    .filters button:hover {
      background: #45a049;
    }
    .filters button.reset {
      background: #f44336;
    }
    .filters button.reset:hover {
      background: #da190b;
    }
    .totals-row {
      font-weight: bold;
      background-color: #f9f9f9;
    }
    .totals-row td {
      padding: 10px;
      border-top: 2px solid #ddd;
    }
    .form-section {
      background: #fff;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    .form-section h3 {
      margin-top: 0;
      color: #333;
    }
    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 10px;
    }
    .form-row input {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 3px;
      flex: 1;
      min-width: 120px;
    }
    .form-row button {
      padding: 8px 20px;
      background: #2196F3;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
    .form-row button:hover {
      background: #1976D2;
    }
  </style>
</head>

<body>
  <!-- Inclusion du top menu -->
  <?php include 'topmenu.php'; ?>

  <div class="main-content">
    <h1>Gestion des remboursements</h1>

    <!-- Section des filtres -->
    <div class="filters">
      <div class="filter-group">
        <label>Période début:</label>
        <select id="mois_debut">
          <option value="">Mois</option>
          <option value="1">Janvier</option>
          <option value="2">Février</option>
          <option value="3">Mars</option>
          <option value="4">Avril</option>
          <option value="5">Mai</option>
          <option value="6">Juin</option>
          <option value="7">Juillet</option>
          <option value="8">Août</option>
          <option value="9">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">Décembre</option>
        </select>
        <input type="number" id="annee_debut" placeholder="Année" min="2020" max="2030">
      </div>
      
      <div class="filter-group">
        <label>Période fin:</label>
        <select id="mois_fin">
          <option value="">Mois</option>
          <option value="1">Janvier</option>
          <option value="2">Février</option>
          <option value="3">Mars</option>
          <option value="4">Avril</option>
          <option value="5">Mai</option>
          <option value="6">Juin</option>
          <option value="7">Juillet</option>
          <option value="8">Août</option>
          <option value="9">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">Décembre</option>
        </select>
        <input type="number" id="annee_fin" placeholder="Année" min="2020" max="2030">
      </div>
      
      <button onclick="appliquerFiltres()">
        <i class="fas fa-filter"></i> Filtrer
      </button>
      <button onclick="reinitialiserFiltres()" class="reset">
        <i class="fas fa-times"></i> Réinitialiser
      </button>

    </div>

    <!-- Section du formulaire -->
    <div class="form-section">
      <h3>Ajouter / Modifier un remboursement</h3>
      <div class="form-row">
        <input type="hidden" id="id_remboursement">
        <input type="number" id="id_pret" placeholder="ID Prêt">
        <input type="number" id="id_statut" placeholder="ID Statut" value="1">
        <input type="number" step="0.01" id="base" placeholder="Capital restant avant">
        <input type="number" step="0.01" id="interet" placeholder="Intérêts payés">
      </div>
      <div class="form-row">
        <input type="number" step="0.01" id="amortissement" placeholder="Capital remboursé">
        <input type="number" step="0.01" id="annuite" placeholder="Montant total">
        <input type="number" step="0.01" id="val_fin" placeholder="Capital restant après">
        <input type="date" id="date_echeance" placeholder="Date échéance">
      </div>
      <div class="form-row">
        <input type="number" min="1" max="12" id="mois" placeholder="Mois (1-12)">
        <input type="number" id="annee" placeholder="Année">
        <button onclick="ajouterOuModifier()">
          <i class="fas fa-save"></i> Ajouter / Modifier
        </button>
      </div>
    </div>

    <table id="table-remboursements">
      <thead>
        <tr>
          <th>ID</th>
          <th>ID Prêt</th>
          <th>ID Statut</th>
          <th>Capital avant</th>
          <th>Intérêts</th>
          <th>Amortissement</th>
          <th>Total</th>
          <th>Capital après</th>
          <th>Date échéance</th>
          <th>Mois/Année</th>
          <th>Date remb.</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr class="totals-row">
          <td colspan="3">TOTAL</td>
          <td id="total-base">0.00</td>
          <td id="total-interet">0.00</td>
          <td id="total-amortissement">0.00</td>
          <td id="total-annuite">0.00</td>
          <td id="total-val_fin">0.00</td>
          <td colspan="4"></td>
        </tr>
      </tfoot>
    </table>

    <script>
    const apiBase = "/ETU003366/ExamenFinalWEB/tp-flightphp-crud/ws"; // Vérifiez ce chemin !
      // const apiBase = "http://localhost/ExamenFinalWEB/tp-flightphp-crud/ws";
      let currentFilters = {};

      function ajax(method, url, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, apiBase + url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4 && xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          }
        };
        xhr.send(data);
      }

      function chargerRemboursements() {
        let url = "/remboursements";
        
        // Construire l'URL avec les filtres
        if (currentFilters.mois_debut && currentFilters.annee_debut && 
            currentFilters.mois_fin && currentFilters.annee_fin) {
          url = `/remboursements/periode/${currentFilters.mois_debut}/${currentFilters.annee_debut}/${currentFilters.mois_fin}/${currentFilters.annee_fin}`;
        }

        ajax("GET", url, null, (data) => {
          const tbody = document.querySelector("#table-remboursements tbody");
          tbody.innerHTML = "";
          
          // Variables pour les totaux
          let totalBase = 0;
          let totalInteret = 0;
          let totalAmortissement = 0;
          let totalAnnuite = 0;
          let totalValFin = 0;

          data.forEach(r => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${r.id_remboursement}</td>
              <td>${r.id_pret}</td>
              <td>${r.id_statut}</td>
              <td>${parseFloat(r.base).toFixed(2)}</td>
              <td>${parseFloat(r.interet).toFixed(2)}</td>
              <td>${parseFloat(r.amortissement).toFixed(2)}</td>
              <td>${parseFloat(r.annuite).toFixed(2)}</td>
              <td>${parseFloat(r.val_fin).toFixed(2)}</td>
              <td>${r.date_echeance}</td>
              <td>${r.mois}/${r.annee}</td>
              <td>${r.date_remboursement || '-'}</td>
              <td>
                <button onclick='remplirFormulaire(${JSON.stringify(r)})' title="Modifier">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick='supprimerRemboursement(${r.id_remboursement})' title="Supprimer">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            `;
            tbody.appendChild(tr);
            
            // Calcul des totaux
            totalBase += parseFloat(r.base);
            totalInteret += parseFloat(r.interet);
            totalAmortissement += parseFloat(r.amortissement);
            totalAnnuite += parseFloat(r.annuite);
            totalValFin += parseFloat(r.val_fin);
          });

          // Mise à jour des totaux
          document.getElementById("total-base").textContent = totalBase.toFixed(2);
          document.getElementById("total-interet").textContent = totalInteret.toFixed(2);
          document.getElementById("total-amortissement").textContent = totalAmortissement.toFixed(2);
          document.getElementById("total-annuite").textContent = totalAnnuite.toFixed(2);
          document.getElementById("total-val_fin").textContent = totalValFin.toFixed(2);
        });
      }

      function appliquerFiltres() {
        const moisDebut = document.getElementById("mois_debut").value;
        const anneeDebut = document.getElementById("annee_debut").value;
        const moisFin = document.getElementById("mois_fin").value;
        const anneeFin = document.getElementById("annee_fin").value;

        // Validation des filtres
        if ((moisDebut && !anneeDebut) || (!moisDebut && anneeDebut) ||
            (moisFin && !anneeFin) || (!moisFin && anneeFin)) {
          alert("Veuillez saisir à la fois le mois ET l'année pour chaque période.");
          return;
        }

        if (moisDebut && anneeDebut && moisFin && anneeFin) {
          // Vérifier que la période de début n'est pas postérieure à la période de fin
          const dateDebut = new Date(anneeDebut, moisDebut - 1);
          const dateFin = new Date(anneeFin, moisFin - 1);
          
          if (dateDebut > dateFin) {
            alert("La période de début ne peut pas être postérieure à la période de fin.");
            return;
          }
        }

        // Sauvegarder les filtres
        currentFilters = {
          mois_debut: moisDebut,
          annee_debut: anneeDebut,
          mois_fin: moisFin,
          annee_fin: anneeFin
        };

        // Recharger les données
        chargerRemboursements();
      }

      function reinitialiserFiltres() {
        document.getElementById("mois_debut").value = "";
        document.getElementById("annee_debut").value = "";
        document.getElementById("mois_fin").value = "";
        document.getElementById("annee_fin").value = "";
        
        currentFilters = {};
        chargerRemboursements();
      }

      function ajouterOuModifier() {
        const id_remboursement = document.getElementById("id_remboursement").value;
        const id_pret = document.getElementById("id_pret").value;
        const id_statut = document.getElementById("id_statut").value;
        const base = document.getElementById("base").value;
        const interet = document.getElementById("interet").value;
        const amortissement = document.getElementById("amortissement").value;
        const annuite = document.getElementById("annuite").value;
        const val_fin = document.getElementById("val_fin").value;
        const date_echeance = document.getElementById("date_echeance").value;
        const mois = document.getElementById("mois").value;
        const annee = document.getElementById("annee").value;

        const data = `id_pret=${id_pret}&id_statut=${id_statut}&base=${base}&interet=${interet}&amortissement=${amortissement}&annuite=${annuite}&val_fin=${val_fin}&date_echeance=${date_echeance}&mois=${mois}&annee=${annee}`;

        if (id_remboursement) {
          ajax("PUT", `/remboursements/${id_remboursement}`, data, () => {
            resetForm();
            chargerRemboursements();
          });
        } else {
          ajax("POST", "/remboursements", data, () => {
            resetForm();
            chargerRemboursements();
          });
        }
      }

      function remplirFormulaire(r) {
        document.getElementById("id_remboursement").value = r.id_remboursement;
        document.getElementById("id_pret").value = r.id_pret;
        document.getElementById("id_statut").value = r.id_statut;
        document.getElementById("base").value = r.base;
        document.getElementById("interet").value = r.interet;
        document.getElementById("amortissement").value = r.amortissement;
        document.getElementById("annuite").value = r.annuite;
        document.getElementById("val_fin").value = r.val_fin;
        document.getElementById("date_echeance").value = r.date_echeance;
        document.getElementById("mois").value = r.mois;
        document.getElementById("annee").value = r.annee;
      }

      function supprimerRemboursement(id) {
        if (confirm("Supprimer ce remboursement ?")) {
          ajax("DELETE", `/remboursements/${id}`, null, () => {
            chargerRemboursements();
          });
        }
      }

      function resetForm() {
        document.getElementById("id_remboursement").value = "";
        document.getElementById("id_pret").value = "";
        document.getElementById("id_statut").value = "1";
        document.getElementById("base").value = "";
        document.getElementById("interet").value = "";
        document.getElementById("amortissement").value = "";
        document.getElementById("annuite").value = "";
        document.getElementById("val_fin").value = "";
        document.getElementById("date_echeance").value = "";
        document.getElementById("mois").value = "";
        document.getElementById("annee").value = "";
      }

      // Chargement initial
      chargerRemboursements();
    </script>
  </div>
</body>
</html>