
# 📘 Portal Consulta Empreendimentos

Aplicação web em PHP que permite a cidadãos, empresas e contadores consultar, regularizar e acompanhar dados empresariais junto ao município. O sistema reúne autenticação segura, consultas fiscais, geração de boletos e recursos de acessibilidade.

## 🏗 Arquitetura do Projeto

```
portal-empreendimentos/
├── backend/
│   ├── php/
│   │   ├── api/            # Endpoints públicos (contato, notícias)
│   │   ├── config/         # Credenciais de banco e e-mail
│   │   ├── public/         # Lógica principal de autenticação e consultas
│   │   └── services/       # Funções auxiliares (ex.: geração de boletos)
│   ├── composer.json       # Dependências (PHPMailer, Dompdf)
│   └── vendor/             # Bibliotecas instaladas via Composer
└── httpdocs/
    ├── *.php               # Páginas da interface web
    ├── api/                # Proxies para os endpoints do backend
    ├── assets/             # Imagens, uploads, ícones
    ├── css/                # Estilos (Bootstrap + estilos próprios)
    ├── includes/           # Cabeçalho, rodapé e modais
    └── scripts/            # JS modular (login, cadastro, utilitários)
```

## ✨ Funcionalidades

### 🔐 Autenticação e Segurança
- Validação completa dos campos de login, bloqueio por tentativas e verificação de CAPTCHA
- Autenticação em duas etapas (2FA) via e-mail com código temporário
- Cadastro de usuário com validação de CPF, endereço e política de senha forte
- Fluxos de recuperação e troca de senha, além de bloqueio de sessão por inatividade

### 🏢 Consultas Empresariais
- Busca de inscrição municipal por IM, CNPJ ou razão social
- Consulta de débitos e emissão de boleto bancário Bradesco em PDF
- Geração de ficha cadastral detalhada em PDF com dados empresariais e CNAEs
- Consultas e emissão de alvarás e licenças (via endpoints dedicados)

### 📰 Conteúdo e Comunicação
- Notícias com carrossel e páginas detalhadas
- Formulário de contato que envia e-mail ao suporte

### ♿ Acessibilidade
- Menu com ajuste de fonte, alto contraste e modo preto & branco
- Integração com VLibras para tradução automática em Libras

## ⚙️ Requisitos

- PHP ≥ 8.0
- MySQL/MariaDB (banco `portalemp`)
- Composer
- Extensões PHP: `mysqli`, `openssl`, `mbstring`, `curl`, `intl`, etc.

### Bibliotecas principais:
- `phpmailer/phpmailer`
- `dompdf/dompdf`

## 🚀 Instalação

```bash
# Clonar repositório
git clone https://github.com/usuario/portal-empreendimentos.git
cd portal-empreendimentos/backend

# Instalar dependências PHP
composer install
```

### Banco de Dados
- Crie o banco `portalemp` e as tabelas necessárias.
- Configure credenciais em `backend/php/config/database.php`.

### E-mail (2FA, contato)
- Ajuste parâmetros SMTP em `backend/php/config/email.php`.

### Permissões
- Garanta permissão de escrita em `httpdocs/assets/uploads/` para upload de fotos.

### Executar

```bash
cd ..
php -S localhost:8000 -t httpdocs
```

Acesse [http://localhost:8000](http://localhost:8000) no navegador.

## 📂 Endpoints e Páginas

| Rota / Script                        | Descrição                                      |
|-------------------------------------|------------------------------------------------|
| `loginController.php`              | Login com CAPTCHA e bloqueio                   |
| `twoFactorStart.php` / `twoFactorVerify.php` | Fluxo de 2FA via e-mail               |
| `registerUser.php`                 | Cadastro de usuário                            |
| `handleInscricao.php`             | Consulta de inscrições municipais              |
| `handleDebitos.php`               | Consulta de débitos e emissão de boletos       |
| `generateRegistrationFormPDF.php` | Geração de ficha cadastral em PDF              |
| `apiContact.php`, `apiNoticias*.php` | APIs de contato e notícias                  |
| `handleAlvaras.php`, `handleLicencas.php` | Consulta de alvarás e licenças        |

## 🔐 Boas Práticas de Segurança

- Sanitização de entradas e consultas parametrizadas
- Hash de senhas (`password_hash`)
- Tokens temporários para 2FA e redefinição de senha
- CAPTCHA e bloqueio após tentativas inválidas
- Controle de sessões e verificação de inatividade

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie sua branch de feature: `git checkout -b feature/minha-ideia`
3. Commit: `git commit -m 'feat: minha nova ideia'`
4. Push: `git push origin feature/minha-ideia`
5. Abra um Pull Request

## 📄 Licença

Projeto acadêmico/demonstrativo
