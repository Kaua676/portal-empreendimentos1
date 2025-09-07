function validarSenha(senha) {
  const minLength = senha.length >= 8;
  const hasUppercase = /[A-Z]/.test(senha);
  const hasNumber = /\d/.test(senha);
  const hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(senha);

  return minLength && hasUppercase && hasNumber && hasSpecialChar;
}
