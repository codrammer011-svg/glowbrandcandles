var dropdown = document.getElementById('demosMenu');
if (dropdown) {
dropdown.addEventListener('change', function(e){
    var dropdown = dropdown;
    window.location.href = dropdown.options[dropdown.selectedIndex].getAttribute('id') + '.html';
});
}
