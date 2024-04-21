const buttons = document.querySelectorAll("button");
const modal = document.querySelectorAll("dialog");


buttons.forEach(button =>{
    if(button.id.startsWith("abrirModal")){
        const sufixo = button.id.slice("abrirModal".length);
        button.onclick = function(){
            modal[sufixo].showModal();
        }
    }
    if(button.id.startsWith("fecharModal")){
        const sufixo2 = button.id.slice("fecharModal".length);
        button.onclick = function(){
            modal[sufixo2].close();
        }
    }
})