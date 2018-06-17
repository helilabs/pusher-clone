# Pusher Clone

### Introduction
This is a real-time server built with php and [ReactPHP](https://reactphp.org/) to mirror some pusher features.

This project was made to show some of my skills as a **Senior Software Engineer** please don't use it in your production project.

## Features
1. generates App ID and app secret tokens for security.
2. has apis to push data to channels and authorize accessibility to private channels
3. uses ZeroMQ to communicate between apis and real-time server. (scalability support)

## Usage
0. make sure you have `ZeroMQ` php extension installed.
1. `app_id` and `secret` can be generated via command `php artisan make:token`

2. start wamp server via `php artisan wamp:server:start`.

2. Use [autobahn](https://github.com/crossbario/autobahn-js) or *any websocket library* to connect to server.
### connection example via autobahn
```js
let connection = new autobahn.Connection({
    url: 'ws://127.0.0.1:7474',
    realm: 'default',
    onchallenge: (session, method, extra) => {
        if (method === 'token') {
            return '{app_id}';
        }
    },
    authmethods: ['token']
})

connection.onopen = (session) => {
    console.log('connected');
}

connection.open();
```

4. always include `app_id` and `secret` in your http request to push apis endpoint as follows
```php
//using Adam Wathan zttp library https://github.com/kitetail/zttp
$response = Zttp::withHeaders([
    'authorization' => 'Bearer {secret}',
    'X-APP-ID' => '{app_id}'
])->post($url, $data);
```
5. only use your server to send requests to real-time server because your server is trusted but your front-end is not.

### Available APIs
1. Push request: to send a broadcast for a channel
```php
$response = Zttp::withHeaders([
    'authorization' => 'Bearer {secret}',
    'X-APP-ID' => '{app_id}'
])->post('{base_url}/api/push', [
    'channel' => 'channel_name',
    'data' => ['key' => 'value']
]);
```
2. Authorize request: to authorize accessability to private channel
```php
$response = Zttp::withHeaders([
    'authorization' => 'Bearer {secret}',
    'X-APP-ID' => '{app_id}'
])->post('{base_url}/api/push/auth', [
    'channel' => 'channel_name',
    'session_id' => '123456'
]);
```

## TODO
This is the basic logic needed for running your own pusher-like real-time server (provided with security features) but I'm planning to implement more feature for fun

1. [ ] make this project work with **Laravel** Echo.
1. [ ] Implement Users registrations, plans system and billing.
2. [ ] Dashboards for admin and every registered user.
3. [ ] generates reports for requests and connected users and available channels.
4. [ ] rewrite the whole server with NodeJS to support better performance.

## Contributes
1. [Mohammed Manssour](https://mohammedmanssour.me).