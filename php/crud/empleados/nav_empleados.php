<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../../../icons/ceti_escudo.png" alt="20110404" height="24">  
      Tiendita - Gestión de Productos
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index_empleados.php">Lista de Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../login/logout.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  /* Fondo degradado para la barra de navegación */
  .bg-gradient {
    background: linear-gradient(90deg, #28a745, #20c997);
  }

  /* Estilo para los enlaces del menú */
  .navbar-nav .nav-link {
    transition: color 0.3s ease;
  }

  .navbar-nav .nav-link:hover {
    color: rgb(7, 135, 255) !important; /* Cambia a azul al pasar el mouse */
  }

  /* Estilo para el texto de la marca */
  .navbar-brand {
    font-size: 1.25rem;
  }
</style>