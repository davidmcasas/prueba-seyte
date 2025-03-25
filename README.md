# Prueba técnica para Seyte
## Por: David Martínez Casas

Se trata de una app web (API + Frontend) realizada en Laravel 12 (PHP 8.3) + Blade/Livewire.

La aplicación se encuentra desplegada en: https://seyte.davidmcasas.com

El repositorio se encuentra en: https://github.com/davidmcasas/prueba-seyte

El proyecto parte del Starter Kit de Laravel 12 con Livewire,
por lo que algunos elementos como la autenticación de usuario ya se dan hechos, lo cual agiliza el desarrollo.

### Tabla de Contenidos:
#### 1. [Diseño de Base de Datos](#a1)
#### 2. [Desarrollo de API](#a2)
#### 3. [Securización API y Roles](#a3)
#### 4. [Desarrollo Frontend](#a4)
#### 5. [Mejoras Propuestas](#a5)
#### 6. [Pasos para Desplegar](#a6)

---


## <a name="a1"></a> 1. Diseño de Base de Datos

Se ha planteado el grueso de la aplicación con dos tablas y modelos principales:

 - Clientes (clients) [2025_03_24_080001_create_clients_table.php](database/migrations/2025_03_24_080001_create_clients_table.php)
 - Citas (appointments) [2025_03_24_080121_create_appointments_table.php](database/migrations/2025_03_24_080121_create_appointments_table.php)

Se ha valorado la posibilidad de incluir un tercer modelo para los Reconocimientos, ya que se menciona
que una cita puede tener múltiples reconocimientos.
Pero finalmente se ha descartado porque el funcional no plantea la necesidad de almacenar ninguna información
asociada a los reconocimientos (empleado, resultado del reconocimiento, etc...). Por tanto se ha optado por
incluir un campo numérico dentro del modelo Cita que refleje el número de reconocimientos realizados en la cita.

Otras tablas relevantes:
- roles: tabla para los tres roles de usuario indicados en el funcional: admin, encargado y médico.
- personal_access_tokens: tabla donde Laravel Sanctum almacena los tokens de usuario que se utilizarán para consumir la API.


## <a name="a2"></a> 2. Desarrollo de API

Las rutas API se encuentran en: [api.php](routes/api.php)

Hay 4 rutas CRUD para Cliente:

- index: GET /api/client/
- show: GET /api/client/{client}
- store: POST /api/client
- update: PATCH /api/client/{client}

Y 4+1 rutas CRUD para Cita:

- index: GET /api/appointment/
- show: GET /api/appointment/{appointment}
- store: POST /api/appointment
- fill: POST /api/appointment/{appointment}/fill
- update: PATCH /api/appointment/{appointment}

La ruta fill tiene su uso reservado para cuando un médico complete una cita indicando el número de reconocimientos realizados.
Esta acción dará por finalizada la cita, impidiendo que se pueda editar en el futuro (ruta update).

No se han implementado rutas delete porque no es un requisito del funcional.

En los controladores API se han implementado todas las funciones asociadas a estas rutas:
 - [ClientController.php](app/Http/Controllers/API/ClientController.php)
 - [AppointmentController.php](app/Http/Controllers/API/AppointmentController.php)

La API se encuentra securizada mediante Laravel Sanctum

## <a name="a3"></a> 3. Securización API y Roles

Se han implementado 3 roles con 3 respectivas cuentas de usuario
 - Administrador (admin) -> admin@example.com 
 - Encargado (manager) -> manager@example.com
 - Médico (medic) -> medic@example.com

(Las 3 cuentas tienen la contraseña "password" por defecto)

Se han implementado diversas limitaciones para los roles, haciendo uso del middleware [CheckRole](app/Http/Middleware/CheckRole.php) para proteger rutas API,
o en algunos casos también haciendo comprobaciones de lógica en el controlador API.

 - Administrador: Tiene acceso a todo el CRUD disponible, así como todo lo que puedan hacer los demás roles, sin limitaciones de día.
 - Encargado: Puede ver todos los clientes y citas. Puede dar cita a clientes. No puede crear ni editar clientes. Puede editar citas, pero no completarlas (ruta fill)
 - Médico: Solo puede ver las citas del día actual. Puede completar las citas del día actual.

También se ha implementado la respectiva lógica en frontend: por ejemplo, Encargado no verá el botón de "Nuevo Cliente",
y Médico no verá el selector de fechas en el listado de citas, ya que solo puede ver las del día actual.

Al hacer uso de Laravel Sanctum para la autenticación y tokens de API, se puede generar un token asociado a un usuario mediante Tinker:

```
> php artisan tinker
> User::where('email', 'medic@example.com')->first()->createToken('auth_token')->plainTextToken;
```

Al ejecutar el comando anterior, se nos devolverá en la consola un token para el usuario medic, que podremos usar para simular llamadas a la API con Postman, por ejemplo. Al realizar una llamada con este token, Laravel Sanctum detectará el usuario al que está asociado el token y su rol, por lo que las restricciones de rol seguirán aplicándose.


## <a name="a4"></a> 4. Desarrollo Frontend

El diseño de frontend consta de una vista para cada uno de los modelos principales: Clientes y Citas.

Se han diseñado en forma de datatable dinámico paginado, con la ayuda de Livewire.
Los formularios de acciones (Nuevo Cliente, Editar, etc), se han planteado como modals superpuestos.

Clases Livewire:
- [ClientTable.php](app/Livewire/ClientTable.php)
- [CreateEditClient.php](app/Livewire/CreateEditClient.php)
- [AppointmentTable.php](app/Livewire/AppointmentTable.php)
- [CreateEditAppointment.php](app/Livewire/CreateEditAppointment.php)
- [FillAppointment.php](app/Livewire/FillAppointment.php)

