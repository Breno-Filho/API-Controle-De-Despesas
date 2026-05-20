# API de Controle de Despesas

API RESTful para gerenciamento de finanças pessoais, construída com **Laravel 11** e **PHP 8.2+**. Permite registrar receitas e despesas, categorizá-las e gerar resumos financeiros.

## Funcionalidades

- Autenticação via Laravel Sanctum (token-based)
- CRUD completo de Categorias (income/expense)
- CRUD completo de Transações (receitas e despesas)
- Filtros por tipo e período
- Relatório financeiro (total receitas, total despesas, saldo)
- Cada usuário acessa apenas seus próprios dados

## Tecnologias

- PHP 8.2+
- Laravel 11
- Laravel Sanctum
- SQLite (padrão) / MySQL / PostgreSQL

## Instalação

```bash
# Clonar o repositório
git clone https://github.com/seu-usuario/api-controle-despesas.git
cd api-controle-despesas

# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Criar banco SQLite
touch database/database.sqlite

# Rodar migrations
php artisan migrate

# Iniciar servidor
php artisan serve
```

A API estará disponível em `http://localhost:8000/api`

## Endpoints

### Autenticação

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/register` | Registro de novo usuário |
| POST | `/api/login` | Login (retorna token) |
| POST | `/api/logout` | Logout (revoga token) |
| GET | `/api/me` | Retorna dados do usuário autenticado |

### Categorias (requer autenticação)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/categories` | Listar categorias do usuário |
| POST | `/api/categories` | Criar nova categoria |
| GET | `/api/categories/{id}` | Ver categoria específica |
| PUT | `/api/categories/{id}` | Atualizar categoria |
| DELETE | `/api/categories/{id}` | Deletar categoria |

### Transações (requer autenticação)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/transactions` | Listar transações |
| POST | `/api/transactions` | Criar nova transação |
| GET | `/api/transactions/{id}` | Ver transação específica |
| PUT | `/api/transactions/{id}` | Atualizar transação |
| DELETE | `/api/transactions/{id}` | Deletar transação |

### Relatórios (requer autenticação)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/reports/summary` | Resumo financeiro |

## Filtros

As transações podem ser filtradas pelos seguintes parâmetros via query string:

| Parâmetro | Descrição | Exemplo |
|-----------|-----------|---------|
| `type` | Filtrar por tipo | `?type=income` ou `?type=expense` |
| `start_date` | Data inicial do período | `?start_date=2026-01-01` |
| `end_date` | Data final do período | `?end_date=2026-12-31` |

**Exemplo combinado:**
```
GET /api/transactions?type=expense&start_date=2026-01-01&end_date=2026-06-30
```

## Exemplos de Uso

### Registro

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@email.com",
    "password": "12345678",
    "password_confirmation": "12345678"
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@email.com",
    "password": "12345678"
  }'
```

### Criar Categoria

```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token}" \
  -d '{
    "name": "Alimentação",
    "type": "expense"
  }'
```

### Criar Transação

```bash
curl -X POST http://localhost:8000/api/transactions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token}" \
  -d '{
    "description": "Almoço restaurante",
    "amount": 45.90,
    "type": "expense",
    "date": "2026-05-20",
    "category_id": 1
  }'
```

### Relatório Financeiro

```bash
curl -X GET "http://localhost:8000/api/reports/summary?start_date=2026-01-01&end_date=2026-12-31" \
  -H "Authorization: Bearer {seu_token}"
```

**Resposta:**
```json
{
  "total_income": 5000.00,
  "total_expense": 2350.75,
  "balance": 2649.25
}
```

## Estrutura do Projeto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── TransactionController.php
│   │   │   └── ReportController.php
│   │   └── Requests/
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       ├── StoreCategoryRequest.php
│   │       ├── UpdateCategoryRequest.php
│   │       ├── StoreTransactionRequest.php
│   │       └── UpdateTransactionRequest.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       └── Transaction.php
├── config/
├── database/migrations/
├── routes/api.php
└── composer.json
```

## Modelos e Relacionamentos

- **User** → hasMany(Category), hasMany(Transaction)
- **Category** → belongsTo(User), hasMany(Transaction)
- **Transaction** → belongsTo(User), belongsTo(Category)

## Licença

MIT
