# desafio-didatikos
# Desafio Full Stack

Este é um projeto desenvolvido como parte do desafio Full Stack, envolvendo a criação de uma API REST para gerenciamento de produtos, cidades e marcas no backend, utilizando Laravel e MySQL, e um frontend em Vue.js.

## Backend (Laravel)

### Estrutura de Banco de Dados:

- Tabelas: `produtos`, `cidades`, `marcas`
- Relacionamentos: Produto pertence a uma marca e a uma cidade.

### API Endpoints:

- `/api/produtos` (GET, POST)
- `/api/produtos/{id}` (GET, PUT, DELETE)
- `/api/cidades` (GET)
- `/api/marcas` (GET)

### Validações:

- Campos obrigatórios no cadastro de produto (Nome, Valor, Cidade).
- Restrição de unicidade para o nome do produto.

### Filtro Dinâmico:

- Implementar filtros para média dos valores dos produtos, soma de todos os produtos, filtrar por valores entre x e y, e produtos por cidade.

### Regras de Negócio:

- Não permitir a exclusão de produtos com estoque.

### Técnicas de Clean Code e SOLID:

- Seguir as melhores práticas de desenvolvimento, utilizando princípios SOLID e mantendo o código limpo e organizado.

### Testes Unitários:

- Implementar testes unitários para garantir a qualidade do código.

## Frontend (Vue.js)

### Estrutura de Componentes:

- Componentes para listagem de produtos, cadastro, edição, e listagem de cidades.
- Tela de login.

### Integração com API:

- Utilizar o Vue Resource ou Axios para fazer chamadas à API Laravel.

### Filtro Dinâmico:

- Implementar a lógica para filtrar produtos conforme as especificações do desafio.

### Validações:

- Validar campos obrigatórios no cadastro de produto e aplicar restrições de unicidade.

### Tela de Login:

- Implementar uma tela de login para autenticação.

## Instruções Gerais:

1. **Configuração do Ambiente:**
   - Certifique-se de ter o ambiente Laravel configurado corretamente.
   - Instale as dependências do projeto com `composer install`.

2. **Configuração do Banco de Dados:**
   - Configure o arquivo `.env` com as informações do seu banco de dados.
   - Execute as migrations com `php artisan migrate`.

3. **Configuração do Frontend:**
   - Certifique-se de ter o Node.js e o npm instalados.
   - Instale as dependências do projeto com `npm install`.

4. **Execução:**
   - Inicie o servidor Laravel com `php artisan serve`.
   - Inicie o servidor Vue.js com `npm run serve`.


