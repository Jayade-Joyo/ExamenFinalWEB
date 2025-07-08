<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des étudiants</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Ajout de Font Awesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <!-- Inclusion du top menu -->
  <?php include 'topmenu.php'; ?>

  <div>
  <label for="filtre-age">Filtrer par âge :</label>
  <input type="number" id="filtre-age" placeholder="Âge exact">
  <button onclick="filtrerParAge()">Filtrer</button>
  <button onclick="chargerEtudiants()">Réinitialiser</button>
</div>

<div>
  <label for="age-min">Âge min :</label>
  <input type="number" id="age-min" placeholder="Ex: 18">

  <label for="age-max">Âge max :</label>
  <input type="number" id="age-max" placeholder="Ex: 25">

  <button onclick="filtrerParIntervalle()">Filtrer</button>
  <button onclick="chargerEtudiants()">Réinitialiser</button>
</div>




  <div class="main-content">
    <h1>Gestion des étudiants</h1>

    <div>
      <input type="hidden" id="id">
      <input type="text" id="nom" placeholder="Nom">
      <input type="text" id="prenom" placeholder="Prénom">
      <input type="email" id="email" placeholder="Email">
      <input type="number" id="age" placeholder="Âge">
      <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
    </div>

    <table id="table-etudiants">
      <thead>
        <tr>
          <th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Âge</th><th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <script>
      const apiBase = "http://localhost/ExamenFinalWEB/tp-flightphp-crud/ws";

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

      function filtrerParAge() {
        const age = document.getElementById("filtre-age").value;
        if (age) {
          ajax("GET", `/etudiants/age/${age}`, null, (data) => {
            const tbody = document.querySelector("#table-etudiants tbody");
            tbody.innerHTML = "";
            data.forEach(e => {
              const tr = document.createElement("tr");
              tr.innerHTML = `
                <td>${e.id}</td>
                <td>${e.nom}</td>
                <td>${e.prenom}</td>
                <td>${e.email}</td>
                <td>${e.age}</td>
                <td>
                  <button onclick='remplirFormulaire(${JSON.stringify(e)})'>✏️</button>
                  <button onclick='supprimerEtudiant(${e.id})'>🗑️</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          });
        }
      }

      function filtrerParIntervalle() {
        const ageMin = document.getElementById("age-min").value;
        const ageMax = document.getElementById("age-max").value;

        if (ageMin && ageMax) {
          ajax("GET", `/etudiants/age?min=${ageMin}&max=${ageMax}`, null, (data) => {
            const tbody = document.querySelector("#table-etudiants tbody");
            tbody.innerHTML = "";
            data.forEach(e => {
              const tr = document.createElement("tr");
              tr.innerHTML = `
                <td>${e.id}</td>
                <td>${e.nom}</td>
                <td>${e.prenom}</td>
                <td>${e.email}</td>
                <td>${e.age}</td>
                <td>
                  <button onclick='remplirFormulaire(${JSON.stringify(e)})'>✏️</button>
                  <button onclick='supprimerEtudiant(${e.id})'>🗑️</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          });
        } else {
          alert("Veuillez entrer un âge min et max.");
        }
      }


      function chargerEtudiants() {
        ajax("GET", "/etudiants", null, (data) => {
          const tbody = document.querySelector("#table-etudiants tbody");
          tbody.innerHTML = "";
          data.forEach(e => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${e.id}</td>
              <td>${e.nom}</td>
              <td>${e.prenom}</td>
              <td>${e.email}</td>
              <td>${e.age}</td>
              <td>
                <button onclick='remplirFormulaire(${JSON.stringify(e)})'>✏️</button>
                <button onclick='supprimerEtudiant(${e.id})'>🗑️</button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        });
      }

      function ajouterOuModifier() {
        const id = document.getElementById("id").value;
        const nom = document.getElementById("nom").value;
        const prenom = document.getElementById("prenom").value;
        const email = document.getElementById("email").value;
        const age = document.getElementById("age").value;

        const data = `nom=${encodeURIComponent(nom)}&prenom=${encodeURIComponent(prenom)}&email=${encodeURIComponent(email)}&age=${age}`;

        if (id) {
          ajax("PUT", `/etudiants/${id}`, data, () => {
            resetForm();
            chargerEtudiants();
          });
        } else {
          ajax("POST", "/etudiants", data, () => {
            resetForm();
            chargerEtudiants();
          });
        }
      }

      function remplirFormulaire(e) {
        document.getElementById("id").value = e.id;
        document.getElementById("nom").value = e.nom;
        document.getElementById("prenom").value = e.prenom;
        document.getElementById("email").value = e.email;
        document.getElementById("age").value = e.age;
      }

      function supprimerEtudiant(id) {
        if (confirm("Supprimer cet étudiant ?")) {
          ajax("DELETE", `/etudiants/${id}`, null, () => {
            chargerEtudiants();
          });
        }
      }

      function resetForm() {
        document.getElementById("id").value = "";
        document.getElementById("nom").value = "";
        document.getElementById("prenom").value = "";
        document.getElementById("email").value = "";
        document.getElementById("age").value = "";
      }

      chargerEtudiants();
    </script>
  </div>
</body>
</html>