# API-RESTful-multi-gateway-para-processamento-de-pagamentos


## 🛠️ Tecnologias Utilizadas

* **PHP 8.2+** & **Laravel 11**
* **MySQL 8.0**
* **Docker & Docker Compose**
* **Mocks de Gateway:** Ambientes simulados para testes de integração (Portas 3001 e 3002).

---

## 📦 Como Instalar e Rodar

### 1. Clonar o repositório
```bash
git clone https://github.com/Matheus-Pessoa06/API-RESTful-multi-gateway-para-processamento-de-pagamentos
```
```bash
cd api-pagamentos
```

### 2. Criar o arquivo de configuração
```bash
cp .env.example .env
```

### 3. Subir os Containers
```bash
docker-compose up -d
```

### 4. Instalar as Dependências
```bash
docker-compose exec app composer install
```

### 5. Preparar o Banco de Dados
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
```

### 6. Como Testar a Compra
Método: POST
URL: http://localhost:8080/api/checkout

### Headers:
Content-Type: application/json

Accept: application/json

Exemplo de Payload (Body JSON):
```json
JSON
{
    "product_id": 1,
    "quantity": 2,
    "name": "Matheus Pessoa",
    "email": "matheus@email.com",
    "cardNumber": "5569000000006063",
    "cvv": "010"
}
```


### Como testar a Resiliência (Failover)
Para validar que o sistema chama o segundo gateway caso o primeiro falhe:

Acesse o arquivo app/Infraestructure/Gateway/Payments/Adapters/GatewayAdapterOne.php.

Altere a URL do endpoint para um endereço inválido (ex: mude a porta).

Envie a requisição pelo Postman/Insomnia.

O sistema retornará sucesso, mas você poderá verificar no banco de dados (gateway_id) que a transação foi processada pelo Gateway 2.



