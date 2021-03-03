![logo api](logo.png)

# Hunter API

REST API do Hunter

***Fluxograma***

![Fluxograma](FluxogramaAPIHunter.png)

# Uso

***REQUEST***

HEADER

```X-KEY-MACHINE:{machine}```

URL

```{url_base}/{object}/{function}/{client.slug}```

***POST***

Qualquer requisição com envio variáveis devem ser realizadas via metodo POST

## ****Em javascript****

Com uso da biblioteca [axios](https://www.npmjs.com/package/axios)

### exemplo:

```javascript
axios.post(URL, vars, {
    headers: {
        'HUNTER-CHAVE-MACHINE': machine
    }
})
.then((response) => {
    // .. TODO ..
})
.catch((error) => {
    // .. TODO ..
})
```
Enviando dados usando ```fetch()``` com método POST

```javascript
  const vars = {
    "ola":"mundo"
  }

  var headers = new Headers({
    'HUNTER-CHAVE-MACHINE': 'machine_key'
  });

  var myInit = { 
    method: 'POST',
    headers: headers,
    body:JSON.stringify(vars)
  };

  fetch('https://myapi.com/examplePrivate/get/CLIENT_SLUG', myInit)
  .then(response => response.json())
  .then( obj => console.log(obj))
```

## ****Em PHP****
Com uso da biblioteca cURL

### exemplo:

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt( 
    $ch, 
    CURLOPT_HTTPHEADER,
    ['HUNTER-CHAVE-MACHINE:'.$machine]
);

$response = curl_exec($ch);
curl_close ($ch);
```

# Requisições Parametrizadas

A API funciona como uma espécie de ORM. Todos os dados que a aplicação precisar devem ser parametrizados.

*Exemplo em PHP:*

```php
define('URL', 'http://api.com.br/user/get/client_slug');
$vars = [
    'where' => [
        'id', '=', 1
    ]
];
curl_setopt($ch, CURLOPT_URL, URL);
curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
```

*Exemplo em JS:*

```javascript
const URL = 'http://api.com.br/user/get/client_slug';
let vars = {
    where:[
        'id', '=', 1
    ]
};
axios.post(URL, vars, headers);
```

