# Bitácora — Tablero de vacantes (auditoría)

> Bitácora acumulativa simple. Cada implementación agrega una entrada con fecha (UTC) sin reescribir historial.

Formato por entrada: Fecha / Objetivo / Cambios por capa / Filtros afectados / Cómo probar / Pendiente.

---

## [2026-09-22] — Fase 0: login local + bitácora inicial

### Objetivo
- Login de esta fase con usuario normal local (`users` en sqlite). No usar `external_auth` para auth.
- Crear `docs/` como bitácora auditable. Diseño del dashboard intacto.

### Cambios
- **Infrastructure (config):** `config/auth.php` provider `users` → `App\Models\User::class` (antes `CoreUsuario::class` externo). Código Auth externo (`Domain/Auth`, `Application/Auth`, `Infrastructure/Auth/*` + bindings en `AppServiceProvider`) se conserva inerte para reconexión futura por otro agente.
- **Docs:** creada `docs/BITACORA.md`.

### Filtros afectados
- Ninguno aún.

### Cómo probar
- `php artisan migrate:fresh --seed` (sqlite) → login `test@example.com / password` → acceso a `/dashboard`, `/vacantes`, `/candidatos`.
- `php artisan test --filter=Dashboard` en verde.

### Pendiente
- Otro agente reconectará login a `core_usuario` en `external_auth` (`AUTH_MODEL`, `EloquentExternalUserRepository`, `AesEcbPasswordVerifier`).

---

## [2026-09-22] — Fase 1: catálogos DB_EXTERNAL (descubrimiento, solo lectura)

### Objetivo
- Identificar tablas reales de región/zona/sucursal/ciudad/estado en `external_auth` para los dropdowns. Vacantes/candidatos siguen en local.

### Hallazgos (conexión `external_auth` OK)
- `sucursal` (sucursal_id, zona_id, nombre, ciudad, municipio, estado, estatus AC, eliminado) — 52 activas aprox.
- `zona` (zona_id, nombre) — 6 zonas; `regiones` (id, nombre).
- `catalogociudades` (id, nombre); `cat_inegi_estados` (cve_ent, nom_ent).
- `sucursal` no guarda región textual: el filtro de sucursal por región se resuelve vía `zona_id`.

### Cambios
- Ninguno en código; solo investigación para Fase 2.

### Pendiente
- Ninguno.

---

## [2026-09-22] — Fase 2: dashboard hexagonal con datos reales + filtros funcionales

### Objetivo
- Eliminar hardcode del tablero sin tocar diseño. Todos los KPIs + tabla + contador reaccionan a los 8 filtros.

### Cambios por capa
- **Domain (`app/Domain/Dashboard/`):** `ValueObjects/VacancyFilter` (normaliza status `abierta→activa`, valida mes/año/rango fechas) + puertos `VacancyStatsRepositoryInterface`, `CatalogRepositoryInterface`. Sin Eloquent/Laravel.
- **Application (`app/Application/Dashboard/GetDashboardStatsUseCase.php`):** orquesta KPIs (promedio cobertura, críticas >20 días, mes/año dinámicos `now()`, históricos `año/año-1` en vez de 2025/2026 quemados).
- **Infrastructure (`app/Infrastructure/Dashboard/`):** `EloquentVacancyStatsRepository` (DB local, portable sqlite→mysql: sin `julianday()`, promedios con Carbon, `whereMonth/Year/Date`) + `ExternalCatalogRepository` (`zona`/`regiones`/`sucursal` AC/`catalogociudades`/`cat_inegi_estados` con cache 10 min y fallback a `DISTINCT` local si la externa falla). Binds en `AppServiceProvider`.
- **Adaptador entrada (`app/Livewire/VacancyDashboard.php`):** inyecta UseCase, hook `updated()` + `resetPage()` (antes `updatingFilters()` inexistente), props `region/branch` string compatibles con schema real (+ alias `region_id/branch_id` legacy), `#[Layout('layouts.app')]` (antes `components.layouts.app` inexistente → 500).
- **Vista (mismo diseño, solo datos):** `livewire/vacancy-dashboard.blade.php` — dropdowns región/sucursal/puesto/año dinámicos (`$catalog*`), status `activa=Abierta`, tabla con columnas reales (`folio/title/type/branch/reason/region`, responsable `N/A` sin columna en schema), etiquetas mes/año/históricos dinámicas.
- **Ruta (`routes/web.php`):** `Route::get('dashboard', VacancyDashboard::class)` (antes `Route::view` estático). Vista quemada archivada en `dashboard-legacy.blade.php`.
- **Modelo (`app/Models/Vacancy.php`):** `closed_at` fillable + casts datetime.
- **Auth local (alcance pedido):** `config/auth.php` provider → `User::class`; `FortifyServiceProvider::authenticateUsing` intenta local (`users` + Hash) primero y conserva fallback externo intacto.
- **Tests:** `tests/Feature/VacancyDashboardTest.php` (status, región/sucursal/puesto+reset, mes/año/rango), `tests/Feature/DashboardStatsTest.php` (UseCase con datos reales + `VacancyFilter`). `database/factories/VacancyFactory.php` para QA.

