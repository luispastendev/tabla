# Tabla Server Side Rendering
Tabla server side con codeigniter 4 y datatables

### **Instalación:**:

Clonar el respositorio

    > git clone ....

Instalar codeigniter 4 con todas sus dependencias

    > composer install

### **Base de datos:**

1. Crear una base de datos mysql en en blanco con codificación utf-8-mb4-spanish

2. Abrir el archivo .env y modificar los valores según sea tu caso

_ejemplo:_
```
app.baseURL = 'http://tabla.v'

database.default.hostname = localhost
database.default.database = tabla
database.default.username = root
database.default.password = ''
database.default.DBDriver = MySQLi
```

3. lanzar las migraciones 

```bash
php spark migrate
```


4. Lanzar los seeders

```bash
php spark db:seed 
```

5. Iniciar el proyecto

```bash
php spark serve
```