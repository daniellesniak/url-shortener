let is_private = false;
document.getElementById('is-private').addEventListener("click", function(e) {
    e.preventDefault()
    is_private = !is_private
    if (is_private) {
        this.classList.add('is-active')
        document.getElementById('is_private').value = true
        document.getElementById('is_private_info').classList.remove('is-visible')
    }

    if (!is_private) {
        this.classList.remove('is-active')
        document.getElementById('is_private').value = false
        document.getElementById('is_private_info').classList.add('is-visible')
    }
})