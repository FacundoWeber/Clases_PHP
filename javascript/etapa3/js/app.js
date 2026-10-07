const cuerpoTabla = document.getElementById("cuerpo-tabla");
const btnRecargar = document.getElementById("btn-recargar");
const divCargando = document.getElementById("cargando");

// Función para obtener y mostrar los usuarios
function cargarUsuarios() {
  divCargando.style.display = "block";
  cuerpoTabla.innerHTML = "";

  fetch("https://jsonplaceholder.typicode.com/users")
    .then(response => {
      if (!response.ok) {
        throw new Error("Error en la red al intentar obtener los usuarios.");
      }
      return response.json();
    })
    .then(usuarios => {
      divCargando.style.display = "none";

      usuarios.forEach(usuario => {
        const nuevaFila = document.createElement("tr");

        // El JSON de la API tiene la ciudad dentro del objeto anidado `address`
        nuevaFila.innerHTML = `
          <td>${usuario.name}</td>
          <td>${usuario.email}</td>
          <td>${usuario.address.city}</td>
        `;

        cuerpoTabla.appendChild(nuevaFila);
      });
    })
    .catch(error => {
      divCargando.style.display = "none";
      console.error("Hubo un problema con la petición Fetch:", error);
      cuerpoTabla.innerHTML = `<tr><td colspan="3" style="color: red; text-align: center;">Error al cargar los datos.</td></tr>`;
    });
}

// Cargar los usuarios automáticamente al cargar la página
document.addEventListener("DOMContentLoaded", cargarUsuarios);

// Evento para el botón 'Recargar'
btnRecargar.addEventListener("click", cargarUsuarios);