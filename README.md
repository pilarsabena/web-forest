# Forest SRL — Sitio Web Corporativo

Sitio web institucional de **Forest SRL**, empresa de limpieza profesional y mantenimiento de espacios verdes con más de 40 años de trayectoria en Córdoba, Argentina. Certificaciones ISO 9001:2015, ISO 14001:2015 e ISO 45001:2018.

---

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| HTML5 / CSS3 | Estructura y estilos (sin frameworks externos) |
| JavaScript vanilla | Animaciones, lógica de calendario y UI |
| PHP + PHPMailer | Envío de formularios por email |
| EmailJS | Confirmaciones de reunión por email (lado cliente) |
| Firebase Realtime Database | Persistencia de turnos reservados (anti-solapamiento) |
| GSAP + ScrollTrigger | Animaciones de scroll y entrada de elementos |
| Google Fonts — DM Sans + Nunito | Tipografía principal + logo nav |

---

## Estructura de archivos

```
web-forest/
├── index.html                      # Página principal
├── index-vivid.html                # Variante de color alternativa
├── enviar.php                      # Backend de formularios (presupuesto y CV)
├── PHPMailer/                      # Librería PHPMailer para envío SMTP
│   ├── PHPMailer.php
│   ├── SMTP.php
│   ├── Exception.php
│   └── ...
├── assets/
│   ├── gral/                       # Imágenes generales
│   │   ├── logo.png                # Logo principal de Forest SRL
│   │   ├── iso.png                 # Los 3 logos ISO juntos (badge flotante nav)
│   │   ├── personas.png            # Foto del equipo
│   │   ├── forest.jpg              # Imagen corporativa (fondo del footer)
│   │   ├── forest1.png             # Imagen corporativa 2
│   │   ├── 9001.png                # Certificación ISO 9001:2015
│   │   ├── 14001.png               # Certificación ISO 14001:2015
│   │   ├── 45001.png               # Certificación ISO 45001:2018
│   │   └── bureau-veritas.png      # Logo Bureau Veritas
│   ├── clientes/                   # Logos de clientes institucionales
│   │   ├── credito.png             # Crédito Argentino
│   │   ├── sanatorio.png           # Sanatorio (zoom x2 aplicado en CSS)
│   │   └── ...
│   └── servicios/                  # Fotos del slideshow del hero
│       ├── fuero.png
│       ├── lavado.png
│       ├── merca.png
│       ├── Mercado-Norte.jpg
│       ├── past.png
│       ├── pastoo.png
│       ├── trib3.jpg
│       └── vidrios.png
├── emailjs-template-cliente.html   # Template email confirmación al cliente
├── emailjs-template-forest.html    # Template email notificación interna
└── README.md
```

---

## Secciones de la página

### 1. Badge ISO flotante
Rectángulo glass fijo en la esquina superior derecha que muestra `iso.png` con los 3 logos ISO. Se oculta en mobile (≤768px).

### 2. Navegación flotante
Barra fija centrada con efecto blur, logo 44px + texto "Forest." (Nunito Black), links y botón CTA. En mobile: botón hamburguesa que abre overlay full-screen con animación de X.

### 3. Hero
Título con animación GSAP. Slideshow de 8 imágenes a la derecha (`assets/servicios/`), rotando cada 3 segundos con puntos de navegación. Dos botones: "Solicitar Presupuesto" y "Conocernos".

### 4. Estadísticas
Contadores animados: **+40 años** de experiencia, clientes activos, espacios mantenidos.

### 5. Carrusel de imágenes ISO
Banda animada (295×190px por item) con solo las 3 certificaciones en orden: 9001 → 14001 → 45001. Las fotos usan `object-fit: cover`; los logos ISO usan `object-fit: contain` con padding interno.

### 6. Servicios
Tarjetas glassmorphism con efecto 3D tilt al hover.

### 7. Certificaciones & Asesoramiento
Tres burbujas circulares ISO 9001, 14001 y 45001.

