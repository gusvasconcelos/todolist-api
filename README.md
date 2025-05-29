# Todo List API

API para gerenciamento de tarefas, busca de notícias e autenticação, com internacionalização e tratamento de erros.

## 🚀 Funcionalidades

- **Autenticação JWT** - Autenticação segura baseada em tokens JWT para proteger suas rotas e recursos
- **CRUD de Tasks** - Sistema completo de gerenciamento de tarefas com filtros e paginação
- **Integração com News API** - Busca e listagem de artigos de notícias com múltiplos filtros
- **Internacionalização** - Suporte a múltiplos idiomas (inglês e português do Brasil)
- **Tratamento de erros global** - Exceções personalizadas para melhorar a experiência do usuário
- **Code Style** - Laravel Pint usando PSR-12 para formatação de código
- **Testes E2E** - Testes de ponta a ponta para garantir a qualidade da API

## 📚 Documentação da API

### 🔐 Autenticação

Todas as rotas da API (exceto login) requerem autenticação via JWT token.

#### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "access_token": "token",
  "token_type": "bearer",
  "expires_in": 3600
}
```


#### Outras rotas de autenticação:
- `POST /api/v1/auth/logout` - Logout do usuário
- `POST /api/v1/auth/refresh` - Renovar token
- `GET /api/v1/auth/me` - Dados do usuário autenticado

### 📝 CRUD de Tasks

#### Listar Tasks (com paginação e filtros)
```http
GET /api/v1/tasks?status=pending&page=1&per_page=10
```

#### Listar Todas as Tasks (sem paginação)
```http
GET /api/v1/tasks/all
```

#### Visualizar Task Específica
```http
GET /api/v1/tasks/1
```

#### Criar uma Task
```http
POST /api/v1/tasks
Content-Type: application/json

{
  "title": "Task 1",
  "description": "Description 1",
  "status": "pending"
}
```

#### Atualizar uma Task
```http
PUT /api/v1/tasks/1
Content-Type: application/json

{
  "title": "Task 1",
  "description": "Description 1",
  "status": "pending"
}
```

#### Deletar uma Task
```http
DELETE /api/v1/tasks/1
```


### 📰 Integração com News API

A integração com a News API foi desenvolvida utilizando diversos design patterns para garantir flexibilidade, manutenibilidade e testabilidade.

#### Design Patterns Implementados

**1. Client Pattern**
- `NewsClient` (classe abstrata): Define a estrutura base para clientes da News API
- `NewsEverythingClient`: Implementação específica para o endpoint `/everything`
- Encapsula a configuração HTTP e autenticação via API key

**2. Builder Pattern**
- `ArticleRequest`: Constrói requests de forma fluente e validada
- Permite configuração step-by-step dos parâmetros de busca
- Valida parâmetros (idiomas disponíveis, ordenação, paginação)

**3. Response Object Pattern**
- `ArticleResponse`: Encapsula e estrutura a resposta da API externa
- `ResponseAbstract`: Classe base para padronizar respostas de APIs
- Fornece métodos para verificar sucesso/erro e extrair dados

**4. Service Layer Pattern**
- `NewsService`: Orquestra a lógica de negócio da integração
- Transforma dados externos em formato compatível com a aplicação
- Gerencia exceções e converte para formato Laravel Paginator

#### Buscar Artigos de Notícias
```http
GET /api/v1/news/articles?q=bitcoin&language=pt&sortBy=publishedAt
```


## 🛠️ Instalação e Configuração

### Pré-requisitos
- PHP 8.2+
- Composer
- PostgreSQL ou MySQL
- Conta na [News API](https://newsapi.org/)

### Passos de instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/gusvasconcelos/laravel-api.git
   cd laravel-api
   ```

2. **Instale as dependências:**
   ```bash
   composer install
   ```

3. **Configure o ambiente:**
   ```bash
   cp .env.example .env
   ```

4. **Configure as variáveis de ambiente no `.env`:**
   ```env
   # Banco de dados
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_api
   DB_USERNAME=root
   DB_PASSWORD=

   # JWT
   JWT_SECRET=your-jwt-secret-key

   # News API
   NEWS_API_KEY=your-news-api-key
   NEWS_API_URL=https://newsapi.org/v2
   ```

5. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

6. **Execute as migrations:**
   ```bash
   php artisan migrate
   ```

7. **Gere o token JWT:**
   ```bash
   php artisan jwt:generate
   ```

8. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```

## 🧪 Testes

Execute os testes com:
```bash
php artisan test
```

## 📁 Estrutura do Projeto
```
└── app
    ├── Enums # Enumerações
    │   └── TaskStatusEnum.php
    ├── Exceptions # Exceções customizadas
        ├── HttpException.php
        ├── NotFoundException.php
        ├── UnauthorizedException.php
        └── UnprocessableEntityException.php
    ├── Helpers # Funções auxiliares
        └── Response
            └── ErrorResponse.php
    ├── Http # Controllers, Requests, Resources
    ├── Models
    ├── Packages # Bibliotecas dentro da aplicação
        └── Api # Integrações com APIs externas
        │   ├── News # Integração com a News API
        │       ├── Everything
        │       │   ├── Request
        │       │   │   └── ArticleRequest.php # Request para buscar artigos
        │       │   └── Response
        │       │   │   └── ArticleResponse.php # Response para artigos
        │       ├── NewsClient.php # Cliente para a API News
        │       └── NewsEverythingClient.php # Cliente para o endpoint /everything
        │   └── ResponseAbstract.php # Classe base para respostas de APIs
    ├── Providers # Serviços e configurações
    ├── Services # Lógica de negócio
    ├── Traits # Traits para reutilização de código
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request
