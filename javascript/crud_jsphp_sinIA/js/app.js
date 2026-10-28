document.addEventListener("DOMContentLoaded", () => {
    const tablaProductos = document.getElementById("tabla-productos");
    const buscador = document.getElementById("buscador");
    const formProducto = document.getElementById("form-productos");
    const totalProductos = document.getElementById("total-productos");

    const API_URL = "api/productos.php";
    let productosCache = []; 
    let ordenActual = { columna: null, asc: true };

    function cargarProductos(termino = "") {
        let url = API_URL;
        if (termino.trim() !== "") {
            url += `?q=${encodeURIComponent(termino)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                productosCache = data;
                renderizarTabla(productosCache);
            })
            .catch(error => console.error("Error al cargar los productos:", error));
    }

    function renderizarTabla(data) {
        tablaProductos.innerHTML = "";

        if (data.length === 0) {
            tablaProductos.innerHTML = `<tr><td colspan="5" style="text-align: center;">No se encontraron productos.</td></tr>`;
            totalProductos.textContent = "Total: 0 productos | Valor del inventario: $0.00 ARS";
            return;
        }

        let valorInventarioTotal = 0;

        data.forEach(producto => {
            const precioNum = parseFloat(producto.Precio) || 0;
            const stockNum = parseInt(producto.Stock) || 0;
            const subtotal = precioNum * stockNum;
            valorInventarioTotal += subtotal;

            // Resaltar stock bajo (<= 5 unidades)
            const esStockBajo = stockNum <= 5;
            const estiloFila = esStockBajo ? 'style="background-color: rgba(255, 99, 71, 0.1);" title="¡Stock bajo!"' : '';

            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${producto.Nombre}</td>
                <td>$ ${precioNum.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS</td>
                <td ${estiloFila}>${stockNum} ${esStockBajo ? '⚠️' : ''}</td>
                <td>$ ${subtotal.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS</td>
                <td>
                    <button class="secondary outline" onclick="eliminarProducto(${producto.Id})">Eliminar</button>
                    <button class="secondary outline" onclick="editarProducto(${producto.Id})">Editar</button>
                </td>
            `;
            tablaProductos.appendChild(fila);
        });

        totalProductos.textContent = `Total: ${data.length} productos | Valor del inventario: $ ${valorInventarioTotal.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS`;
    }

    // Ordenar por columna al hacer clic en los encabezados
    window.ordenarPor = function(columna) {
        if (ordenActual.columna === columna) {
            ordenActual.asc = !ordenActual.asc;
        } else {
            ordenActual.columna = columna;
            ordenActual.asc = true;
        }

        productosCache.sort((a, b) => {
            let valA = a[columna];
            let valB = b[columna];

            if (!isNaN(valA) && !isNaN(valB)) {
                valA = Number(valA);
                valB = Number(valB);
            } else {
                valA = String(valA).toLowerCase();
                valB = String(valB).toLowerCase();
            }

            if (valA < valB) return ordenActual.asc ? -1 : 1;
            if (valA > valB) return ordenActual.asc ? 1 : -1;
            return 0;
        });

        renderizarTabla(productosCache);
    };

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

    // Debounce en el buscador (espera 300ms antes de consultar)
    let debounceTimer;
    buscador.addEventListener("input", (e) => {
        clearTimeout(debounceTimer);
        const termino = e.target.value;
        debounceTimer = setTimeout(() => {
            cargarProductos(termino);
        }, 300);
    });

    // Validación del formulario en el cliente antes de enviar por POST
    if (formProducto) {
        formProducto.addEventListener("submit", (e) => {
            e.preventDefault();

            const nombreVal = document.getElementById("nombre").value.trim();
            const precioVal = parseFloat(document.getElementById("precio").value);
            const stockVal = parseInt(document.getElementById("stock").value);

            if (!nombreVal || isNaN(precioVal) || precioVal < 0 || isNaN(stockVal) || stockVal < 0) {
                alert("Por favor, introduce datos válidos. El precio y el stock deben ser numéricos y mayores o iguales a 0.");
                return;
            }

            const nuevoProducto = {
                nombre: nombreVal,
                precio: precioVal,
                stock: stockVal
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
    }
});