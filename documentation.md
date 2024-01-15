# Descripción del documento

A continuación se presenta la documentación general para el acceso a los datos de la cámara.

Este documento es parte del proyecto de investigación y desarrollo de la FACITEC con el apoyo del shopping Mapy.

## Method POST 

- URLBASE: https://appmapy.facitec.edu.py/api/detections
- Endpoit: /detectionsByDates

## Parámetros

Objeto de tipo JSON. Ejemplo:

```
{"desde": "2023-01-01", "hasta": "2023-02-01"}
```

## Headers

Necesita un header X-Auth-Token que contenga una cadena sha256

## Ejemplos de uso

### CURL


```
curl --request POST \
  --url https://appmapy.facitec.edu.py/api/detections/detectionsByDates \
  --header 'Content-Type: application/json' \
  --header 'X-Auth-Token: {{aquí debe ir su token}}' \
  --data '{
	"desde": "2024-01-01",
	"hasta": "2024-01-12"
}'
```


### JavaScript

```
const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-Auth-Token': '{{aquí debe ir su token}}'
  },
  body: '{"desde":"2024-01-01","hasta":"2024-01-12"}'
};

fetch('https://appmapy.facitec.edu.py/api/detections/detectionsByDates', options)
  .then(response => response.json())
  .then(response => console.log(response))
  .catch(err => console.error(err));
```