Vistas correspondientes:
- [client-table.blade.php](resources/views/livewire/client-table.blade.php)
- [create-edit-client.blade.php](resources/views/livewire/create-edit-client.blade.php)
- [appointment-table.blade.php](resources/views/livewire/appointment-table.blade.php)
- [create-edit-appointment.blade.php](resources/views/livewire/create-edit-appointment.blade.php)
- [fill-appointment.blade.php](resources/views/livewire/fill-appointment.blade.php)

Gracias al uso de Livewire no he necesitado escribir ni una sola línea de JavaScript (que conste que no tengo ningún problema con JavaScript)


## <a name="a5"></a> 5. Mejoras Propuestas

En el funcional se proponen varias mejoras opcionales. En este apartado repaso todas y comento cómo las he interpretado e implementado:

### 5.1 Clientes y Citas: "Exportación a excel de los resultados filtrados en el listado."
He utilizado la librería "maatwebsite/excel" que permite generar archivos .csv o .xlsx de forma sencilla a partir de queries de Eloquent.
Su acción se llama desde el datatable respectivo, pasándole los filtros actuales.

- [ClientsExport.php](app/Exports/ClientsExport.php)
- [AppointmentsExport.php](app/Exports/AppointmentsExport.php)


### 5.2 Citas: "Esta cita se podrá modificar mientras no se haya realizado ningún reconocimiento."
En el [AppointmentController.php](app/Http/Controllers/API/AppointmentController.php)
he implementado que las funciones update y fill no puedan continuar si la Cita ya tiene reconocimientos registrados.
En el Frontend, los botones Editar no aparecerán si la Cita ya no se puede editar.


### 5.3 Citas: "El sistema deberá registrar el exceso de reconocimientos realizados respecto a los que se han contratado"
No tenía del todo claro como se desea interpretar esto a nivel de Backend. A nivel Frontend, en el datatable de Clientes, al lado del campo de reconocimientos incluidos en el contrato, he incluido un campo función sum que muestra el total de reconocimientos realizados.
Su valor se visualizará en rojo si supera el número del contrato, sirviendo de indicador visual.

### 5.4 Autenticación y roles: Recuperación de contraseña
La recuperación de contraseña ya viene de serie en el Starter Kit de Laravel 12, así que aquí no he tenido que hacer mucho.
Los roles y autenticación de API los explico en el punto [3. Securización API y Roles](#a3)

### 5.5 Registrar clientes con contrato activo y no activo
Dado que un contrato tiene fecha de inicio y fecha de fin, he interpretado como "activos" aquellos contratos cuyo período abarquen el día actual.
He implementado un filtro en el datatable de clientes para aplicar esta distinción.

### 5.6 Registrar citas realizadas
Dado que se entiende que las citas las realizan los médicos, he decidido crear la ruta fill para el rol médico como una acción especial
que sirve para actualizar la cita indicando el número de reconocimientos realizados, dando por finalizada la cita y evitando su edición futura.
Esta acción y sus implicaciones se informan al usuario en el modal de "Registrar Reconocimientos" en el datatable de Citas.

### Mejoras Omitidas
- Citas: "Visualización en modo calendario semanal o mensual."
- "Incluir testing en el backend"
- "Uso de Docker para el backend"

### Otros detalles a tener en cuenta
Es posible que aparezcan algunos mensajes en inglés, 
por ejemplo en las validaciones de Laravel en los formularios, o en el correo electrónico de recuperación.
Todos estos mensajes se pueden traducir (y debería hacerse en una aplicación real), pero no he querido entretenerme con ello.

El campo "código" de cliente no es editable y se genera automáticamente en el evento creating definido en el modelo: [Client.php](app/Models/Client.php)
El resto de campos, todos son editables ya que el funcional no especifica nada al respecto, con la única distinción de que el campo "CIF" es unique.

## <a name="a6"></a> 6. Pasos para desplegar: 

### 6.1. Clonar el repositorio al servidor donde se vaya a probar e instalar dependencias.
```
composer install
```
En caso de conflicto por tener varias versiones de composer, utilizar:
```
composer2 install
```

### 6.2. Copiar ".env.example" y renombrarlo a ".env", y configurarlo.

En el archivo .env es necesario configurar las variables de entorno de la base de datos que se vaya a utilizar.
Para MySQL: "DB_CONNECTION=mysql" y configurar el resto de variables DB_*

### 6.3. Generar clave hash de la aplicación.
```
php artisan key:generate
```
Laravel escribirá esta clave en el fichero .env en "APP_KEY"

### 6.4. Crear las tablas de la BBDD y ejecutar el Seeder.
```
php artisan migrate:fresh --seed
```
El seeder por defecto creará los roles de usuario y un usuario para cada rol.

Para alimentar la BD con datos de ejemplo, se debe ejecutar el DevSeeder aparte:

```
php artisan db:seed --class=DevSeeder
```

### 6.5. Compilación del Frontend

```
npm run build
```
Esto creara la carpeta public/build con los .js y .css compilados de la aplicación

Probado con Node 23

### 6.6. (Opcional) Configurar envío de email

Con idea de probar el formulario de recuperar contraseña.

En el .env, las variables MAIL_* están configuradas por defecto
para que los emails se registren en el Log de laravel en lugar de enviarse
("MAIL_MAILER=log"). Para que se envíen por smtp, hay que poner
"MAIL_MAILER=smtp" y configurar el resto de variables MAIL_*.


### 6.7. (Opcional) Ejecutar cacheado de Laravel
```
php artisan optimize
``` 
Esto también cachea las variables del .env, por lo que a partir de ahora, ante cualquier cambio en el .env
será necesario volver a ejecutar el cacheado.
