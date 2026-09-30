export function calcularPromedio(notas) {
    return notas.reduce((a, b) => a + b, 0) / notas.length;
}

export function estaAprobado(notas,minima) {
    let respuesta = 'Desaprobado';
    if (notas >= minima){
    	respuesta = 'Aprobado';
    }
    return respuesta;
}