document.querySelectorAll('.button').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        window.location.href = url;
    });
});
