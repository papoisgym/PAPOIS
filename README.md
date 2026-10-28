# Proyecto — Administración de Proyectos

## PAPOIS: Software para Gimnasios

**Semestre:** 2026-II  
**Materia:** Administración de Proyectos  
**Grupo:** 1508  
**Profesor:** Alfredo Mondragón Escobar  
**Equipo (orden alfabético por apellidos):**

* Gómez Galindo Dante Alejandro
* Gómez García Alexis Misael
* Hernández Villaneda Fabián
* Luna Valdez Martín Sebastián

**Periodo del proyecto:** 1 de octubre de 2026 – 25 de octubre de 2026 (entrega final: 25 de octubre de 2026)

**Repositorio:** https://github.com/papoisgym/PAPOIS  
**Sitio publicado:** https://papoisgym.github.io/PAPOIS/

> Proyecto escolar. El gimnasio PAPOIS, sus precios, direcciones y socios son ficticios.

---

## Descripción general

PAPOIS es un sistema de software para la administración de gimnasios, desarrollado como producto del proyecto de la materia Administración de Proyectos (Ingeniería en Computación, UNAM FES Aragón). Está pensado para que administradores, recepcionistas e instructores gestionen el gimnasio y para que los socios consulten su expediente de entrenamiento, renueven su membresía y reserven salones y equipo desde Internet.

El mercado meta son los gimnasios de la zona metropolitana de la CDMX. El sistema busca evitar demoras en pagos, olvidos de vencimientos de membresía, pérdida de rentas de salones por cancelaciones no registradas y falta de información para tomar decisiones.

Lo que más hace el sistema:

* **Control de acceso con credencial:** cada socio tiene una credencial **QR** y la recepción valida la entrada con lector de código de barras USB (funciona como teclado) o con la **cámara** del dispositivo. El panel muestra fotografía, nombre, número de socio, membresía y pagos, y resuelve **ACCESO AUTORIZADO** o **DENEGADO** con el motivo.
* **Base de datos en Google Sheets:** cada cambio se guarda en la hoja de cálculo y los datos se sincronizan cada 30 segundos.
* **Reservaciones sin pérdidas:** bloquea horarios ocupados, no permite reservar con membresía vencida y libera el espacio al cancelar.
* **Información para decidir:** reportes del plan más vendido, salones y equipo más reservados e instructores con más reservaciones.

---

## Requisitos para ejecutar

| Requisito | Detalle |
|-|-|
| Navegador web | Cualquier navegador actual (Chrome, Edge, Firefox) |
| Sistema operativo | Windows (también funciona en otros sistemas) |
| Instalación | Ninguna |
| Librerías | `qrcode.js` (genera la credencial QR) y `jsQR.js` (lee QR con la cámara); van en la misma carpeta que `index.html` |
| Internet | Necesario para guardar en Google Sheets y para cargar las fotografías de la galería; sin conexión el sistema trabaja en modo local |
| Cámara | El escaneo con cámara requiere **HTTPS** o `localhost` (el sitio de GitHub Pages ya usa HTTPS) |

---

## Estructura del proyecto

```
PAPOIS/
├── index.html    ← Sitio completo (HTML, CSS y JavaScript)
├── qrcode.js     ← Librería para generar la credencial QR
├── jsQR.js       ← Librería para leer QR con la cámara
├── img/          ← Fotos del sitio (hero.jpg, opcional)
├── (fotografía y videos del carrusel de la página principal, con el nombre que usa index.html)
└── README.md     ← Este archivo
```

---

## Módulos del sistema

### Módulo 1 — Sitio público

Carrusel de fotografías y videos, sección *Quiénes somos*, instalaciones agrupadas por áreas (zonas de entrenamiento, salones y espacios de clases, servicios y bienestar), planes y contacto, con acceso a la inscripción.

| Plan | Precio ilustrativo | Incluye |
|-|-|-|
| Básico | $399 / mes | Acceso al gimnasio, horario de 6:00 a 22:00, reserva de salones |
| Plus | $599 / mes | Lo del Básico, clases grupales ilimitadas, rutina personalizada, expediente en línea |
| Premium | $799 / mes | Lo del Plus, dieta personalizada, reserva prioritaria de equipo, acceso al área Spa, 1 invitado al mes |

### Módulo 2 — Portal de socios

| Sección | Función |
|-|-|
| Registro y acceso | Alta de nuevos socios con ID consecutivo (PAP-XXXX) y acceso con ID de socio o correo |
| Mi membresía | Plan, vencimiento y estado; renovación (pago simulado); captura de correo y teléfono para avisos; historial de pagos |
| Reservar | Reservación de salones y equipo operativo, con bloqueo de horarios ocupados y cancelación que libera el espacio |
| Mi QR | Credencial QR del socio con descarga de la imagen |
| Mi expediente | Rutina, dieta, avance físico y asistencia |

### Módulo 3 — Panel de administración

