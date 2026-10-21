document.addEventListener("DOMContentLoaded", () => {
    const tablaProductos = document.getElementById("tabla-productos");
    const buscador = document.getElementById("buscador");
    const formProducto = document.getElementById("form-productos");
    const totalProductos = document.getElementById("total-productos");

    const API_URL = "api/productos.php";

    function cargarProductos(termino = "") {
        let url = API_URL;
        if (termino.trim() !== "") {
            url += `?q=${encodeURIComponent(termino)}`;
        }

        fetch(url)
        
            .then(response => response.json())
            .then(data => {
                console.log(data)
                tablaProductos.innerHTML = "";

                if (data.length === 0) {
                    tablaProductos.innerHTML = `<tr><td colspan="3" style="text-align: center;">No se encontraron productos.</td></tr>`;
                    totalProductos.textContent = "Total: 0 productos";
                    return;
                }

                data.forEach(producto => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${producto.Nombre}</td>
                        <td>${producto.Precio} $ARS</td>
                        <td>${producto.Stock}</td>
                        <td><button class="secondary outline" onclick="eliminarProducto(${producto.id})">Eliminar</button>
                        <button class="secondary outline" onclick="editarProducto(${producto.id})">Editar</button></td> 
                    `;
                    tablaProductos.appendChild(fila);
                });

                totalProductos.textContent = `Total: ${data.length} productos`;
            })
            .catch(error => console.error("Error al cargar los productos:", error));
    }

    window.eliminarProducto = function(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            fetch(`api/productos.php?id=${id}`, {
                method: "DELETE"
            })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.success) {
                    cargarProductos(); 
                } else {
                    alert("Error: " + resultado.message);
                }
            })
            .catch(error => console.error("Error al eliminar:", error));
        }
    }

    window.editarProducto = function(id) {
        window.location.href = `editar.php?id=${id}`;
    };

    cargarProductos();

    buscador.addEventListener("input", (e) => {
        const termino = e.target.value;
        cargarProductos(termino);
    });

    formProducto.addEventListener("submit", (e) => {
        e.preventDefault();

        const nuevoProducto = {
            nombre: document.getElementById("nombre").value,
            precio: document.getElementById("precio").value,
            stock: document.getElementById("stock").value,
        };

        fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(nuevoProducto)
        })
        .then(response => response.json())
        .then(resultado => {
            if (resultado.success) {
                formProducto.reset();
                cargarProductos();
            } else {
                alert("Error: " + resultado.message);
            }
        })
        .catch(error => console.error("Error al enviar el formulario:", error));
    });
});