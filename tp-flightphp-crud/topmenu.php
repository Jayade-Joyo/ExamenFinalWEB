<?php
/**
 * Template Top Menu - À inclure en haut de chaque page
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($pageTitle ?? 'Gestion Bancaire'); ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Styles du top menu */
    .topmenu {
      background: #2c3e50;
      color: white;
      display: flex;
      padding: 0;
      margin: 0;
      width: 100%;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .topmenu-nav {
      display: flex;
      width: 100%;
    }
    
    .topmenu-nav ul {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      width: 100%;
    }
    
    .topmenu-nav li {
      position: relative;
    }
    
    .nav-link {
      color: #ecf0f1;
      padding: 15px 20px;
      display: block;
      text-decoration: none;
      transition: all 0.3s;
      font-size: 14px;
    }
    
    .nav-link:hover, .nav-link.active {
      background: #34495e;
      color: #fff;
    }
    
    .nav-link i {
      margin-right: 8px;
      font-size: 16px;
    }
    
    .user-section {
      margin-left: auto;
      display: flex;
      align-items: center;
      padding: 0 20px;
      background: #1a252f;
    }
    
    .user-section span {
      margin-right: 15px;
      font-size: 14px;
    }
    
    .logout-btn {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 13px;
      transition: background 0.3s;
    }
    
    .logout-btn:hover {
      background: #c0392b;
    }
    
    /* Indicateur de page active */
    .nav-link.active {
      border-bottom: 3px solid #3498db;
    }
  </style>
</head>
<body>

<nav class="topmenu">
  <div class="topmenu-nav">
    <ul>
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
          <i class="fas fa-tachometer-alt"></i>
          <span>Tableau de bord</span>
        </a>
      </li>
      
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'ajouter_fonds.php' ? 'active' : ''; ?>" href="ajouter_fonds.php">
          <i class="fas fa-money-bill-wave"></i>
          <span>Ajouter Fonds</span>
        </a>
      </li>
      
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'clients_prets.php' ? 'active' : ''; ?>" href="clients_prets.php">
          <i class="fas fa-list-alt"></i>
          <span>Clients/prets</span>
        </a>
      </li>
      
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'prets.php' ? 'active' : ''; ?>" href="prets.php">
          <i class="fas fa-hand-holding-usd"></i>
          <span>Prêts</span>
        </a>
      </li>
      
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'remboursements.php' ? 'active' : ''; ?>" href="remboursements.php">
          <i class="fas fa-cash-register"></i>
          <span>Remboursements</span>
        </a>
      </li>
      
      <li>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'interets.php' ? 'active' : ''; ?>" href="interets.php">
          <i class="fas fa-coins"></i>
          <span>Intérêts Gagnés</span>
        </a>
      </li>
    </ul>
    
    <div class="user-section">
      <span>Connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong></span>
      <button class="logout-btn" onclick="window.location.href='logout.php'">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </button>
    </div>
  </div>
</nav>

