<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion Clients & Prêts</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Styles existants conservés */
    .client-header {
      background: #3498db;
      color: white;
      padding: 15px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .client-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      overflow: hidden;
    }
    /* ... autres styles existants ... */
    .client-details {
      padding: 15px;
      border-bottom: 1px solid #eee;
    }
    .pret-item {
      padding: 12px 15px;
      border-bottom: 1px solid #f5f5f5;
      transition: background 0.3s;
    }
    .pret-item:hover {
      background: #f9f9f9;
    }
    .pret-info {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 10px;
    }
    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
    }
    .badge-success {
      background: #2ecc71;
      color: white;
    }
    .badge-warning {
      background: #f39c12;
      color: white;
    }
    .toggle-prets {
      transition: transform 0.3s;
    }
    .collapsed .toggle-prets {
      transform: rotate(-90deg);
    }

    /* Nouveaux styles pour la modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border-radius: 8px;
      width: 70%;
      max-width: 800px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      max-height: 80vh;
      overflow-y: auto;
    }
    .close-modal {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close-modal:hover {
      color: black;
    }
    .pret-details {
      margin-top: 20px;
    }
    .detail-row {
      display: flex;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }
    .detail-label {
      font-weight: bold;
      width: 200px;
      color: #555;
    }
    .detail-value {
      flex: 1;
    }
    .pret-actions {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }
    .pret-actions button {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-primary {
      background-color: #3498db;
      color: white;
    }
    .btn-danger {
      background-color: #e74c3c;
      color: white;
    }
    .btn-pdf {
        padding: 6px 12px;
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-pdf:hover {
        background-color: #c0392b;
    }
  </style>
</head>
<body>
  <?php include 'topmenu.php'; ?>

  <div class="main-content">
    <h1><i class="fas fa-users"></i> Liste des Clients et leurs Prêts</h1>

    <div id="clients-container"></div>

    <!-- Modal pour les détails du prêt -->
    <div id="pretModal" class="modal">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 id="modal-title">Détails du prêt</h2>
        <div id="pretDetails" class="pret-details"></div>
        <div class="pret-actions">
          <button class="btn-primary" onclick="genererPDF()">
            <i class="fas fa-file-pdf"></i> Export PDF
          </button>
          <button class="btn-danger" onclick="supprimerPret()">
            <i class="fas fa-trash"></i> Supprimer
          </button>
        </div>
      </div>
    </div>

    <script>
      const apiBase = "http://localhost/ExamenFinalWEB/tp-flightphp-crud/ws";
      let currentPretId = null;

      // Fonction pour charger les clients (inchangée)
      function chargerClients() {
        fetch(`${apiBase}/clients`)
          .then(response => response.json())
          .then(clients => {
            const container = document.getElementById('clients-container');
            container.innerHTML = '';

            clients.forEach(client => {
              const clientCard = document.createElement('div');
              clientCard.className = 'client-card';
              clientCard.innerHTML = `
                <div class="client-header" onclick="togglePrets(this)">
                  <div>
                    <strong>${client.prenom} ${client.nom}</strong>
                    <span class="badge ${client.revenu_mensuel > 5000 ? 'badge-success' : 'badge-warning'}">
                      ${client.revenu_mensuel} €/mois
                    </span>
                  </div>
                  <i class="fas fa-chevron-down toggle-prets"></i>
                </div>
                <div class="client-details">
                  <p><i class="fas fa-envelope"></i> ${client.email}</p>
                  <p><i class="fas fa-phone"></i> ${client.telephone}</p>
                  <p><i class="fas fa-map-marker-alt"></i> ${client.adresse}</p>
                </div>
                <div class="prets-container" style="display: none;"></div>
              `;
              
              container.appendChild(clientCard);
              chargerPrets(client.id_client, clientCard.querySelector('.prets-container'));
            });
          });
      }

      // Fonction pour charger les prêts d'un client (modifiée pour ajouter le clic)
      function chargerPrets(clientId, container) {
        fetch(`${apiBase}/clients/${clientId}/prets`)
          .then(response => response.json())
          .then(prets => {
            if (prets.length === 0) {
              container.innerHTML = '<div class="pret-item">Aucun prêt trouvé</div>';
              return;
            }

            container.innerHTML = '';
            prets.forEach(pret => {
              const pretItem = document.createElement('div');
              pretItem.className = 'pret-item';
              pretItem.innerHTML = `
                <div class="pret-info" onclick="afficherDetailsPret(${pret.id_pret})">
                  <div><strong>Prêt #${pret.id_pret}</strong></div>
                  <div>Montant: ${pret.montant} €</div>
                  <div>Taux: ${pret.taux_applique}%</div>
                  <div>Du ${pret.date_debut} au ${pret.date_fin}</div>
                  <div>Reste: ${pret.montant_restant} €</div>
                    
                </div>
              `;
              container.appendChild(pretItem);
            });
          });
      }

     

      // Fonction pour afficher les détails d'un prêt
      function afficherDetailsPret(pretId) {
        currentPretId = pretId;
        fetch(`${apiBase}/prets/${pretId}/details`)
          .then(response => response.json())
          .then(pret => {
            const modal = document.getElementById('pretModal');
            const detailsContainer = document.getElementById('pretDetails');
            
            document.getElementById('modal-title').textContent = `Détails du prêt #${pret.id_pret}`;
            
            detailsContainer.innerHTML = `
              <div class="detail-row">
                <div class="detail-label">Client:</div>
                <div class="detail-value">${pret.client_nom} ${pret.client_prenom}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Montant total:</div>
                <div class="detail-value">${pret.montant.toFixed(2)} €</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Taux appliqué:</div>
                <div class="detail-value">${pret.taux_applique}%</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Période:</div>
                <div class="detail-value">Du ${pret.date_debut} au ${pret.date_fin}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Montant restant:</div>
                <div class="detail-value">${pret.montant_restant.toFixed(2)} €</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Type de prêt:</div>
                <div class="detail-value">${pret.type_pret}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Statut:</div>
                <div class="detail-value">${pret.statut}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Fréquence de paiement:</div>
                <div class="detail-value">${pret.frequence_paiement}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Établissement financier:</div>
                <div class="detail-value">${pret.etablissement}</div>
              </div>
              <h3>Remboursements</h3>
              ${pret.remboursements && pret.remboursements.length > 0 ? 
                pret.remboursements.map(r => `
                  <div class="detail-row">
                    <div class="detail-label">${r.date_echeance}:</div>
                    <div class="detail-value">
                      ${r.annuite} € (Intérêt: ${r.interet} €, Capital: ${r.amortissement} €)
                    </div>
                  </div>
                `).join('') : 
                '<p>Aucun remboursement enregistré</p>'}
            `;
            
            modal.style.display = 'block';
          });
      }

      // Fonction pour fermer la modal
      document.querySelector('.close-modal').onclick = function() {
        document.getElementById('pretModal').style.display = 'none';
      };

      // Fermer la modal si on clique en dehors
      window.onclick = function(event) {
        const modal = document.getElementById('pretModal');
        if (event.target == modal) {
          modal.style.display = 'none';
        }
      };

      // Fonction pour générer un PDF (à implémenter)
      function genererPDF() {
        window.open(`${apiBase}/prets/${currentPretId}/pdf`);
      }

      // Fonction pour supprimer un prêt
      function supprimerPret() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce prêt ?')) {
          fetch(`${apiBase}/prets/${currentPretId}`, {
            method: 'DELETE'
          })
          .then(response => {
            if (response.ok) {
              alert('Prêt supprimé avec succès');
              document.getElementById('pretModal').style.display = 'none';
              chargerClients(); // Recharger la liste
            }
          });
        }
      }

      // Fonction pour afficher/masquer les prêts (inchangée)
      function togglePrets(header) {
        const card = header.parentElement;
        const pretsContainer = card.querySelector('.prets-container');
        
        if (pretsContainer.style.display === 'none') {
          pretsContainer.style.display = 'block';
          card.classList.remove('collapsed');
        } else {
          pretsContainer.style.display = 'none';
          card.classList.add('collapsed');
        }
      }

      // Chargement initial
      chargerClients();
    </script>
  </div>
</body>
</html>