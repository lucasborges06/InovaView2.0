# InovaView — Plataforma criativa para exibir e compartilhar projetos visuais

## Resumo
InovaView é um projeto acadêmico desenvolvido em PHP (compatível com PHP 7.4+) para demonstrar
uma plataforma completa de exposição de trabalhos visuais. Este repositório inclui uma
landing page vibrante, sistema de upload, galeria com paginação e visualização em modal,
painel administrativo com autenticação segura, proteção CSRF, e integração de envio de
contatos por **e‑mail** e **WhatsApp** (via link wa.me).

## Funcionalidades implementadas
- Visual "loud": cores fortes, tipografia moderna e responsiva.
- Upload de imagens com validação (tamanho e tipo).
- Galeria com paginação e preview em modal.
- Painel admin com senha criptografada (bcrypt) e sessão segura.
- Tokens CSRF para formulários sensíveis (login, upload, contato).
- Envio de contato por `mail()` (quando disponível) e encaminhamento para WhatsApp.
- README formal em PT‑BR e estrutura pronta para apresentação acadêmica.

## Como executar
1. Extraia para o diretório público do servidor (ex: `www` ou `public_html`).
2. Dê permissão de escrita para `uploads/` e `data/` (`chmod 755/775` conforme necessário).
3. Abra `index.php` no navegador. O banco SQLite (`data/inovaview.db`) será criado automaticamente.
4. Credenciais admin iniciais:
   - Usuário: `admin`
   - Senha: `admin123`
   > A senha é armazenada de forma segura (hash). **Altere em produção.**
5. Para o envio por WhatsApp, o número padrão configurado é: +5531998101841

## Arquivos principais
- `index.php` — Landing / contato.
- `upload.php` — Formulário de upload.
- `gallery.php` — Galeria com paginação e modal.
- `admin.php` — Painel administrativo (login / lista de uploads).
- `config.php` — Configurações (contato, admin hash, etc).
- `db_init.php` — Inicializa banco SQLite.
- `assets/` — CSS e JS.
- `uploads/` — Imagens enviadas.
- `data/` — Banco SQLite.

## Observações de segurança e entrega
- Em ambiente de produção, configure SMTP para envio de e‑mails (o `mail()` pode não funcionar em localhost).
- Troque a senha do admin e restrinja o acesso ao painel.
- Faça validações adicionais conforme requisitos da sua disciplina.

Boa apresentação! Personalize textos e cores se desejar.
