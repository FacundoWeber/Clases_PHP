import Estudiante, { calcularPromedio, VERSION } from './main.js';

const ana = new Estudiante('Ana', [8, 9, 7.5]);
console.log(ana.getPromedio());       // 8.166...
console.log(calcularPromedio([5, 6])); // 5.5
console.log(VERSION);                  // 1.0.0
