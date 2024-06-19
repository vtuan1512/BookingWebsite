document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector(".typing-area");
    const incoming_id = form.querySelector(".incoming_id").value;
    const inputField = form.querySelector(".input-field");
    const sendBtn = form.querySelector("button");
    const chatBox = document.querySelector(".chat-box");

    form.onsubmit = (e)=>{
        e.preventDefault();
    };

    inputField.focus();
    inputField.onkeyup =()=>{
        if(inputField.value !== ""){
            sendBtn.classList.add("active");
        } else {
            sendBtn.classList.remove("active");
        }
    };

    sendBtn.onclick=()=>{
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/insert_manager.php", true);
        xhr.onload = ()=>{
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    inputField.value = "";
                    scrollBottom();
                }
            }
        };
        let formData = new FormData(form);
        xhr.send(formData);
    };

    chatBox.onmouseenter = () => {
        chatBox.classList.add("active");
    };
    chatBox.onmouseleave =()=>{
        chatBox.classList.remove("active"); 
    };

    setInterval(()=>{
        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/get-chat_manager.php",true);
        xhr.onload = ()=>{
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    chatBox.innerHTML = data;
                    if(!chatBox.classList.contains("active")){
                        scrollBottom();
                    }
                }
            }
        }

        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("incoming_id="+incoming_id);

    },500);

    function scrollBottom(){
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