### Filtros afectados (8/8 verificados con tests)
- Estatus, región, sucursal, puesto (title LIKE), mes, año, desde/hasta. Rango inválido (`from>to`, formato) se ignora sin romper.

### Cómo probar
- `php artisan migrate:fresh --seed` + `Vacancy::factory()->count(40)->create()` → login `test@example.com / password` → `/dashboard`, aplicar cada filtro, `Limpiar filtros`.
- `php artisan test --filter="Dashboard|VacancyDashboard|Authentication"` → 12 tests, 11 pass + 1 skip preexistente.
- Suite completa: 31 tests, 29 pass. Fallo restante `SecurityTest@renders_without_two_factor` es **preexistente y fuera de alcance**: la vista está en español (`Actualizar contraseña`) y el test espera inglés (`Update password`); ningún archivo de settings fue tocado.

### Pendiente
- Columna `responsable/categoría` no existe en `vacancies` → tabla muestra `N/A` hasta futura migración.
- Otro agente: login 100% `core_usuario` externo (quitar intento local en `FortifyServiceProvider`).

---

## [2026-09-24] — Fase 3: alta con catálogos reales + limpieza a 3 muestras

### Objetivo
- El modal "Nueva Vacante" (`/vacantes`) mostraba sucursales/regiones inventadas; debe listar las verdaderas de `DB_EXTERNAL`.
- Limpiar las 40 vacantes de QA y dejar solo 3 de muestra para probar.

### Cambios por capa
- **Infrastructure (`ExternalCatalogRepository`):** lectura defensiva de filas (objeto/array/escalar, omite vacíos), en cache solo arrays planos + claves versionadas `catalog.v2.*` (había entradas rancias que causaban warnings `Attempt to read property nombre`). `cache:clear` ejecutado.
- **Adaptador entrada (`Vacancies/Index.php` + blade):** `render()` inyecta `CatalogRepositoryInterface` → `regionOptions` (zona + regiones) y `branchOptions` (sucursales AC, filtradas por zona al elegir región + `updatedRegion` resetea sucursal). Defaults vacíos con placeholder "Selecciona…", `save()` limpia branch/región. Diseño Flux intacto.
- **Bugs reales encontrados al sembrar muestras (corregidos):**
  - `Vacancy::$fillable` no incluía `created_at/updated_at` → las fechas se ignoraban en `create()` (críticas y promedios salían mal). Agregados al fillable.
  - Carbon 3 devuelve `diffInDays` con signo → `avgCoverageDays` daba `-5` y "días vacante" negativo. `abs()` en `EloquentVacancyStatsRepository` y en `vacancy-dashboard.blade.php:276`.
- **Datos dev (`database.sqlite`, no toca externa ni tests):** borradas 40 de QA; 3 muestras con valores reales coherentes zona↔sucursal: `VAC-2026-DEMO01` activa reciente (Asesor de Ventas/POZA RICA/N1), `DEMO02` activa crítica 30 días (Chofer/CD MENDOZA/G2), `DEMO03` cerrada con 8 días de cobertura (Encargado de Almacen/ALTAMIRA/N2, hired este mes). Resultado: total 3, promedio 8 días, 1 crítica (Chofer/CD MENDOZA/G2), abiertas mes 1, contrataciones mes 1, cobertura año 33%.
- **Tests nuevos (`tests/Feature/VacancyCreateTest.php`):** el modal lista catálogos reales (sin "Veracruz Centro"/"Xalapa Banderilla") y el alta con `N1/POZA RICA` guarda correctamente.

