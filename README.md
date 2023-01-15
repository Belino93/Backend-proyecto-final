<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Proyecto Laravel-PHP

Proyecto final del curso de GeeksHubs academy.

En este caso la tematica era libre, y he elegido crear Fixapp una plataforma que permite gestionar reparaciones de dispositivos moviles.
En este repositorio se encuentra la parte backend del proyecto. 
Repositorio Frontend React: https://github.com/Belino93/frontend-proyecto-final

La relación entre tablas seria la siguiente:
![DDBB](https://user-images.githubusercontent.com/90568424/212538376-7cf588ee-ee30-4efd-af88-e0ffa8d99c7f.PNG)



## Uso de la API

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/23873290-6f0ff7c6-2d8e-48da-9840-5a15bc4417d2?action=collection%2Ffork&collection-url=entityId%3D23873290-6f0ff7c6-2d8e-48da-9840-5a15bc4417d2%26entityType%3Dcollection%26workspaceId%3D0036013c-adfd-42cc-995e-7fbd3c9599ba)


## Tecnologías utilizadas en el proyecto:
- **Laravel**
- **PHP**

### Librerias extra
    - tymon/jwt-auth : Gestiona la autorización con JWT
    
### Explicación de la estructura del proyecto
Partimos con la estructura básica de laravel.

- **Controllers**
    - AuthController.php : Controlador creado para los endpoints de autenticación.
        - register : Registra un usuario y envia un email al nuevo usuario.
        - login : Loguea un usuario.
        - logout : Logout a un usuario, invalida su token.
        - profile : Devuelve el perfil del usuario
        
    - DeviceController.php : Controlador que contiene los endpoint de la la tabla dispositivos.
        - getDevices : Devuelve todos los dispositivos de la BD.
        - getDevicesByBrand : Devuelve todos los dispositivos de la BD con una marca específica.
        - newDevice : Crea un nuevo dispositivo.
        - updateDevice : Actualiza un dispositivo.
        - deleteDevice : Borra un dispositivo.
    
    - RepairController.php : Controlador que contiene los endpoint de las reparaciones.
        - getAllRepairs : Devuelve todas las reparaciones de la BD.
        - newRepair : Crea una nueva reparación.
        - updateRepair : Edita una reparación.
        - deleteRepair : Borra una reparación.
    
    - DeviceRepairController.php : Controla las reparaciones de dispositivos.
        - getUserRepairs : Devuelve las reparaciones de un usuario.
        - getAllUserRepairByImei : Devuelve las reparaciones de un usuario en las que el imei coincida con el de las reparaciones tramitadas.
        - getAllUsersRepairs : Devuelve todas las reparaciones de usuarios de la bd.
        - newDeviceRepair : Usuario crea una nueva reparación para un dispositivo. Envia un correo con los datos de la reparación.
        - nextRepairState : Cambia el estado actual por el siguiente.
        - prevRepairState : Cambia el estado actual por un estado previo.
        - updateUserRepair : Actualiza los datos de la reparación.
        
    - UserController.php : Contiene los endpoints de usuarios
        - getUsers : Devuelve todos los usuarios.
        - updateUser : Actualiza un usuario.
        - deleteUser : Borra un usuario.
        - deleteUserByAdmin : Borra un usuario con permiso administrador.
        - userUpdateRole : Admin puede crear otros administradores.
        
- **Middlewares**
    - IsAdmin.php : Middleware que controla las peticiones a endpoints para Admin.

- **Mail**
    - UserRegistered.php : Mail utilizado para el registro.
    - UserRepair.php : Mail utilizado para la reparación de un dispositivo.

- **resources-views-emails**
    - registerMail.blade : Vista del mail de registro.
    - userRepairMail.blade: Vista del mail utilizado para la reparación de un dispositivo.


- **Models**
    - Device.php : Modelo de dispositivo.
    - Repair.php : Modelo de reparación.
    - Role.php : Modelo de rol.
    - User.php : Modelo de Usuario.


- **Explicación de la securización de la API**
    - Los usuarios que no estén logados pueden:
        - Registrarse
        - Loguearse
        - Ver todos los tipos de reparaciones
        - Ver todas las marcas
        - Ver todos los dispositivos de una marca

    - Los usuarios logueados con rol 'user', pueden además:
        - Actualizar su perfil
        - Crear reparaciones para dispositivos.
        - Ver sus reparaciones.
        - Ver sus reparaciones que coincidan con el imei proporcionado.
        - Hacer logout.
        - Ver su perfil.
        
    - Los usuarios que cuenten con el rol de 'superAdmin', pueden también:
        - Crear dispositivos
        - Actualizar dispositivos
        - Eliminar dispositivos
        - Crear reparaciones
        - Actualizar reparaciones
        - Eliminar reparaciones
        - Ver todos los usuarios
        - Actualizar reparaciones de usuarios.
        - Cambiar estado del proceso de reparación.
        - Ver todas las reparaciones de usuarios.
        - Actualizar roles de usuario.
        - Borrar usuario.

