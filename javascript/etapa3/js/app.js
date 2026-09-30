function calcularPromedio(notas) {
  if (!notas || notas.length === 0) return 0;
  const suma = notas.reduce((acumulador, nota) => acumulador + nota, 0);
  return suma / notas.length;
}

function estaAprobado(nota, minima = 6) {
  return nota >= minima;
}

function obtenerCalificacion(promedio) {
  if (promedio >= 9) return "Sobresaliente";
  if (promedio >= 7.5) return "Muy bueno";
  if (promedio >= 6) return "Bueno";
  return "Desaprobado";
}

const formulario = document.getElementById("formulario");
const cuerpoTabla = document.getElementById("cuerpo-tabla");
const btnLimpiar = document.getElementById("btn-limpiar");

//tabla
formulario.addEventListener("submit", function(event) {
  event.preventDefault();

  const nombre = document.getElementById("name").value.trim();
  const notasInput = document.getElementById("notas").value;
  const notaMinimaInput = document.getElementById("minima").value;

  const notaMinima = notaMinimaInput !== "" ? parseFloat(notaMinimaInput) : 6;

  const notasArray = notasInput
    .split(",")
    .map(n => parseFloat(n.trim()))
    .filter(n => !isNaN(n));

  if (notasArray.length === 0) {
    alert("Por favor, ingresa al menos una nota válida.");
    return;
  }

  const promedio = Number(calcularPromedio(notasArray).toFixed(2));
  const calificacion = obtenerCalificacion(promedio);
  const aprobado = estaAprobado(promedio, notaMinima);

  const nuevaFila = document.createElement("tr");

  nuevaFila.innerHTML = `
    <td>${nombre}</td>
    <td>${promedio}</td>
    <td>${calificacion}</td>
    <td>${aprobado ? "Aprobado" : "Desaprobado"}</td>
  `;

  cuerpoTabla.appendChild(nuevaFila);

  document.getElementById("name").value = "";
  document.getElementById("notas").value = "";
});

// Evento para borrar todo el contenido de la tabla
btnLimpiar.addEventListener("click", function() {
  if (cuerpoTabla.children.length === 0) {
    alert("La tabla ya está vacía.");
    return;
  }

  cuerpoTabla.innerHTML = "";
});