### Filtros afectados
- Ninguno del dashboard; alta ahora guarda región/sucursal reales que los filtros del dashboard sí encuentran.

### Cómo probar
- `/vacantes` → Nueva Vacante → Región lista 10 valores (G1..N2 + ZONA …), Sucursal ~52 reales; al elegir región se filtran por zona. Guardar y ver folio + aparición en `/dashboard`.
- `php artisan test` → 33 tests, 31 pass; único fallo el preexistente `SecurityTest` (vista en español vs test en inglés, fuera de alcance).

### Pendiente
- `candidates` está en 0 registros; sembrar muestras cuando se trabaje ese módulo.

---

## [2026-09-24] — Fase 3.1: Región del alta solo G/N (directriz equipo)

### Objetivo
- El equipo indicó que en "Nueva Vacante → Región" solo deben aparecer las zonas G y N. Se eliminan los valores de la tabla `regiones` (ZONA CORDOBA, ZONA VERACRUZ…) del dropdown.

### Cambios
- **Infrastructure (`ExternalCatalogRepository::regions()`):** solo tabla `zona` filtrada con `/^[GN]\d+$/i` → G1, G2, G3, G4, N1, N2. Clave de caché versionada a `catalog.v3.regions` + `cache:clear`. El cambio aplica igual al filtro Región del dashboard (mismo catálogo).
- Las 3 muestras (N1/G2/N2) siguen siendo válidas; `branches($region)` por `zona_id` sin cambios.

### Cómo probar
- `/vacantes` → Nueva Vacante → Región lista exactamente 6 valores. `php artisan test --filter="VacancyCreate|VacancyDashboard|Dashboard"` → 9/9.

---

## [2026-09-25] — Fase 4: administración de la landing desde el dashboard

### Objetivo
- Cada sección de `welcome.blade.php` debe alimentarse desde el sistema una vez logueado, sin afectar el diseño actual. El menú por roles/permisos queda para una fase posterior.

### Cambios por capa
- **Persistencia (7 migraciones `2026_09_25_000001–000007`):** `portal_banners`, `portal_news`, `portal_recognitions`, `portal_quick_links`, `portal_documents`, `portal_events`, `feedback_messages`. Campos híbridos imagen/archivo (`image_url`+`image_path`, `external_url`+`file_path`), `sort_order`, flags `is_active`/`is_published`/`is_featured`.
- **Infrastructure (modelos `app/Models/Portal/`):** `Banner`, `NewsItem`, `Recognition`, `QuickLink`, `PortalDocument` (con `downloadUrl()`), `PortalEvent`, `FeedbackMessage`. `Banner/NewsItem` resuelven `Storage::disk('public')` o URL externa.
- **Domain (`app/Domain/Portal/Contracts/PortalRepositoryInterface.php`):** puerto puro (solo arrays, sin Eloquent): lectura activa/ordenada por agregado, `store/update/destroy/find` genéricos + inbox de buzón (`feedbackInbox`, `storeFeedback`, `markFeedbackRead`, `archiveFeedback`, `deleteFeedback`).
- **Application (`app/Application/Portal/UseCases/`):** `ListPublishedContentUseCase` (lectura pública de la landing), `ManagePortalContentUseCase` (CRUD admin), `SubmitFeedbackUseCase` (valida min 3 caracteres). Testeables sin Laravel.
- **Infrastructure (adaptador `app/Infrastructure/Portal/Repositories/EloquentPortalRepository.php`):** implementa el puerto con Eloquent; serializa banners/noticias con alias legacy (`titulo/subtitulo/imagen/badge`, `extracto/autor/fecha/destacado/categoria`, `badge_class`, `day/month/badge` en eventos); borra archivo subido al eliminar. Bind puerto→adaptador en `AppServiceProvider`.
- **Adaptadores entrada:** `app/Http/Controllers/Portal/LandingController.php` (`index` delega al UseCase y pasa `$banners/$noticiasCorporativas/$recognitions/$quickLinks/$documents/$events` a la vista; `storeFeedback` valida y guarda) + Livewire `app/Livewire/Portal/PortalManager.php` (un manager con `section`, modal crear/editar, uploads híbridos, moderación de buzón) + vista `resources/views/livewire/portal/manager.blade.php` (tabs por sección, tablas, inbox con leído/archivar/eliminar).
- **Rutas (`routes/web.php`):** `GET /` → `LandingController@index` (antes `Route::view` estático), `POST /buzon` → `storeFeedback`, `GET /portal/{section}` (auth+verified, 7 secciones) → `PortalManager`. Sin gates por ahora; `TODO can('portal.*')` para fase de roles.
- **Sidebar (`layouts/app/sidebar.blade.php`):** nuevo grupo `Contenido Portal` con 7 items (mismo estilo Flux que Plataforma).
- **Vista landing (`welcome.blade.php`, mismo diseño):** carrusel desde `$banners` (`@js($slides)`), noticias desde DB (buscador/categoría filtran en query; fallback al `collect()` legacy si la tabla está vacía), reconocimientos/accesos/documentos/eventos con `@forelse` + fallback idéntico al contenido anterior, buzón como `POST` con `@csrf` + mensajes `session('feedback_ok')`.
- **Seeder (`database/seeders/Portal/PortalContentSeeder.php`):** siembra los contenidos hardcoded originales como datos iniciales (idempotente por conteo). `storage:link` creado.

