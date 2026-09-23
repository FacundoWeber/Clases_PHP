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

class Estudiante {
    constructor(nombre, notas) {
        this.nombre = nombre;
        this.notas  = notas;
    }
    getNombre(){ return this.nombre; }
    getPromedio() { return calcularPromedio(this.notas); }
}

Estudiante, { calcularPromedio};

const facu = new Estudiante('Facundo', [10, 6, 7]);
const jorge = new Estudiante('Jorge', [5, 5, 7]);
console.log([facu.getNombre(),facu.getPromedio(),estaAprobado(facu.getPromedio(),6)]);
console.log([jorge.getNombre(),jorge.getPromedio(),estaAprobado(jorge.getPromedio(),6)]);