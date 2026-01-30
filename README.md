# 🚗 Vehicle Maintenance Tracker

Una aplicación web robusta desarrollada con **Laravel** para la gestión y digitalización de recibos de mantenimiento automotriz. El sistema permite a los usuarios llevar un control exhaustivo de sus vehículos y transformar fotos de recibos físicos en registros digitales organizados.


---


## ✨ Características Principales

* **Gestión Multivehículo:** Registra y administra varios vehículos bajo un mismo perfil de dueño.
* **OCR de Recibos:** Carga fotos de tickets y facturas; la app extrae automáticamente la información clave.
* **Historial de Servicios:** Seguimiento de kilometraje, costos y tipos de mantenimiento por taller.
* **Base de Datos Relacional:** Estructura jerárquica para marcas, modelos y talleres.

---
## 🛠️ Stack Tecnológico

* **Framework:** [Laravel 11](https://laravel.com/)
* **Lenguaje:** PHP 8.x
* **Base de Datos:** SQLite / MySQL (Soporta claves foráneas y eliminaciones en cascada)
* **Frontend:** Blade / Tailwind CSS
* **Procesamiento de Imagen:** Tesseract OCR / Google Vision API (según implementación)

## 📋 Estructura de Datos

El proyecto utiliza las siguientes entidades principales:

* **Marcas y Modelos:** Catálogo organizado para evitar duplicidad de datos.
* **Vehículos:** Vinculados a un usuario y modelo, con seguimiento de matrícula y km.
* **Talleres:** Directorio de lugares donde se realizan los servicios.
* **Recibos:** El núcleo del sistema, donde se almacenan fechas, precios y observaciones.

---

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
    git clone https://github.com/Hferpi/car-history-php.git
   cd car_history_php
    ```

2. **Instalar Dependencias**

    ```bash
    # Instalamos dependencias principales
    composer install
    npm install

    
    ```


    ```bash
    # Crear el .env preparado para sqlite

    cp .env.example .env

    ```

    ```bash
    # Generar claves app
    php artisan key:generate


    ```



3. **Importar tablas de init_sqlite.sql**

    ```bash
    # Instalamos sqlite

    apt install sqlite3
 
    ```
   
     ```bash

    # Instalamos tablas insertar marcas y modelos de vehiculos. 
    npm run setup
 
    ```

    ```bash
    # Instruccion para guargar los recibos.

    php artisan storage:link
    ```



## 🌟 Funcionalidades Principales

* **Perfil de Usuario:** Sistema de autenticación para que cada dueño gestione su propia flota de vehículos.
* **Gestión de Vehículos:** Registro detallado incluyendo matrícula, marca, modelo y kilometraje actual.
* **Escaneo Inteligente (OCR):** El núcleo de la app. Permite subir una fotografía del recibo del taller; el sistema extrae automáticamente:
    * Nombre del Taller.
    * Fecha de la intervención.
    * Importe total (Precio).
    * Descripción de los servicios realizados.
* **Histórico de Mantenimiento:** Consulta rápida de todas las reparaciones y servicios realizados a un vehículo específico, facilitando el control de gastos y el mantenimiento preventivo.

---

## 🏗️ Arquitectura de la Aplicación

La aplicación sigue el patrón **Modelo-Vista-Controlador (MVC)** de Laravel, asegurando una separación clara entre la lógica de negocio y la interfaz de usuario.



### Relaciones del Sistema:
1.  **Usuarios y Vehículos:** Un usuario puede poseer múltiples vehículos (Relación 1:N).
2.  **Marcas y Modelos:** Estructura jerárquica para evitar errores de escritura y normalizar los datos de la flota.
3.  **Vehículos y Recibos:** Cada recibo está vinculado a un vehículo específico para mantener el historial clínico del coche.
4.  **Talleres y Recibos:** Los recibos se asocian a talleres para identificar dónde se realizó cada servicio.

---

## 📸 Flujo de Trabajo del OCR

1.  **Captura:** El usuario toma una foto del ticket físico desde su móvil o sube un archivo desde su ordenador.
2.  **Procesamiento:** La imagen es enviada al motor de OCR integrado en el backend de Laravel.
3.  **Validación:** El sistema muestra los datos detectados en un formulario pre-rellenado para que el usuario confirme o corrija la información.
4.  **Almacenamiento:** Una vez confirmado, el recibo se guarda y el kilometraje del vehículo se actualiza automáticamente.

---

## 🛠️ Requisitos del Sistema

* **PHP:** >= 8.2
* **Composer:** Gestor de dependencias de PHP.
* **Node.js & NPM:** Para compilar los assets del frontend.
* **Extensión GD o Imagick:** Necesaria para el pre-procesamiento de imágenes antes del OCR.

---

## 🤝 Contribución

Si deseas añadir nuevas funcionalidades (como gráficas de gastos anuales o recordatorios de ITV/Seguro):

1.  Haz un **Fork** del proyecto.
2.  Crea una rama con tu nueva función: `git checkout -b feature/NuevaMejora`.
3.  Realiza un **Commit** con tus cambios: `git commit -m 'Añadir nueva funcionalidad'`.
4.  Sube la rama: `git push origin feature/NuevaMejora`.
5.  Abre un **Pull Request**.

---

## 👥 Autores

Este proyecto ha sido desarrollado por:

* **Hector Fernandez** - [@Hferpi](https://github.com/Hferpi)
* **Alex Rojas** - [@Alex9902](https://github.com/Alex9902)

---

## 📄 Licencia

Este proyecto se distribuye bajo una licencia que permite el uso, estudio, modificación y redistribución del software, siempre que se cumpla la siguiente condición:

* **Reconocimiento:** Se debe otorgar el crédito apropiado, proporcionar un enlace a la licencia e indicar si se realizaron cambios. Debes mencionar explícitamente a los autores originales (**Hector Fernandez** y **Alex Rojas**) en cualquier copia o trabajo derivado.
