# Barracuda Project

## Installation

PHP: Version 7.4.3

Package Manager: [Composer](https://getcomposer.org/)

Run command in root project directory
```
php composer.phar install
```
---
## Create Package Endpoint

### Request
```
curl --location --request POST 'http://localhost:8000/create' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'origin=Holland' \
--data-urlencode 'destination=Grand Rapids' \
--data-urlencode 'weight=15'
```

### Example Response
```
{
    "data": {
        "origin": "Holland",
        "destination": "Grand Rapids",
        "weight": "15",
        "id": 1
    }
}
```

---

## Check Package Progress Endpoint

### Request
```
curl --location --request GET 'http://localhost:8000/check_progress?id=<package_id>'
```

### Example Response

```
{
    "data": {
        "package": {
            "id": 1,
            "origin": "Holland",
            "delivered": "0",
            "destination": "Grand Rapids",
            "weight": "15.0"
        },
        "tracking": [
            {
                "location": "Holland",
                "status": "arrived",
                "created_at": "2021-04-30T02:28:04.000000Z",
                "updated_at": "2021-04-30T02:28:04.000000Z"
            }
        ]
    }
}
```

---

## Update Package Endpoint

### Request
```
curl --location --request PUT 'http://localhost:8000/update' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'id=<package_id>' \
--data-urlencode 'location=Grand Rapids' \
--data-urlencode 'status=arrived'
```

### Example Response

```
{
    "data": {
        "location": "Grand Rapids",
        "status": "arrived",
        "created_at": "2021-04-30T01:53:35.000000Z",
        "updated_at": "2021-04-30T01:53:46.000000Z"
    }
}
```

---

## Mark Delivered Endpoint

### Request
```
curl --location --request POST 'http://localhost:8000/mark_delivered' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'id=1'
```

### Example Response

```
{
    "data": {
        "id": 1,
        "origin": "Holland",
        "delivered": true,
        "destination": "Grand Rapids",
        "weight": "15.0"
    }
}
```

## Tests

To run tests run the following command in the project root directory.
```
./vendor/bin/phpunit
```
