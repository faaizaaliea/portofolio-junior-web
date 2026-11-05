// jari-jari sepeda (object dengan constructor)
class Sepeda {
  constructor(jariJari) {
    this.jariJari = jariJari;
  }

  diameter() {
    return this.jariJari * 2;
  }
}
let sepedaSaya = new Sepeda(10);
console.log(
  `Diameter roda sepeda dengan jari-jari ${sepedaSaya.jariJari} cm: ${sepedaSaya.diameter()} cm`,
);
