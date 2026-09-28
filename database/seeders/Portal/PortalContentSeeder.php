<?php

namespace Database\Seeders\Portal;

use App\Models\Portal\Banner;
use App\Models\Portal\NewsItem;
use App\Models\Portal\PortalDocument;
use App\Models\Portal\PortalEvent;
use App\Models\Portal\QuickLink;
use App\Models\Portal\Recognition;
use Illuminate\Database\Seeder;

class PortalContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Banner::query()->count() === 0) {
            Banner::insert([
                ['title' => 'Nueva Colección Mármol Italiano 2026', 'subtitle' => 'Exclusividad y alta gama para proyectos residenciales y comerciales exigentes.', 'badge' => 'Lanzamiento Exclusivo', 'image_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=80', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Gran Convivio Patrio y Festejos de Septiembre', 'subtitle' => 'Consulta la programación de eventos y días de asueto oficial autorizados.', 'badge' => 'Cultura y Eventos', 'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Optimización de Procesos y Guías Operativas', 'subtitle' => 'Actualiza tus solicitudes de permisos y vacaciones en el nuevo portal.', 'badge' => 'Recursos Humanos', 'image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1400&q=80', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (NewsItem::query()->count() === 0) {
            NewsItem::insert([
                ['title' => 'Mensaje de Dirección: Cierre exitoso del tercer trimestre y metas logradas', 'excerpt' => 'Agradecemos el compromiso extraordinario de todas las sucursales y centros de distribución por superar las expectativas de distribución este periodo.', 'category' => 'comunicados', 'author' => 'Dirección General', 'published_at' => '2026-09-12', 'image_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80', 'is_featured' => true, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Nuevo protocolo de permisos, vacaciones y guías operativas 2026-2027', 'excerpt' => 'Consulta los lineamientos actualizados y descarga los formatos oficiales en el repositorio de documentos claves para agilizar tus trámites.', 'category' => 'recursos', 'author' => 'Recursos Humanos', 'published_at' => '2026-09-10', 'image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80', 'is_featured' => false, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Preparativos oficiales para el Gran Convivio Patrio y Festejos del Mes', 'excerpt' => 'Conoce la programación de eventos festivos y los días de asueto oficial autorizados para todo el personal operativo y administrativo.', 'category' => 'cultura', 'author' => 'Comité de Cultura y Eventos', 'published_at' => '2026-09-08', 'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'is_featured' => false, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Lanzamiento exclusivo de la nueva línea de Mármol Italiano Premium', 'excerpt' => 'Ya se encuentra disponible el catálogo corporativo actualizado con acabados de alta gama para proyectos residenciales y comerciales de alta exigencia.', 'category' => 'comunicados', 'author' => 'Gerencia Comercial', 'published_at' => '2026-09-05', 'image_url' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=800&q=80', 'is_featured' => false, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Recognition::query()->count() === 0) {
            Recognition::insert([
                ['type' => 'aniversario', 'title' => '¡Feliz Aniversario Laboral!', 'body' => 'Carlos Mendoza cumple 5 años en la sucursal Xalapa Centro. ¡Gracias por tu entrega y trayectoria!', 'icon' => '🏆', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['type' => 'ascenso', 'title' => 'Ascenso Destacado', 'body' => 'Reconocemos a Sofía Rivas por su reciente nombramiento como Gerente Regional de Operaciones.', 'icon' => '⭐', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (QuickLink::query()->count() === 0) {
            QuickLink::insert([
                ['label' => '📌 Portal de Recursos Humanos', 'url' => '#', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['label' => '📞 Contacto con Reclutamiento (ATS)', 'url' => '#', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['label' => '💻 Soporte Técnico y Tickets', 'url' => '#', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['label' => '📂 Directorio General de Sucursales', 'url' => '#', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (PortalDocument::query()->count() === 0) {
            PortalDocument::insert([
                ['name' => 'Reglamento Interno 2026.pdf', 'external_url' => '#', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Formato Oficial Permisos.docx', 'external_url' => '#', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Guía de Identidad Visual.pdf', 'external_url' => '#', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (PortalEvent::query()->count() === 0) {
            PortalEvent::insert([
                ['title' => 'Aniversario y Convivio Patrio', 'description' => 'Celebración general en todas las sucursales a partir de las 14:00 hrs.', 'type' => 'festivo', 'event_date' => '2026-09-15', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Capacitación General', 'description' => 'Reunión mensual vía streaming y asignación de metas trimestrales.', 'type' => 'capacitacion', 'event_date' => '2026-09-22', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Festejo Colaboradores del Mes', 'description' => 'Reconocimiento a colaboradores cumpliendo años en el periodo de septiembre.', 'type' => 'cumple', 'event_date' => '2026-09-30', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
