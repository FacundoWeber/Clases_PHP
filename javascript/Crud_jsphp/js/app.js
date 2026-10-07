document.addEventListener("DOMContentLoaded", () => {
    const tablaEstudiantes = document.getElementById("tabla-estudiantes");
    const buscador = document.getElementById("buscador");
    const formEstudiante = document.getElementById("form-estudiante");
    const totalEstudiantes = document.getElementById("total-estudiantes");

    const API_URL = "api/estudiantes.php";

    function cargarEstudiantes(termino = "") {
        let url = API_URL;
        if (termino.trim() !== "") {
            url += `?q=${encodeURIComponent(termino)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tablaEstudiantes.innerHTML = "";

                if (data.length === 0) {
                    tablaEstudiantes.innerHTML = `<tr><td colspan="3" style="text-align: center;">No se encontraron estudiantes.</td></tr>`;
                    totalEstudiantes.textContent = "Total: 0 estudiantes";
                    return;
                }

                data.forEach(estudiante => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${estudiante.nombre} ${estudiante.apellido}</td>
                        <td>${estudiante.email}</td>
                        <td><button class="secondary outline" onclick="eliminarEstudiante(${estudiante.id})">Eliminar</button></td>
                    `;
                    tablaEstudiantes.appendChild(fila);
                });

                totalEstudiantes.textContent = `Total: ${data.length} estudiantes`;
            })
            .catch(error => console.error("Error al cargar los estudiantes:", error));
    }

    window.eliminarEstudiante = function(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este estudiante?")) {
            fetch(`api/estudiantes.php?id=${id}`, {
                method: "DELETE"
            })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.success) {
                    cargarEstudiantes(); 
                } else {
                    alert("Error: " + resultado.message);
                }
            })
            .catch(error => console.error("Error al eliminar:", error));
        }
    }

    cargarEstudiantes();

    buscador.addEventListener("input", (e) => {
        const termino = e.target.value;
        cargarEstudiantes(termino);
    });

    formEstudiante.addEventListener("submit", (e) => {
        e.preventDefault();

        const nuevoEstudiante = {
            nombre: document.getElementById("nombre").value,
            apellido: document.getElementById("apellido").value,
            email: document.getElementById("email").value
        };

        fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(nuevoEstudiante)
        })
        .then(response => response.json())
        .then(resultado => {
            if (resultado.success) {
                formEstudiante.reset();
                cargarEstudiantes();
            } else {
                alert("Error: " + resultado.message);
            }
        })
        .catch(error => console.error("Error al enviar el formulario:", error));
    });
});