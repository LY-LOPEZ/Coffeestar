# ☕ CoffeStar - Sistema de Gestión para Cafetería

CoffeStar es una aplicación web desarrollada en PHP y MySQL que permite administrar pedidos, controlar estados de atención y gestionar operaciones básicas de una cafetería.

## 🚀 Características

* Gestión de pedidos de clientes.
* Panel de administración.
* Panel para cajero.
* Actualización de estados de pedidos.
* Generación de facturas.
* Seguimiento del estado de los pedidos.
* Interfaz web sencilla y fácil de usar.

## 🛠 Tecnologías Utilizadas

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* XAMPP

## 📋 Requisitos

* XAMPP (Apache y MySQL)
* PHP 8.x o superior
* Navegador web moderno

## ⚙️ Instalación

### 1. Clonar o descargar el proyecto

```bash
git clone https://github.com/LY-LOPEZ/Coffeestar.git
```

### 2. Copiar el proyecto

Mover la carpeta del proyecto a:

```text
C:\xampp\htdocs\
```

### 3. Iniciar servicios

Abrir XAMPP y activar:

* Apache
* MySQL

### 4. Crear la base de datos

Ingresar a:

```text
http://localhost/phpmyadmin
```

Importar el archivo:

```text
database/coffestar.sql
```

### 5. Ejecutar la aplicación

Abrir en el navegador:

```text
http://localhost/coffestar_php_mysql/
```

## 🔐 Credenciales de Acceso

### Administrador

| Campo      | Valor    |
| ---------- | -------- |
| Usuario    | admin    |
| Contraseña | admin123 |

### Cajero

| Campo      | Valor   |
| ---------- | ------- |
| Usuario    | cajero  |
| Contraseña | caja123 |

## 📁 Estructura Principal

```text
coffestar_php_mysql/
│
├── admin/
├── cajero/
├── assets/
│   ├── css/
│   └── js/
├── config/
├── database/
├── index.php
├── login.php
├── logout.php
├── estado.php
├── procesar_pedido.php
└── README.md
```

## 👨‍💻 Autor

Desarrollado como proyecto académico de Economía y Sistemas de Información.

## 📄 Licencia

Este proyecto es de uso educativo y académico.