### 8. Clientes
Marquee infinito con logos institucionales (96px de alto). Sanatorio tiene zoom ×2 aplicado via CSS para compensar el espacio en blanco de la imagen.

### 9. Nosotros — Nuestra Presentación
Sección unificada que arranca con la presentación institucional:
- 3 cards destacadas: 4 Provincias · +280 Puntos · +40 Años
- Misión y Visión
- Valores
- Dos imágenes fijas (personas.png + forest1.png) en grid 2 columnas
- Política Integrada de Gestión

### 10. Formulario de Presupuesto
Campos: Nombre, Empresa, Email, Teléfono, Servicio requerido. Dispara el flujo de reserva de reunión al enviarse.

### 11. Trabajá con Nosotros
Formulario de CV con carga de PDF.

### 12. Footer
Sin logo. Fondo `forest.jpg` con overlay semitransparente. Datos de contacto, navegación y copyright.

---

## Flujo de reserva de reunión

```
1. Empresa llena formulario de presupuesto
         ↓
2. enviar.php envía el mail y redirige con ?enviado=1&tipo=presupuesto
         ↓
3. Popup: "¿Querés agendar una reunión con nosotros?"
         ↓ (Sí)
4. Modal con calendario — elige día y horario (lunes a viernes, 9 a 17 hs)
         ↓
5. EmailJS envía dos mails simultáneos:
   → Cliente:     confirmación con fecha, hora y link de Google Meet
   → Forest SRL:  notificación interna con datos del cliente
         ↓
6. Firebase guarda el turno para evitar que otro lo reserve
```

---

## Configuración de servicios externos

### PHPMailer — `enviar.php`
```
Host:     mail.forestsrl.com.ar
Puerto:   465 (SMTPS)
Usuario:  formularios@forestsrl.com.ar
```

### EmailJS
```
Service ID:           service_8bi64ao
Template cliente:     template_res0bjx       → llega al que reservó
Template Forest SRL:  template_forest_notif  → llega a informacionforest@gmail.com
Public Key:           Y4gSaFN3dhO1ztNeJ
```

Variables: `{{user_name}}`, `{{user_email}}`, `{{fecha}}`, `{{hora}}`, `{{meet_link}}`, `{{booking_service}}`, `{{to_email}}`

### Firebase Realtime Database
```
Proyecto:  pagina-web-74184
URL:       https://pagina-web-74184-default-rtdb.firebaseio.com
Nodo:      /booked.json
Formato:   { "Mon Jun 09 2026-10": 1 }   ← clave: fecha-hora
```

### Google Meet
```
Link fijo:  https://meet.google.com/hum-dxpp-bbs
```

---

## Animaciones y efectos visuales

| Efecto | Descripción |
|---|---|
| Rama decorativa | SVG lateral con hojas animadas |
| Canvas partículas | Nodos flotantes con conexiones que reaccionan al mouse |
| Canvas ondas | Capas de olas verdes animadas en el fondo |
| Scroll reveal | Elementos entran con GSAP al aparecer en el viewport |
| Contadores | Estadísticas se incrementan animadas al hacer scroll |
| Tilt 3D | Tarjetas con perspectiva reactiva al mouse |
| Marquee | Carrusel de imágenes ISO y lista de clientes en scroll infinito |
| Slideshow hero | 8 fotos que rotan cada 3 segundos con fade y dots de navegación |

---

## Responsive design

| Breakpoint | Comportamiento |
|---|---|
| > 1024px | Layout completo, hero 2 columnas, grids 3 columnas |
| ≤ 1024px (tablet) | Hero 1 columna, grids 2 columnas, footer 2 columnas |
| ≤ 768px (mobile) | Hamburguesa, grids 1 columna, footer 1 columna, badge ISO oculto, padding reducido |

---

## Despliegue

Hosting PHP compartido (NutHost). Subir a `public_html`: `index.html`, `enviar.php`, carpetas `assets/` y `PHPMailer/`.

