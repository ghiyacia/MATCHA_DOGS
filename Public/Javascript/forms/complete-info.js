const buttonChoose = document.getElementById('choose-file-button');
const chooseFile = document.getElementById('choose-file-real');
const profileImage = document.getElementById('profile-image');

buttonChoose.addEventListener("click", function() {
    chooseFile.click();
});

chooseFile.addEventListener("change", function() {
    let type = this.files[0].type;
    if (type == "image/png" || type == "image/jpeg") {
        profileImage.src = URL.createObjectURL(this.files[0]);
    } else {
        this.value = "";
        alert('Seul les images au format .png et .jpg - .jpeg sont acceptées');
    }
})