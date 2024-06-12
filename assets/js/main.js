const button = document.getElementById("AbrirModal")
const modal = document.getElementById("modal")
const buttonClose = document.getElementById("FechaModal")



button.onclick = function () {
    modal.showModal();
}

buttonClose.onclick = function () {
    modal.close();
}