`index-vivid.html` es una variante de color alternativa — decidir cuál usar antes de subir a producción.

---

## Historial de cambios

### Corrección de burbujas ISO en sección Certificaciones
Las burbujas ISO colisionaban con la clase `.iso-badge` usada también para el badge flotante (que tenía `position: fixed`). Se renombró el badge flotante a `.iso-nav-badge` para eliminar el conflicto.

### Badge ISO flotante: rectángulo glass con imagen
La imagen `iso.png` a 72px era ilegible. Se reemplazó por un `<div class="iso-nav-badge">` con fondo glass y la imagen a 180px. Se oculta en mobile (≤768px).

### Footer: versiones correctas de certificaciones ISO
`ISO 9001 · ISO 14001 · ISO 45001` → `ISO 9001:2015 · ISO 14001:2015 · ISO 45001:2018`.

### Tipografía del nav: Nunito Black
El texto "Forest." del navbar adoptó la fuente **Nunito 900** para coincidir con la tipografía redondeada del logotipo.

### Actualización del teléfono de contacto
`+54 351 702-4934` → `+54 9 351 373-2964` en sección de contacto, footer y enlace `tel:`.

### Reorganización de assets
Imágenes sueltas en raíz de `assets/` movidas a `assets/gral/`. Carpeta `nosotros/` renombrada a `servicios/`. Todas las rutas HTML actualizadas.

### Carrusel ISO debajo de estadísticas
- Tamaño aumentado al doble: items de 295×190px
- Sin padding interior: imágenes llenan el rectángulo (`object-fit: cover`)
- ISOs con `object-fit: contain` + padding para no recortarse
- Solo 3 imágenes en orden: 9001 → 14001 → 45001

### Carrusel de clientes
- Tamaño aumentado 50%: chips de 96px de alto
- `overflow: hidden` para contener el zoom del Sanatorio
- Zoom ×2 en sanatorio.png via CSS (`transform: scale(2)`)
- `cfn.png` reemplazado por `credito.png`

### Logo del nav agrandado
`.nav-logo-img`: 32px → 44px en desktop, 26px → 36px en mobile.

### Padding de secciones reducido
`.section-flow`: `padding: 7rem 2rem` → `4rem 2rem` en todas las secciones para un ritmo visual más compacto.

### Footer: logo eliminado
Se quitó el logo de Forest SRL del footer. Solo queda el texto "Forest SRL" como título.

### Footer: fondo `forest.jpg`
`forest.jpg` como fondo del footer con overlay `rgba(235,242,235,0.82)` para legibilidad del texto.

### Sección Nosotros: "Nuestra Presentación"
Sección reestructurada con contenido de la propuesta comercial:
- 3 cards: 4 Provincias · +280 Puntos · +40 Años
- Misión y Visión
- Dos imágenes fijas (personas.png + forest1.png) antes de la Política Integrada

### Estadísticas: 30 → 40 años
Contador animado y todos los textos del sitio actualizados de "30 años" a "40 años".

### Hero slideshow: imágenes reales de `assets/servicios/`
Las referencias `1.jpg, 2.jpg...` reemplazadas por los nombres reales. Slideshow con 8 imágenes. `index-vivid.html` recibió el slideshow completo (antes solo tenía logo flotante).

### Responsive design: menú hamburguesa y correcciones mobile
- Menú hamburguesa con overlay full-screen animado
- `hero-desc`, `sec-sub`: `max-width: 100%` en mobile
- Formularios, calendario y time-slots con padding y columnas reducidos

### Sincronización y limpieza
`index-auxiliar.html` fue usado como archivo de prueba para previsualizar cambios antes de aplicarlos a producción. Una vez confirmados todos los cambios, se aplicaron a `index.html` e `index-vivid.html` y se eliminó el auxiliar.

---

## Contacto

**Forest SRL**
Bv. Los Granaderos 1743, Córdoba, Argentina
+54 9 351 373-2964
info@forestsrl.com.ar
