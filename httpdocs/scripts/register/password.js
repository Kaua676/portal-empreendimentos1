const passwordInput = document.getElementById('password');
const lengthValidate = document.getElementById('length_validate');
const uppercaseValidate = document.getElementById('uppercase_validate');
const numberValidate = document.getElementById('number_validate');
const specialValidate = document.getElementById('special_validate');

function toggleClass(el, ok) {
  el.classList.remove('valid', 'invalid');
  el.classList.add(ok ? 'valid' : 'invalid');
}

passwordInput.addEventListener('input', () => {
  const pw = passwordInput.value;
  toggleClass(lengthValidate, pw.length >= 8);
  toggleClass(uppercaseValidate, /[A-Z]/.test(pw));
  toggleClass(numberValidate, /\d/.test(pw));
  toggleClass(specialValidate, /[!@#$%^&*(),.?":{}|<>-]/.test(pw));
});
