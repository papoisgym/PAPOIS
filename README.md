Proyecto — Administración de Proyectos
PAPOIS: Software para Gimnasios
Semestre: 2026-II  
Materia: Administración de Proyectos  
Profesor: Alfredo Mondragón Escobar  
Equipo (orden alfabético por apellidos):
Gómez Galindo Dante Alejandro
Gómez García Alexis Misael
Hernández Villaneda Fabián
Luna Valdez Martín Sebastián
Periodo del proyecto: 1 de octubre de 2026 – 31 de octubre de 2026
Repositorio: https://github.com/papoisgym/PAPOIS  
Sitio publicado: https://papoisgym.github.io/PAPOIS/
> Proyecto escolar. El gimnasio PAPOIS, sus precios, direcciones y socios son ficticios.
---
Descripción general
PAPOIS es un sistema de software para la administración de gimnasios, desarrollado como producto del proyecto de la materia Administración de Proyectos (Ingeniería en Computación, UNAM FES Aragón). Está pensado para que administradores, recepcionistas e instructores gestionen el gimnasio y para que los socios consulten su expediente de entrenamiento y reserven salones y equipo desde Internet.
El mercado meta son los gimnasios de la zona metropolitana de la CDMX. El sistema busca evitar demoras en pagos, olvidos de vencimientos de membresía, pérdida de rentas de salones por cancelaciones no registradas y falta de información para tomar decisiones.
---
Requisitos para ejecutar
Requisito	Detalle
Navegador web	Cualquier navegador actual (Chrome, Edge, Firefox)
Sistema operativo	Windows (también funciona en otros sistemas)
Instalación	Ninguna
Dependencias	Ninguna (HTML, CSS y JavaScript en un solo archivo)
---
Estructura del proyecto
```
PAPOIS/
├── index.html    ← Sitio completo (HTML, CSS y JavaScript)
├── img/          ← Fotos del sitio (opcional)
└── README.md     ← Este archivo
```
---
Módulos del sistema
Módulo 1 — Sitio público
Presentación del gimnasio, instalaciones, planes y contacto, con acceso a la inscripción.
Plan	Precio ilustrativo	Incluye
Básico	$399 / mes	Acceso al gimnasio, horario de 6:00 a 22:00, reserva de salones
Plus	$599 / mes	Lo del Básico, clases grupales, rutina personalizada, expediente en línea
Premium	$799 / mes	Lo del Plus, dieta personalizada, reserva prioritaria de equipo, 1 invitado al mes
Módulo 2 — Portal de socios
Registro de nuevos socios y acceso con ID de socio o correo.
Renovación de membresía (pago simulado) y consulta de pagos.
Reservación de salones y equipo con bloqueo de horarios ocupados y cancelación que libera el espacio.
Expediente en línea: rutina, dieta, avance físico y asistencia.
Captura de correo y teléfono para avisos de vencimiento.
Módulo 3 — Panel de administración
Acceso con roles Administrador (todo el panel) y Recepción (socios, asistencia, reservaciones y avisos).
Sección	Función
Socios	Altas, bajas, cobro de renovaciones y búsqueda
Asistencia	Registro de entrada por ID de credencial (compatible con lector de código de barras que funcione como teclado)
Reservaciones	Reservación de salones y equipo a instructores y cancelaciones
Equipo	Adquisiciones, mantenimiento y bajas
Dietas y rutinas	Banco de dietas y rutinas, avance físico y asignación a socios
Contabilidad	Ingresos, egresos, balance y facturas con folio
Empleados	Altas y registro de nómina
Administradores	Alta y baja de usuarios del panel
Avisos	Socios con membresía por vencer y mensaje listo para enviar por correo o SMS
Reportes	Plan más vendido, salones y equipo más reservados, instructores con más reservaciones
Datos y Excel	Exportación de los registros a CSV, que se abre en Excel
---
Acceso de prueba
Los datos de ejemplo son ficticios.
Perfil	Usuario	Contraseña
Administrador	`admin`	`admin123`
Recepción	`recepcion`	`recep123`
Socio	`PAP-1001`	`1234`
---
Cómo ejecutar
En línea: abrir https://papoisgym.github.io/PAPOIS/
En tu computadora:
Descargar el repositorio (Code → Download ZIP) y descomprimirlo.
Abrir `index.html` en el navegador.
Entrar al portal de socios o al panel de administración con los accesos de prueba.
---
Despliegue
El sitio se publica con GitHub Pages:
En el repositorio, ir a Settings → Pages.
En Source elegir Deploy from a branch, rama `main` y carpeta `/ (root)`.
Guardar. El sitio queda disponible en https://papoisgym.github.io/PAPOIS/.
---
Notas técnicas
Todo el sistema está en un solo archivo `index.html`, sin librerías externas.
Los datos se guardan en el almacenamiento local del navegador (`localStorage`). Cada visitante ve y modifica solo sus propios datos; no hay una base de datos compartida entre usuarios.
Los datos de ejemplo se cargan la primera vez y pueden restaurarse desde la sección Datos y Excel del panel.
La reservación valida que el socio tenga membresía vigente y que el salón o equipo no esté ocupado en la fecha y hora elegidas.
La exportación a CSV no incluye contraseñas.
El diseño se adapta a computadora y celular, y respeta el modo claro u oscuro del dispositivo.
---
Limitaciones
No envía correos ni SMS de forma automática: la sección Avisos abre el correo o la app de mensajes del dispositivo con el texto ya escrito.
No hay respaldo automático ni base de datos compartida; los datos viven en el navegador de cada usuario.
Las contraseñas se validan en el navegador y no están cifradas, por lo que el sistema no debe usarse con datos reales de personas.
El pago de membresías es simulado y las facturas son registros internos, no comprobantes fiscales.
El lector de código de barras no se probó con un equipo físico.
