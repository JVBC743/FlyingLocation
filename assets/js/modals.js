const imageButtons = document.querySelectorAll("button[id^='abrirModal']");
const imageModals = document.querySelectorAll("dialog[id^='modal']");
const imageCloseButtons = document.querySelectorAll("button[id^='fecharModal']");

const deleteButtons = document.querySelectorAll("button[id^='delAbrirModal']");
const deleteModals = document.querySelectorAll("dialog[id^='delModal']");
const deleteCloseButtons = document.querySelectorAll("button[id^='delFecharModal']");

imageButtons.forEach((button, index) => {
    button.addEventListener("click", () => {
        imageModals[index].showModal();
    });
});

imageCloseButtons.forEach((button, index) => {
    button.addEventListener("click", () => {
        imageModals[index].close();
    });
});

deleteButtons.forEach((button, index) => {
    button.addEventListener("click", () => {
        deleteModals[index].showModal();
    });
});

deleteCloseButtons.forEach((button, index) => {
    button.addEventListener("click", () => {
        deleteModals[index].close();
    });
});
