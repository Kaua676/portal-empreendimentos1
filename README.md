# 📘 README – Portal Consulta Empreendimentos

## 📌 Visão Geral

**Portal Consulta Empreendimentos** é uma aplicação web em **PHP** voltada a **cidadãos**, **empresas** e **contadores** que precisam **consultar, regularizar e acompanhar dados de empresas** junto ao município.

O sistema oferece:

- Cadastro e autenticação de usuários
- Mecanismos de segurança (CAPTCHA, bloqueio, 2FA)
- Consultas a inscrições municipais, débitos, alvarás, licenças
- Emissão de boletos bancários e PDFs

---

## 🏗 Estrutura do Projeto

```
Portal-Empreendimentos/
├─ backend/
│  ├─ php/
│  │  ├─ api/               # Endpoints para contato e notícias
│  │  ├─ config/            # Configuração de banco e e-mail
│  │  ├─ public/            # APIs públicas (login, perfil, boletos, etc.)
│  │  └─ services/          # Funções auxiliares (ex.: geração de boleto)
│  ├─ composer.json         # Dependências PHP (PHPMailer, Dompdf, etc.)
│  └─ vendor/               # Bibliotecas instaladas via Composer
└─ httpdocs/
   ├─ *.php                 # Páginas (home, login, perfil, consultas…)
   ├─ assets/               # Imagens e uploads de usuários
   ├─ css/                  # Estilos (Bootstrap + estilos próprios)
   ├─ includes/             # Header/footer e modais
   └─ scripts/              # JavaScript modular (login, cadastro, utilitários)
```

---

## ✨ Funcionalidades Principais

### 🔐 Autenticação e Segurança

- Cadastro com validação de CPF, endereço e senha forte
- Login com **CAPTCHA**, limite de tentativas e bloqueio
- **Autenticação em duas etapas (2FA)** via e‑mail
- Recuperação de senha com envio de senha temporária
- Força de troca de senha em casos específicos
- Atualização de perfil com troca de senha e foto

### 🏢 Consultas Empresariais

- **Inscrição Municipal**: busca por IM, CNPJ ou razão social
- **Débitos**: listagem e geração de boletos (PDF Bradesco)
- **Alvarás e Certidões**: consulta por tipo, número e validade
- **Licenças**: visualização das licenças associadas
- **Ficha Cadastral**: geração de PDF com dados e CNAEs

### 📰 Conteúdo e Comunicação

- **Notícias**: carrossel e páginas detalhadas
- **Contato**: formulário com envio de e-mail ao suporte
- **Área do Usuário**: dados pessoais e configurações

### ♿ Acessibilidade

- Suporte a VLibras, alto contraste, fonte ampliada
- Interface responsiva com **Bootstrap**

---

## ⚙️ Requisitos

- PHP ≥ 8.0
- MySQL/MariaDB (banco `portalemp`)
- Composer
- Extensões PHP: `mysqli`, `openssl`, `mbstring`, `curl`, `intl`, etc.
- Servidor web (Apache/Nginx) ou embutido (PHP built-in)
- Dependências:
  - `phpmailer/phpmailer`
  - `dompdf/dompdf` ou `fpdf/fpdf`

---

## 🚀 Instalação e Configuração

```bash
git clone https://github.com/Kaua676/Portal-Empreendimentos.git
cd Portal-Empreendimentos/backend
composer install
```

### Banco de Dados

- Crie o banco `portalemp` e as tabelas (modelo não incluído)
- Edite `backend/php/config/database.php` com as credenciais

### E-mail

- Edite `backend/php/config/email.php` com os dados SMTP

### Permissões

- Permitir escrita em `httpdocs/assets/uploads/`

### Execução

```bash
cd ..
php -S localhost:8000 -t httpdocs
```

---

## 📚 Uso

Acesse `http://localhost:8000` ou domínio configurado e utilize as funcionalidades via menu.

---

## 📂 APIs e Endpoints

- `loginController.php` – Login
- `twoFactorStart.php`, `twoFactorVerify.php` – 2FA
- `registerUser.php` – Cadastro
- `resetPassword.php` – Recuperação
- `handleInscricao.php` – Consulta de inscrição
- `handleDebitos.php` – Débitos e boletos
- `handleAlvaras.php` – Certidões e alvarás
- `generateRegistrationFormPDF.php` – Ficha cadastral
- `apiContact.php` – Contato
- `apiNoticia.php`, `apiNoticiaCarrossel.php` – Notícias

---

## 🔐 Segurança

- Sanitização com `filter_input`, `preg_replace`
- Senhas com `password_hash`
- CAPTCHA, 2FA, e bloqueios por tentativa
- Tokens temporários (senha, 2FA)

---

## 🤝 Contribuição

Contribuições são bem-vindas!

1. Faça um fork
2. Crie um branch (`git checkout -b feature/sua-funcionalidade`)
3. Commit (`git commit -m 'feat: nova funcionalidade'`)
4. Push (`git push origin feature/sua-funcionalidade`)
5. Pull Request

---

## 📄 Licença

Projeto acadêmico/demonstrativo.
