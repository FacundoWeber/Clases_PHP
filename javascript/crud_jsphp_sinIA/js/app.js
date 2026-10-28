document.addEventListener("DOMContentLoaded", () => {
    const tablaProductos = document.getElementById("tabla-productos");
    const tablaProductosVenta = document.getElementById("tabla-productosventa");
    const buscador = document.getElementById("buscador");
    const formProducto = document.getElementById("form-productos");
    const totalProductos = document.getElementById("total-productos");

    const API_URL = "api/productos.php";
    const API_VENTAS_URL = "api/ventas.php"; // URL donde procesarás la venta en el backend
    
    let productosCache = []; 
    let carritoVenta = {}; // Almacenará los productos seleccionados: { id_producto: cantidad }
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
                renderizarTablaventa(productosCache);
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

    function renderizarTablaventa(data) {
        tablaProductosVenta.innerHTML = "";

        if (data.length === 0) {
            tablaProductosVenta.innerHTML = `<tr><td colspan="5" style="text-align: center;">No se encontraron productos.</td></tr>`;
            return;
        }

        let totalVentaGeneral = 0;

        data.forEach(producto => {
            const precioNum = parseFloat(producto.Precio) || 0;
            const stockNum = parseInt(producto.Stock) || 0;
            const cantidadEnCarrito = carritoVenta[producto.Id] || 0;
            const subtotalItem = precioNum * cantidadEnCarrito;
            totalVentaGeneral += subtotalItem;

            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${producto.Nombre}</td>
                <td>$ ${precioNum.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS</td>
                <td>${stockNum}</td>
                <td><strong>${cantidadEnCarrito}</strong></td>
                <td>$ ${subtotalItem.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS</td>
                <td>
                    <button type="button" class="secondary outline" onclick="incrementarVenta(${producto.Id})">+</button>
                    <button type="button" class="secondary outline" onclick="disminuirVenta(${producto.Id})">-</button>
                </td>
            `;
            tablaProductosVenta.appendChild(fila);  
        });

        // Agregamos una fila al final con el total general y el botón de "Aceptar Venta"
        const filaTotal = document.createElement("tr");
        filaTotal.innerHTML = `
            <td colspan="4" style="text-align: right;"><strong>Total de la Venta:</strong></td>
            <td><strong>$ ${totalVentaGeneral.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ARS</strong></td>
            <td>
                <button type="button" class="contrast" onclick="finalizarVenta()">Aceptar Venta</button>
            </td>
        `;
        tablaProductosVenta.appendChild(filaTotal);
    }

    // Funciones globales para el manejo del carrito de ventas
    window.incrementarVenta = function(id) {
        const producto = productosCache.find(p => p.Id == id);
        if (!producto) return;

        const stockDisponible = parseInt(producto.Stock) || 0;
        const actualEnCarrito = carritoVenta[id] || 0;

        if (actualEnCarrito + 1 > stockDisponible) {
            alert("No hay suficiente stock disponible para este producto.");
            return;
        }

        carritoVenta[id] = actualEnCarrito + 1;
        renderizarTablaventa(productosCache);
    };

    window.disminuirVenta = function(id) {
        if (!carritoVenta[id] || carritoVenta[id] <= 0) return;

        carritoVenta[id]--;
        if (carritoVenta[id] === 0) {
            delete carritoVenta[id];
        }
        renderizarTablaventa(productosCache);
    };

    window.finalizarVenta = function() {
        const itemsAComprar = Object.keys(carritoVenta).map(id => ({
            id_producto: parseInt(id),
            cantidad: carritoVenta[id]
        }));

        if (itemsAComprar.length === 0) {
            alert("El carrito de venta está vacío. Selecciona al menos un producto.");
            return;
        }

        if (!confirm("¿Deseas confirmar y registrar esta venta?")) {
            return;
        }

        // Enviamos la cabecera y el detalle en un solo JSON al backend
        fetch(API_VENTAS_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ productos: itemsAComprar })
        })
        .then(response => response.json())
        .then(resultado => {
            if (resultado.success) {
                alert("¡Venta registrada con éxito!");
                carritoVenta = {}; // Limpiamos el carrito
                cargarProductos(); // Recargamos productos para actualizar el stock en pantalla
            } else {
                alert("Error al registrar la venta: " + resultado.message);
            }
        })
        .catch(error => console.error("Error al registrar la venta:", error));
    };

    // Ordenar y eliminar habituales
    window.ordenarPor = function(columna) { /* ... lógica de ordenamiento ... */ };
    
    window.eliminarProducto = function(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            fetch(`api/productos.php?id=${id}`, { method: "DELETE" })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.success) cargarProductos();
                else alert("Error: " + resultado.message);
            })
            .catch(error => console.error("Error al eliminar:", error));
        }
    };

    window.editarProducto = function(id) {
        window.location.href = `editar.php?id=${id}`;
    };

    cargarProductos();

    let debounceTimer;
    buscador.addEventListener("input", (e) => {
        clearTimeout(debounceTimer);
        const termino = e.target.value;
        debounceTimer = setTimeout(() => {
            cargarProductos(termino);
        }, 300);
    });

    if (formProducto) {
        formProducto.addEventListener("submit", (e) => {
            e.preventDefault();
            const nombreVal = document.getElementById("nombre").value.trim();
            const precioVal = parseFloat(document.getElementById("precio").value);
            const stockVal = parseInt(document.getElementById("stock").value);

            if (!nombreVal || isNaN(precioVal) || precioVal < 0 || isNaN(stockVal) || stockVal < 0) {
                alert("Por favor, introduce datos válidos.");
                return;
            }

            fetch(API_URL, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ nombre: nombreVal, precio: precioVal, stock: stockVal })
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