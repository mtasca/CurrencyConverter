## Endpoints

| Name                               | Method | URI                           |
| :---                               | :---   | :---                          |
| [Convert](#POST-convert)           | `POST` | /api/v1/currency/convert      |
| [Convert Bulk](#POST-convert-bulk) | `POST` | /api/v1/currency/convert_bulk |

## <a name="POST-convert"></a>Convert
Convert

### Request

#### Example

```http
GET /api/v1/currency/convert
```

### Response

#### Success response

```http
HTTP/1.1 200 Ok
Content-type: application/json
{
    "from": "USD",
    "amount": "12.56", 
    "to": "EUR"
}
```

```json
{
    "metadata": {
        "code": 200,
        "message": "OK"
    },
    "data": {
        "currency": {
            "name": "Euro",
            "description": "Euro",
            "iso_code": "EUR",
            "iso_number": 978
        },
        "amount": 25.12
    }
}
```

### Failed Response

#### Bad request (400)
- I don't send some of the required parameters in the body request

```http
HTTP/1.1 400 Bad Request
Content-type: application/json
```

```json
{
    "metadata": {
        "code": 400,
        "message": "Bad Request"
    },
    "data": {
        "message": "Invalid body request, please check the docs"
    }
}
```

## <a name="POST-convert-bulk"></a>Convert
Convert In Bulk

### Request

#### Example

```http
GET /api/v1/currency/convert_bulk
```

### Response

#### Success response

```http
HTTP/1.1 200 Ok
Content-type: application/json
{
    "from": "USD",
    "amount": "12.56", 
    "to": ["EUR", "ARS", "GBP"]
}
```

```json
{
    "metadata": {
        "code": 200,
        "message": "OK"
    },
    "data": [
        {
            "currency": {
                "name": "Euro",
                "description": "Euro",
                "iso_code": "EUR",
                "iso_number": 978
            },
            "amount": 7.03
        },
        {
            "currency": {
                "name": "Argentine peso",
                "description": "Argentine peso",
                "iso_code": "ARS",
                "iso_number": 32
            },
            "amount": 0.45
        },
        {
            "currency": {
                "name": "Pound sterling",
                "description": "Pound sterling",
                "iso_code": "GBP",
                "iso_number": 826
            },
            "amount": 20.02
        }
    ]
}
```

### Failed Response

#### Bad request (400)
- I don't send some of the required parameters in the body request

```http
HTTP/1.1 400 Bad Request
Content-type: application/json
```

```json
{
    "metadata": {
        "code": 400,
        "message": "Bad Request"
    },
    "data": {
        "message": "Invalid body request, please check the docs"
    }
}
```