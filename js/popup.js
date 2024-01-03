let user_popup = document.getElementById("popup-user-logo");
let user_popup_bt = document.getElementById("popup-bt-user");
let popup = document.getElementsByClassName("popup")[0];

user_popup_bt.onclick = () => {
    user_popup.classList.toggle("show");
    popup.style.display = "block";
}

window.onclick = (event) => {
    if (event.target == popup) {
        popup.style.display = "none";
        for (let p of document.getElementsByClassName("popup-container")) {
            p.classList.remove("show");
        }
    }
}

function showMusicPopup(e) {
    let music_popup = e.nextElementSibling;
    music_popup.classList.toggle("show");
    popup.style.display = "block";
}