### Correcciones visuales (misma fase)
- Sidebar: faltaba el `</flux:sidebar.group>` de cierre del grupo Portal (rompía todo el layout logueado); agregados `icon="photo"` y `:current` con `?->parameter('section')` a prueba de nulos.
- Landing: `x-data="{... slides: @json ...}"` generaba comillas dobles dentro de atributo con comillas dobles (pantalla negra); cambiado a atributo con comillas simples + `@js($slides)`.

### Cómo probar
- `php artisan migrate` + `php artisan db:seed --class="Database\\Seeders\\Portal\\PortalContentSeeder"` → `/` muestra los mismos contenidos que antes pero desde DB.
- Login → grupo Contenido Portal → crear/editar/eliminar en cada sección → verificar reflejo en `/` (navegación privada para evitar caché).
- Tablas vacías → `/` muestra el fallback legacy sin romperse. `?categoria=cultura` y `?search=protocolo` filtran noticias.
- Buzón público: envío válido guarda y muestra éxito; inválido (<3 caracteres) devuelve error; inbox permite marcar leído/archivar/eliminar.
- `php artisan test`: 31/33 pasan; único fallo el preexistente `SecurityTest@renders_without_two_factor` (vista en español vs test en inglés, fuera de alcance). Subconjunto sidebar/auth: `php artisan test --filter="profile|dashboard|vacanc"` → 14/14.

### Pendiente
- Menú por roles/permisos (`TODO can('portal.*')` en rutas + sidebar).
- Header (marca) y footer de la landing aún fijos en Blade.
- `Vacancies/Index.php` sigue usando `Vacancy::` directo (violación hexagonal preexistente; el módulo Portal nuevo sí usa repositorio vía interfaz).

---

## [2026-09-25] — Fase 4.1: reporte para jefatura (texto, sin editar el .docx)

### Objetivo
- Entregar el texto de la segunda etapa (15–25 de septiembre) para que el responsable lo formatee manualmente en Word.

### Cambios
- Se intentó la edición automatizada del `.docx` con `python-docx`, pero el formato resultante no igualó al original (espaciados y tarjetas), así que **se revirtió el documento a su estado original** (26 párrafos de cuerpo, 6 tablas) y se eliminó el respaldo. No queda ningún cambio en `Reporte_Tecnico_Operativo.docx`.
- El texto de la nueva sección se entregó en el chat: portada (periodo 15–25), ajuste al Resumen, nota al Módulo 4 y sección “4. Segunda Etapa” completa (Módulo 5 + cimientos técnicos).
