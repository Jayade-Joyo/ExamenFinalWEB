<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Simulation de remboursement</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <?php include 'topmenu.php'; ?>

  <div class="main-content">
    <h1>Simulation de remboursement d'un prêt</h1>

    <div>
      <label for="id-pret">ID du prêt :</label>
      <input type="number" id="id-pret" placeholder="Ex: 1">
      <button onclick="genererSimulation()">Générer l'échéancier</button>
      <button onclick="validerSimulation()">Valider et enregistrer</button>
    </div>

    <table id="table-echeancier">
      <thead>
        <tr>
          <th>#</th>
          <th>Mois</th>
          <th>Année</th>
          <th>Base</th>
          <th>Intérêt</th>
          <th>Amortissement</th>
          <th>Annuite</th>
          <th>Valeur finale</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <script>
    const apiBase = "/ETU003366/ExamenFinalWEB/tp-flightphp-crud/ws"; // Vérifiez ce chemin !
      // const apiBase = "http://localhost/ExamenFinalWEB/tp-flightphp-crud/ws";

      function ajax(method, url, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, apiBase + url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              try {
                callback(JSON.parse(xhr.responseText));
              } catch (e) {
                alert("Erreur de parsing JSON : " + e.message);
              }
            } else {
              alert("Erreur HTTP : " + xhr.status + " - " + xhr.responseText);
            }
          }
        };
        xhr.send(data);
      }

      function genererSimulation() {
        const idPret = document.getElementById("id-pret").value;
        if (!idPret) return alert("Veuillez entrer un ID de prêt.");

        ajax("GET", `/simulation/pret/${idPret}`, null, (data) => {
          if (data.remboursements) {
            const tbody = document.querySelector("#table-echeancier tbody");
            tbody.innerHTML = "";

            data.remboursements.forEach((r, i) => {
              const tr = document.createElement("tr");
              tr.innerHTML = `
                <td>${i + 1}</td>
                <td>${r.mois}</td>
                <td>${r.annee}</td>
                <td>${parseFloat(r.base).toFixed(2)} €</td>
                <td>${parseFloat(r.interet).toFixed(2)} €</td>
                <td>${parseFloat(r.amortissement).toFixed(2)} €</td>
                <td>${parseFloat(r.annuite).toFixed(2)} €</td>
                <td>${parseFloat(r.val_fin).toFixed(2)} €</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            alert(data.message || "Aucune donnée retournée.");
          }
        });
      }

      function validerSimulation() {
        const idPret = document.getElementById("id-pret").value;
        if (!idPret) return alert("Veuillez entrer un ID de prêt.");
        genererSimulation();
        ajax("POST", `/remboursement/simuler-et-enregistrer/${idPret}`, null, (data) => {
          alert(data.message);
        });
      }
    </script>
  </div>
</body>
</html>