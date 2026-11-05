function membandingkanArray(arr1, arr2) {
  return (
    arr1.length === arr2.length &&
    arr1.every((value, index) => value === arr2[index])
  );
}

let arr1 = [1, 2, 3];
let arr2 = [3, 2, 1];
console.log(membandingkanArray(arr1, arr2)); // Output: false
