function showHelptext() {
    let helptext = document.getElementById(this.id + "_helptext");
    helptext.style.display = "inherit";
    if (this.value.length < 1) {
        helptext.style.display = "none";
    }
}
