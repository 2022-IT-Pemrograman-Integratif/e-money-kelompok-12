 # API Peacepay
Anggota Kelompok:
- Hafizh Abid Wibowo - 502701011
- Alvian Ghifari - 5027201035
- Axellino Anggoro A - 5027201040

## Register

### Method
`POST`

### Endpoint
`api/register`

### Auth
Tidak menggunakan autentikasi

### Parameter
`{number, name, password}`

### Postman Output
![messageImage_1650345611597](https://user-images.githubusercontent.com/83297238/163925372-d9057f81-6b41-4dc7-9a31-c82739af40b4.jpeg)

<br>

## Login

### Method
`POST`

### Endpoint
`/api/login`

### Auth
Tidak menggunakan Autentikasi

### Parameter
`{number, password}`

### Postman Output
![messageImage_1650345909339](https://user-images.githubusercontent.com/83297238/163925895-b4884839-6f26-44c0-8b60-e2880770cf22.jpg)

<br>

## Top Up

### Method
`POST`

### Endpoint
`/api/topup`

### Auth
Menggunakan Autentikasi Admin

### Parameter
`{number, amount}`

### Postman Output
![messageImage_1650345698958](https://user-images.githubusercontent.com/83297238/163925579-206aae7a-3e0f-4604-ad47-c5dce389abfb.jpeg)

<br>

## Transfer

### Method
`POST`

### Endpoint
`/api/transfer`

### Auth
Menggunakan autentikasi users

### Parameter
`{tujuan, amount}`

### Postman Output
![messageImage_1650345761853](https://user-images.githubusercontent.com/83297238/163925746-f2e94116-aae1-43cc-b888-a9193774458d.jpeg)

<br>

## Users

### Method
`GET`

### Endpoint
`/api/users`
`/api/users/$id`

### Auth
Menggunakan Autentikasi Admin

### Parameter
Tidak menggunakan parameter

### Postman Output 
![messageImage_1650345661624](https://user-images.githubusercontent.com/83297238/163925481-b7942eb8-dec2-4a92-bf0e-8503638dea6e.jpeg)