Acceso con roles **Administrador** (las 11 secciones) y **Recepción** (socios, asistencia, reservaciones y avisos).

| Sección | Función |
|-|-|
| Socios | Altas con cobro, búsqueda, cobro de renovaciones, consulta del QR y bajas |
| Asistencia | Control de acceso por lector USB o cámara (QR y códigos de barras), panel con datos del socio, resultado autorizado o denegado con motivo, historial de intentos e ignora lecturas repetidas |
| Reservaciones | Reservación de salones y equipo a instructores y cancelaciones |
| Equipo | Adquisiciones (generan el egreso), mantenimiento y bajas; el equipo que no está operativo no se ofrece para reservar |
| Dietas y rutinas | Banco de dietas y rutinas, avance físico y asignación a socios |
| Contabilidad | Ingresos, egresos, balance y facturas con folio |
| Empleados | Altas y registro de nómina |
| Administradores | Alta y baja de usuarios del panel y sus roles |
| Avisos | Socios con membresía por vencer (días de anticipación configurables) y mensaje listo para enviar por correo o SMS |
| Reportes | Plan más vendido, salones y equipo más reservados, instructores con más reservaciones |
| Datos y Excel | Exportación de los registros a CSV (se abre en Excel), estado de la base de datos y restauración de datos de ejemplo |

---

## Acceso de prueba

Los datos de ejemplo son ficticios.

| Perfil | Usuario | Contraseña |
|-|-|-|
| Administrador | `admin` | `admin123` |
| Recepción | `recepcion` | `recep123` |
| Socio | `PAP-1001` | `1234` |

---

## Cómo ejecutar

**En línea:** abrir https://papoisgym.github.io/PAPOIS/

**En tu computadora:**

1. Descargar el repositorio (**Code → Download ZIP**) y descomprimirlo.
2. Verificar que `index.html`, `qrcode.js` y `jsQR.js` estén en la misma carpeta.
3. Abrir `index.html` en el navegador.
4. Entrar al portal de socios o al panel de administración con los accesos de prueba.

---

## Despliegue

El sitio se publica con **GitHub Pages**:

1. En el repositorio, ir a **Settings → Pages**.
2. En *Source* elegir **Deploy from a branch**, rama `main` y carpeta `/ (root)`.
3. Guardar. El sitio queda disponible en https://papoisgym.github.io/PAPOIS/.

---

## Base de datos (Google Sheets)

La conexión se configura al inicio del script de `index.html`, en el objeto `CONFIG`:

* `API_URL`: dirección de la aplicación web de Google Apps Script conectada a la hoja de cálculo.
* `CLAVE`: clave que acompaña a cada solicitud.

Funcionamiento: al abrir el sitio se cargan los datos; después de cada cambio se guardan en la hoja (a los 0.7 segundos) y cada 30 segundos se sincronizan los cambios hechos desde otros equipos. El pie de página indica el estado: *Datos en Google Sheets*, *modo local* o *sin conexión con la base de datos*. Si `API_URL` está vacío o falla la conexión, el sistema usa el almacenamiento local del navegador (`localStorage`).

---

## Pruebas

Se ejecutaron 27 pruebas funcionales sobre la versión final (acceso de socios y administradores, reservaciones, pagos, control de acceso, equipo, contabilidad, exportación y reportes) y las 27 resultaron aprobadas. El detalle está en la sección 23 del documento del proyecto.

---

## Notas técnicas

* Todo el sistema está en un solo archivo `index.html`; solo usa las librerías locales `qrcode.js` y `jsQR.js`.
* La reservación valida que el socio tenga membresía vigente y que el salón o equipo no esté ocupado en la fecha y hora elegidas.
* El control de acceso exige conexión confirmada con la base de datos: sin ella **deniega** la entrada, porque no puede verificar la membresía ni registrar el intento.
* El lector de códigos acepta el ID del socio o el contenido del QR (`PAPOIS|ID`), valida los caracteres permitidos e ignora la misma lectura repetida en menos de 3.5 segundos.
* La exportación a CSV no incluye contraseñas.
* Los datos de ejemplo se cargan la primera vez y pueden restaurarse desde la sección *Datos y Excel* del panel.
* El diseño se adapta a computadora y celular, y respeta el modo claro u oscuro del dispositivo.

---

## Limitaciones

* No envía correos ni SMS desde el sitio: la sección *Avisos* abre el correo o la app de mensajes del dispositivo con el texto ya escrito. El envío diario automático depende del script de Google Apps Script, que no forma parte de este repositorio.
* Las contraseñas se validan en el navegador y no están cifradas, y la `CLAVE` de la base de datos es visible en el código del sitio, por lo que el sistema no debe usarse con datos reales de personas.
* El pago de membresías es simulado y las facturas son registros internos, no comprobantes fiscales.
* El lector de código de barras físico no se probó con un equipo real; la lectura con cámara y la captura por teclado sí están implementadas.
* Las fotografías de la galería se cargan desde Internet y son ilustrativas.
