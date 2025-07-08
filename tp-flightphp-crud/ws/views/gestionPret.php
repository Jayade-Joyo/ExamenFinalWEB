<?php
// gestionPret.php - Vue pour l'interface de gestion des prêts
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Prêts - Système Bancaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .tab.active {
            background: #007bff;
            color: white;
        }
        
        .tab:hover {
            background: #e9ecef;
        }
        
        .tab.active:hover {
            background: #0056b3;
        }
        
        .content {
            padding: 30px;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .table tr:hover {
            background: #f8f9fa;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status.en-attente {
            background: #fff3cd;
            color: #856404;
        }
        
        .status.approuve {
            background: #d4edda;
            color: #155724;
        }
        
        .status.rejete {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status.rembourse {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .amortissement-table {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }
        
        .montant {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏦 Gestion des Prêts</h1>
            <p>Système de gestion des prêts bancaires</p>
        </div>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('liste')">📋 Liste des Prêts</div>
            <div class="tab" onclick="showTab('nouveau')">➕ Nouveau Prêt</div>
            <div class="tab" onclick="showTab('calculateur')">🧮 Calculateur</div>
        </div>
        
        <div class="content">
            <!-- Onglet Liste des Prêts -->
            <div id="liste" class="tab-content active">
                <h2>Liste des Prêts</h2>
                <div id="message"></div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Restant</th>
                            <th>Taux</th>
                            <th>Assurance</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prets) && is_array($prets)): ?>
                            <?php foreach ($prets as $pret): ?>
                            <tr>
                                <td><?= $pret['id_pret'] ?></td>
                                <td><?= htmlspecialchars($pret['client_nom']) ?></td>
                                <td><?= htmlspecialchars($pret['nom_type']) ?></td>
                                <td class="montant"><?= number_format($pret['montant'], 2, ',', ' ') ?> €</td>
                                <td class="montant"><?= number_format($pret['montant_restant'], 2, ',', ' ') ?> €</td>
                                <td><?= $pret['taux_applique'] ?>%</td>
                                <td><?= $pret['taux_Assurance'] ?>%</td>
                                <td><?= date('d/m/Y', strtotime($pret['date_debut'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($pret['date_fin'])) ?></td>
                                <td>
                                    <span class="status <?= strtolower(str_replace('_', '-', $pret['statut'])) ?>">
                                        <?= $pret['statut'] ?>
                                    </span>
                                </td>
                                <td>
                                    <select onchange="updateStatut(<?= $pret['id_pret'] ?>, this.value)">
                                        <option value="">Changer statut</option>
                                        <?php if (!empty($statuts) && is_array($statuts)): ?>
                                            <?php foreach ($statuts as $statut): ?>
                                            <option value="<?= $statut['id_statut'] ?>"><?= $statut['libelle'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">Aucun statut disponible</option>
                                        <?php endif; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">Aucun prêt trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Onglet Nouveau Prêt -->
            <div id="nouveau" class="tab-content">
                <h2>Créer un Nouveau Prêt</h2>
                
                <form id="formPret">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_client">Client</label>
                            <select id="id_client" name="id_client" required>
                                <option value="">Sélectionner un client</option>
                                <?php if (!empty($clients) && is_array($clients)): ?>
                                    <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id_client'] ?>"><?= htmlspecialchars($client['nom_complet']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_type_pret">Type de Prêt</label>
                            <select id="id_type_pret" name="id_type_pret" required onchange="updateTaux()">
                                <option value="">Sélectionner un type</option>
                                <?php if (!empty($typesPret) && is_array($typesPret)): ?>
                                    <?php foreach ($typesPret as $type): ?>
                                    <option value="<?= $type['id_type_pret'] ?>" data-taux="<?= $type['taux_interet'] ?>">
                                        <?= htmlspecialchars($type['nom_type']) ?> (<?= $type['taux_interet'] ?>%)
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_etablissement">Établissement</label>
                            <select id="id_etablissement" name="id_etablissement" required>
                                <option value="">Sélectionner un établissement</option>
                                <?php if (!empty($etablissements) && is_array($etablissements)): ?>
                                    <?php foreach ($etablissements as $etablissement): ?>
                                    <option value="<?= $etablissement['id_etablissement'] ?>">
                                        <?= htmlspecialchars($etablissement['nom_etablissement']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="montant">Montant (€)</label>
                            <input type="number" id="montant" name="montant" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_debut">Date de Début</label>
                            <input type="date" id="date_debut" name="date_debut" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_fin">Date de Fin</label>
                            <input type="date" id="date_fin" name="date_fin" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taux_applique">Taux Appliqué (%)</label>
                            <input type="number" id="taux_applique" name="taux_applique" step="0.01" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="taux_Assurance">Taux d'Assurance (%)</label>
                            <input type="number" id="taux_Assurance" name="taux_Assurance" step="0.01" value="0" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Créer le Prêt</button>
                </form>
            </div>
            
            <!-- Onglet Calculateur -->
            <div id="calculateur" class="tab-content">
                <h2>Calculateur de Tableau d'Amortissement</h2>
                
                <form id="formCalcul">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="calc_montant">Montant du Prêt (€)</label>
                            <input type="number" id="calc_montant" name="montant" step="0.01" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="calc_taux">Taux Annuel (%)</label>
                            <input type="number" id="calc_taux" name="taux" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="calc_taux_Assurance">Taux d'Assurance (%)</label>
                            <input type="number" id="calc_taux_Assurance" name="taux_Assurance" step="0.01" value="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="calc_duree">Durée (mois)</label>
                            <input type="number" id="calc_duree" name="duree" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Calculer</button>
                </form>
                
                <div id="resultats" class="amortissement-table"></div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            const tabs = document.querySelectorAll('.tab');
            const contents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }
        
        function updateTaux() {
            const select = document.getElementById('id_type_pret');
            const tauxInput = document.getElementById('taux_applique');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.dataset.taux) {
                tauxInput.value = selectedOption.dataset.taux;
            }
        }
        
        function showMessage(message, type = 'success') {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => {
                messageDiv.innerHTML = '';
            }, 5000);
        }
        
        document.getElementById('formPret').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const montant = parseFloat(document.getElementById('montant').value);
            const taux = parseFloat(document.getElementById('taux_applique').value);
            const tauxAssurance = parseFloat(document.getElementById('taux_Assurance').value);
            const dateDebut = new Date(document.getElementById('date_debut').value);
            const dateFin = new Date(document.getElementById('date_fin').value);
            
            if (montant <= 0) {
                showMessage('Le montant doit être supérieur à 0.', 'error');
                return;
            }
            
            if (taux <= 0) {
                showMessage('Le taux appliqué doit être supérieur à 0.', 'error');
                return;
            }
            
            if (tauxAssurance < 0) {
                showMessage("Le taux d'assurance ne peut pas être négatif.", 'error');
                return;
            }
            
            if (dateFin <= dateDebut) {
                showMessage('La date de fin doit être postérieure à la date de début.', 'error');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'creer_pret');
            
            fetch('/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('Erreur de connexion', 'error');
            });
        });
        
        function updateStatut(idPret, nouveauStatut) {
            if (!nouveauStatut) return;
            
            const formData = new FormData();
            formData.append('action', 'update_statut');
            formData.append('id_pret', idPret);
            formData.append('nouveau_statut', nouveauStatut);
            
            fetch('/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    setTimeout(() => location.reload(), 1500);
                }
            });
        }
        
        document.getElementById('formCalcul').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const montant = parseFloat(document.getElementById('calc_montant').value);
            const taux = parseFloat(document.getElementById('calc_taux').value);
            const tauxAssurance = parseFloat(document.getElementById('calc_taux_Assurance').value);
            const duree = parseInt(document.getElementById('calc_duree').value);
            
            if (montant <= 0) {
                showMessage('Le montant doit être supérieur à 0.', 'error');
                return;
            }
            
            if (taux <= 0) {
                showMessage('Le taux appliqué doit être supérieur à 0.', 'error');
                return;
            }
            
            if (tauxAssurance < 0) {
                showMessage("Le taux d'assurance ne peut pas être négatif.", 'error');
                return;
            }
            
            if (duree <= 0) {
                showMessage('La durée doit être supérieure à 0.', 'error');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'calculer_amortissement');
            
            fetch('/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let tableauHTML = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Capital Initial</th>
                                <th>Intérêts</th>
                                <th>Amortissement</th>
                                <th>Assurance</th>
                                <th>Annuité</th>
                                <th>Capital Restant</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                data.forEach(ligne => {
                    tableauHTML += `
                        <tr>
                            <td>${ligne.mois}</td>
                            <td class="montant">${ligne.base.toLocaleString('fr-FR')} €</td>
                            <td class="montant">${ligne.interet.toLocaleString('fr-FR')} €</td>
                            <td class="montant">${ligne.amortissement.toLocaleString('fr-FR')} €</td>
                            <td class="montant">${ligne.assurance.toLocaleString('fr-FR')} €</td>
                            <td class="montant">${ligne.annuite.toLocaleString('fr-FR')} €</td>
                            <td class="montant">${ligne.val_fin.toLocaleString('fr-FR')} €</td>
                        </tr>
                    `;
                });
                
                tableauHTML += '</tbody></table>';
                document.getElementById('resultats').innerHTML = tableauHTML;
            });
        });
    </script>
</body>
</html>