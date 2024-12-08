function toggleIban() {
    const iban = document.getElementById('iban-content');
    iban.style.display = iban.style.display === 'none' || !iban.style.display ? 'block' : 'none';
}