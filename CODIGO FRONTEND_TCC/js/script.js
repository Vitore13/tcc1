let body = document.querySelector("body")
let tab = document.querySelector(".tab-container")
let btn = document.querySelector(".btn")
let closeBtn = document.querySelector(".close")
let saida = document.querySelector(".saida")
let  idd = document.querySelector(".meupal")

btn.addEventListener('click', () => {
  body.classList.toggle('show')
})

closeBtn.addEventListener('click', () => {
  body.classList.toggle('show')
})

saida.addEventListener('click', () => {
 idd.classList.toggle()
})

